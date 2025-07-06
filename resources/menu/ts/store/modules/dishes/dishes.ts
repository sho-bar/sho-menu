import type { Dish, NoDishesResponse, FetchDishesActionParams, FetchDishesMutationParams } from '@/types'
import type DishesState from './DishesState'
import type RootState from '@menu/store/RootState'
import axios from 'axios'
import { Module } from 'vuex'
import addParamToUrl from '@/modules/addParamToUrl'
import removeParamToUrl from '@/modules/removeParamToUrl'
import getDishFromUrl from '@menu/modules/getDishFromUrl'
import dishFields from '@menu/modules/dishFields'

const MAX_DISHES_PER_PAGE = 100

const dishes: Module<DishesState, RootState> = {
    namespaced: true,

    state: {
        loading: true,
        selectedDish: null,
        dishes: [],
        isFetching: false,
    },

    getters: {
        loading: s => s.loading,
        selectedDish: s => s.selectedDish,
    },

    mutations: {
        async FETCH_DISHES(state, { selectedParent, dispatch, page, loading }: FetchDishesMutationParams): Promise<void> {
            if (!state.isFetching) {
                await dispatch('sidebar/scrollToChildCategory', null, {
                    root: true,
                })
            }

            state.isFetching = true

            let url = '/wp-json/wp/v2/sho-menu-dishes'
                + `?per_page=${MAX_DISHES_PER_PAGE}`
                + `&page=${page}`
                + `&sho-menu-dish-category=${selectedParent.id}`
                + `&_fields=${dishFields.join(',')}`

            state.loading = loading

            try {
                const resp = await axios.get<Dish[] | NoDishesResponse>(url)
                const dishes = resp.data

                if ('message' in dishes || dishes.length === 0) {
                    return
                }

                if (loading) {
                    state.dishes = dishes
                } else {
                    state.dishes.push(...dishes)
                }

                await dispatch('sidebar/attachDishesToChildCategories', dishes, {
                    root: true,
                })

                await dispatch('selectNeedingDish')

                if (dishes.length === MAX_DISHES_PER_PAGE) {
                    await dispatch('fetchDishes', {
                        page: page + 1,
                        loading: false,
                     })
                } else {
                    // there are no more dishes to fetch
                    setTimeout(async () => {
                        await dispatch('sidebar/observeCategories', null, { root: true })
                        state.isFetching = false
                    }, 500)
            }
            } catch (err) {
                console.error(err)
                state.isFetching = false
            }

            state.loading = false
        },
    },

    actions: {
        fetchDishes({ commit, rootGetters, dispatch }, { page, loading }: FetchDishesActionParams): void {
            const selectedParent = rootGetters['sidebar/selectedParent']
            commit('FETCH_DISHES', { selectedParent, dispatch, page, loading })
        },

        selectDish({ state }, dish: Dish): void {
            state.selectedDish = dish
            addParamToUrl('dish', dish.id.toString())
        },

        clearSelectedDish({ state }): void {
            state.selectedDish = null
            removeParamToUrl('dish')
        },

        selectNeedingDish({ state, dispatch }): void {
            const dishFromUrl = getDishFromUrl(state.dishes)

            if (dishFromUrl) {
                dispatch('selectDish', dishFromUrl)
            }
        },

        changeLoading({ state }, loading: boolean): void {
            state.loading = loading
        },
    },
}

export default dishes
