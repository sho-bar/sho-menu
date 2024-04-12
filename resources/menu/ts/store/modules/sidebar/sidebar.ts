import type { Category, Dish } from '@/types'
import type SidebarState from './SidebarState'
import type RootState from '@menu/store/RootState'
import type { Dispatch } from 'vuex'
import { Module } from 'vuex'
import axios from 'axios'
import screenSizeIs from '@/modules/screenSizeIs'
import addParamToUrl from '@/modules/addParamToUrl'
import getCategoryFromUrl from '@/modules/getCategoryFromUrl'

const sidebar: Module<SidebarState, RootState> = {
    namespaced: true,

    state: {
        allCategories: [],
        parentCategories: [],
        childCategories: [],
        selectedParent: null,
        selectedChild: null,
        loading: false,
    },

    getters: {
        selectedParent: s => s.selectedParent,
        selectedChild: s => s.selectedChild,
        childCategories: s => s.childCategories,
        parentCategories: s => s.parentCategories,
    },

    mutations: {
        FETCH_CATEGORIES(state, dispatch: Dispatch): void {
            let url = '/wp-json/wp/v2/sho-menu-dish-category'
                + '?_fields=id,slug,name,parent,description'

            state.loading = true

            axios.get<Category[]>(url)
                .then(resp => {
                    state.allCategories = resp.data
                    state.parentCategories = resp.data.filter(c => c.parent === 0)

                    dispatch('sidebar/selectNeedingParentCategory', null, {
                        root: true,
                    })
                })
                .catch(err => console.error(err))
                .finally(() => state.loading = false)
        },
    },

    actions: {
        fetchCategories({ commit, dispatch }): void {
            commit('FETCH_CATEGORIES', dispatch)
        },

        selectParentCategory({ state, dispatch }, category: Category): void {
            state.childCategories = state.allCategories.filter(c => c.parent === category.id)
            state.selectedParent = category

            addParamToUrl('parent', category.id.toString())

            dispatch('dishes/fetchDishes', null, { root: true })
        },

        selectChildCategory({ state, dispatch }, category: Category): void {
            state.selectedChild = category
            dispatch('scrollToChildCategory', category.id)
        },

        selectNeedingParentCategory({ state, dispatch }): void {
            if (!screenSizeIs(811)) {
                return
            }

            const categoryFromUrl = getCategoryFromUrl(state.parentCategories)

            if (categoryFromUrl) {
                dispatch('selectParentCategory', categoryFromUrl)
                return
            }

            dispatch('selectFirstParentCategory')
        },

        selectFirstParentCategory({ state, dispatch }): void {
            if (state.parentCategories.length) {
                dispatch('selectParentCategory', state.parentCategories[0])
            }
        },

        resetSidebarCategories({ state }): void {
            state.selectedParent = null
            state.selectedChild = null
            state.childCategories = []
        },

        attachDishesToChildCategories({ state }, dishes: Dish[]): void {
            for (const category of state.childCategories) {
                const matchedDishes = dishes.filter(dish => dish['sho-menu-dish-category'].includes(category.id))

                if (matchedDishes.length === 0) {
                    continue
                }

                category.dishes = matchedDishes
            }
        },

        scrollToChildCategory({ state }, categoryId: number): void {
            const elem = document.getElementById(`sho-menu-category-${categoryId}`)

            if (!elem) {
                return
            }

            elem.scrollIntoView({ behavior: 'smooth', block: 'start' })
        },
    },
}

export default sidebar