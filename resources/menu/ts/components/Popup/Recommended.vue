<script setup lang="ts">
import type { Dish, NoDishesResponse } from '@/types'
import { onMounted, ref } from 'vue'
import axios from 'axios'
import dishFields from '@menu/modules/dishFields'
import RecommendedItem from '@menu/components/Popup/RecommendedItem.vue'

type Props = {
    dish: Dish
}

const { dish } = defineProps<Props>()
const loading = ref<boolean>(true)
const dishes = ref<Dish[]>([])

onMounted(() => {
    fetchDishes()
})

function fetchDishes(): void {
    if (dish.recommended_dishes.length === 0) {
        return
    }

    loading.value = true

    const ids = dish.recommended_dishes.map(d => d.id)

    let url = '/wp-json/wp/v2/sho-menu-dishes'
        + `?per_page=100`
        + `&_fields=${dishFields.join(',')}`
        + `&include=${ids.join(',')}`

    axios.get<Dish[] | NoDishesResponse>(url)
        .then(resp => handleResponse(resp.data))
        .catch(err => console.error(err))
        .finally(() => loading.value = false)
}

function handleResponse(resp: Dish[] | NoDishesResponse): void {
    if (Array.isArray(resp)) {
        dishes.value = resp
        return
    }

    console.error(resp.message)
}
</script>

<template>
    <div class="sho-menu-recommended">
        <h2 class="sho-menu-recommended__title">
            Рекомендуємо до страви
        </h2>

        <ul>
            <RecommendedItem
                v-for="d in dishes"
                :key="d.id"
                :dish="d"
            >
            </RecommendedItem>
        </ul>
    </div>
</template>