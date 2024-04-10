<script setup lang="ts">
import type { Category, Dish } from '@/types'
import { onMounted, ref } from 'vue'
import { events } from '@menu/config'
import listenEvent from '@/modules/listenEvent'
import axios from 'axios'

const dishes = ref<Dish[]>([])
const loading = ref<boolean>(false)

onMounted(() => {
    // todo: fetch the first category
    // fetchDishes()

    listenEvent(events.parentCategoryIsSelected, fetchDishes)
})

function fetchDishes(category: Category): void {
    let url = '/wp-json/wp/v2/sho-menu-dishes'
        + '?per_page=100'
        + '&page=1'
        + `&sho-menu-dish-category=${category.id}`
        + '&_fields=id,slug,title.rendered,content.rendered,price,weight'

    loading.value = true

    axios.get<Dish[]>(url)
        .then(resp => dishes.value = resp.data)
        .catch(err => console.error(err))
        .finally(() => loading.value = false)
}
</script>

<template>
    <div class="sho-menu__dishes">
        <div
            v-for="dish in dishes"
            :key="dish.id"
        >
            <h3>{{ dish.title.rendered }}</h3>
        </div>
    </div>
</template>