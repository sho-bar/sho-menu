<script setup lang="ts">
import type { Category, Dish } from '@/types'
import type { ParentCategoryIsSelectedEventData } from '@menu/types'
import { onMounted, ref } from 'vue'
import { events } from '@menu/config'
import attachDishesToCategories from '@menu/modules/attachDishesToCategories'
import DishItem from '@menu/components/Dishes/DishItem.vue'
import ShowSidebarBtn from '@menu/components/Sidebar/ShowSidebarBtn.vue'
import CategoryItem from '@menu/components/Dishes/CategoryItem.vue'
import listenEvent from '@/modules/listenEvent'
import axios from 'axios'

const categories = ref<Category[]>([])
const loading = ref<boolean>(true)
const selectedCategory = ref<Category | null>(null)

onMounted(() => {
    listenEvent<ParentCategoryIsSelectedEventData>(
        events.parentCategoryIsSelected,
        fetchDishes,
    )
})

function fetchDishes(data: ParentCategoryIsSelectedEventData): void {
    selectedCategory.value = data.parentCategory

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

    let url = '/wp-json/wp/v2/sho-menu-dishes'
        + '?per_page=100'
        + '&page=1'
        + `&sho-menu-dish-category=${data.parentCategory.id}`
        + `&_fields=${selectFields.join(',')}`

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
    <div
        class="sho-menu__dishes"
        :class="{ 'sho-menu__dishes--show': selectedCategory !== null }"
    >
        <show-sidebar-btn />

        <div v-if="loading">Завантаження...</div>
        <div v-else="categories.length === 0">Позицій у цій категорії ще немає</div>
        <div v-else>
            <div v-for="category in categories" :key="category.id">
                <div
                    v-if="category.dishes && category.dishes.length > 0"
                    class="sho-menu__dishes__section"
                >
                    <category-item :category="category" />

                    <dish-item
                        v-for="dish in category.dishes"
                        :key="dish.id"
                        :dish="dish"
                    />
                </div>
            </div>
        </div>
    </div>
</template>