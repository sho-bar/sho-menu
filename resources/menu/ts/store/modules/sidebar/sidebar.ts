import type { Category, Dish } from '@/types'
import type SidebarState from './SidebarState'
import type RootState from '@menu/store/RootState'
import { Module } from 'vuex'
import axios from 'axios'
import screenSizeIs from '@/modules/screenSizeIs'

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
        FETCH_CATEGORIES(state): void {
            let url = '/wp-json/wp/v2/sho-menu-dish-category'
                + '?_fields=id,slug,name,parent,description'

            state.loading = true

            axios.get<Category[]>(url)
                .then(resp => {
                    state.allCategories = resp.data
                    state.parentCategories = resp.data.filter(c => c.parent === 0)

                    if (state.parentCategories.length > 0 && screenSizeIs(811)) {
                        // @ts-ignore
                        this.dispatch('sidebar/selectParentCategory', state.parentCategories[0])
                    }
                })
                .catch(err => console.error(err))
                .finally(() => state.loading = false)
        },
    },

    actions: {
        fetchCategories({ commit }): void {
            commit('FETCH_CATEGORIES')
        },

        selectParentCategory({ state, dispatch }, category: Category): void {
            state.childCategories = state.allCategories.filter(c => c.parent === category.id)
            state.selectedParent = category

            dispatch('dishes/fetchDishes', null, { root: true })
        },

        selectChildCategory({ state }, category: Category): void {
            state.selectedChild = category
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
    },
}

export default sidebar