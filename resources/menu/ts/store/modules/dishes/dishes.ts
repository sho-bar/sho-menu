import type { Dish, Category } from '@/types'
import type { Dispatch } from 'vuex'
import type DishesState from './DishesState'
import type RootState from '@menu/store/RootState'
import axios from 'axios'
import { Module } from 'vuex'
import addParamToUrl from '@/modules/addParamToUrl'
import removeParamToUrl from '@/modules/removeParamToUrl'
import getDishFromUrl from '@menu/modules/getDishFromUrl'

const selectFields = [
    'id',
    'slug',
    'title.rendered',
    'content.rendered',
    'price',
    'weight',
    'weight_unit',
    'sho-menu-dish-category',
    'image_url',
]

type FetchDishesParams = {
    selectedParent: Category
    dispatch: Dispatch
}

const dishes: Module<DishesState, RootState> = {
    namespaced: true,

    state: {
        loading: true,
        selectedDish: null,
        dishes: [],
    },

    getters: {
        loading: s => s.loading,
        selectedDish: s => s.selectedDish,
        dishes: s => s.dishes,
    },

    mutations: {
        FETCH_DISHES(state, { selectedParent, dispatch }: FetchDishesParams): void {
            let url = '/wp-json/wp/v2/sho-menu-dishes'
                + '?per_page=100'
                + '&page=1'
                + `&sho-menu-dish-category=${selectedParent.id}`
                + `&_fields=${selectFields.join(',')}`

            state.loading = true

            axios.get<Dish[]>(url)
                .then(resp => {
                    if (resp.data.length === 0) {
                        return
                    }

                    state.dishes = resp.data

                    dispatch('sidebar/attachDishesToChildCategories', resp.data, {
                        root: true,
                    })

                    dispatch('selectNeedingDish')
                })
                .catch(err => console.error(err))
                .finally(() => state.loading = false)
        },
    },

    actions: {
        fetchDishes({ commit, rootGetters, dispatch }): void {
            const selectedParent = rootGetters['sidebar/selectedParent']
            commit('FETCH_DISHES', { selectedParent, dispatch })
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