import type { Category, Dish } from '@/types'
import type SidebarState from './SidebarState'
import type RootState from '@menu/store/RootState'
import type { Dispatch } from 'vuex'
import { Module } from 'vuex'
import axios from 'axios'
import screenSizeIs from '@/modules/screenSizeIs'
import addParamToUrl from '@/modules/addParamToUrl'
import getParamFromUrl from '@/modules/getParamFromUrl'

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

        selectChildCategory({ state }, category: Category): void {
            state.selectedChild = category
        },

        selectNeedingParentCategory({ state, dispatch }): void {
            if (!screenSizeIs(811)) {
                return
            }

            const parent = getParamFromUrl('parent')

            if (parent) {
                const parentId = parseInt(parent)
                const selectedParent = state.parentCategories.find(c => c.id === parentId)

                if (selectedParent) {
                    dispatch('selectParentCategory', selectedParent)
                    return
                }
            }

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
    },
}

export default sidebar