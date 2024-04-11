import type { Dish, Category } from '@/types'
import type DishesState from './DishesState'
import type RootState from '@menu/store/RootState'
import { Module } from 'vuex'
import axios from 'axios'

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

const dishes: Module<DishesState, RootState> = {
    namespaced: true,

    state: {
        loading: false,
    },

    getters: {
    },

    mutations: {
        FETCH_DISHES(state, selectedParent: Category): void {
            let url = '/wp-json/wp/v2/sho-menu-dishes'
                + '?per_page=100'
                + '&page=1'
                + `&sho-menu-dish-category=${selectedParent.id}`
                + `&_fields=${selectFields.join(',')}`

            state.loading = true

            axios.get<Dish[]>(url)
                .then(resp => {
                    // @ts-ignore
                    this.dispatch('sidebar/attachDishesToChildCategories', resp.data)
                })
                .catch(err => console.error(err))
                .finally(() => state.loading = false)
        },
    },

    actions: {
        fetchDishes({ commit, rootGetters }): void {
            const selectedParent = rootGetters['sidebar/selectedParent']
            commit('FETCH_DISHES', selectedParent)
        },
    },
}

export default dishes