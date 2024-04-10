<script setup lang="ts">
import type { Category, Dish } from '@/types'
import type { ParentCategoryIsSelectedEventData } from '@menu/types'
import { onMounted, ref } from 'vue'
import { events } from '@menu/config'
import attachDishesToCategories from '@menu/modules/attachDishesToCategories'
import listenEvent from '@/modules/listenEvent'
import axios from 'axios'

const categories = ref<Category[]>([])
const loading = ref<boolean>(true)

onMounted(() => {
    listenEvent<ParentCategoryIsSelectedEventData>(
        events.parentCategoryIsSelected,
        fetchDishes,
    )
})

function fetchDishes(data: ParentCategoryIsSelectedEventData): void {
    let url = '/wp-json/wp/v2/sho-menu-dishes'
        + '?per_page=100'
        + '&page=1'
        + `&sho-menu-dish-category=${data.parentCategory.id}`
        + '&_fields=id,slug,title.rendered,content.rendered,price,weight,sho-menu-dish-category'

    loading.value = true

    axios.get<Dish[]>(url)
        .then(resp => {
            categories.value = attachDishesToCategories(resp.data, data.childCategories)
        })
        .catch(err => console.error(err))
        .finally(() => loading.value = false)
}
</script>

<template>
    <div v-if="loading">Завантаження...</div>
    <div v-else="dishes.length === 0">Позицій у цій категорії ще немає</div>
    <div v-else class="sho-menu__dishes">
        <div
            v-for="category in categories"
            :key="category.id"
        >
            <h3>{{ category.name }}</h3>

            <div
                v-for="dish in category.dishes"
                :key="dish.id"
            >
                <h4>{{ dish.title.rendered }}</h4>
                <div v-html="dish.content.rendered"></div>
                <div>{{ dish.price }} грн</div>
                <div>{{ dish.weight }} г</div>
            </div>
        </div>
    </div>
</template>