import type { Category, Dish, FetchDishesActionParams } from '@/types'
import type SidebarState from './SidebarState'
import type RootState from '@menu/store/RootState'
import type { Dispatch } from 'vuex'
import { Module } from 'vuex'
import axios from 'axios'
import addParamToUrl from '@/modules/addParamToUrl'
import getCategoryFromUrl from '@menu/modules/getCategoryFromUrl'
import removeParamToUrl from '@/modules/removeParamToUrl'
import screenSizeIs from '@/modules/screenSizeIs'
import observeCategories from '@menu/modules/observeCategories'
import scrollToCategory from '@menu/modules/scrollToCategory'

const MAX_CATEGORIES_PER_PAGE = 100

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
                + `?per_page=${MAX_CATEGORIES_PER_PAGE}`
                + '&page=1'
                + '&_fields=id,slug,name,parent,description'

            state.loading = true

            axios.get<Category[]>(url)
                .then(resp => {
                    if (resp.data.length === 0) {
                        dispatch('dishes/changeLoading', null, { root: true })
                        return
                    }

                    state.allCategories = resp.data
                    state.parentCategories = resp.data.filter(c => c.parent === 0)

                    dispatch('selectNeedingParentCategory')
                    dispatch('selectNeedingChildCategory')
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

            removeParamToUrl('child')
            addParamToUrl('parent', category.slug)

            const args: FetchDishesActionParams = {
                page: 1,
                loading: true
            }

            dispatch('dishes/fetchDishes', args, { root: true })
        },

        selectChildCategory({ state, dispatch }, category: Category): void {
            state.selectedChild = category

            addParamToUrl('child', category.slug)

            dispatch('scrollToChildCategory', category.id)
        },

        observeCategories({ state }): void {
            observeCategories((id: number) => {
                const category = state.childCategories.find(c => c.id === id)

                if (!category) {
                    console.warn(`Category with id ${id} not found in state.childCategories`)
                    return
                }

                state.selectedChild = category
                addParamToUrl('child', category.slug)
                scrollToCategory(id)
            })
        },

        selectNeedingParentCategory({ state, dispatch }): void {
            const categoryFromUrl = getCategoryFromUrl('parent', state.parentCategories)

            if (categoryFromUrl) {
                dispatch('selectParentCategory', categoryFromUrl)
                return
            }

            if (screenSizeIs(811)) {
                dispatch('selectFirstParentCategory')
            }
        },

        selectNeedingChildCategory({ state, dispatch }): void {
            const categoryFromUrl = getCategoryFromUrl('child', state.childCategories)

            if (categoryFromUrl) {
                // This is a workaround, categories are not loaded into the DOM yet
                setTimeout(() => dispatch('selectChildCategory', categoryFromUrl), 300)
                return
            }
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
            removeParamToUrl('parent', 'child', 'dish')
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