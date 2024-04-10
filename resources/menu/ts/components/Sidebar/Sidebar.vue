<script setup lang="ts">
import type { Category } from '@/types'
import { onMounted, ref } from 'vue'
import axios from 'axios'

const loading = ref<boolean>(false)
const categories = ref<Category[]>([])

onMounted(() => fetchCategories())

function fetchCategories(): void {
    let url = '/wp-json/wp/v2/sho-menu-dish-category'
    url += '?_fields=id,slug,name,parent'

    axios.get<Category[]>(url)
        .then(resp => categories.value = filterCategories(resp.data))
        .catch(err => console.error(err))
        .finally(() => loading.value = false)
}

function filterCategories(categories: Category[]): Category[] {
    return categories.filter(category => category.parent === 0)
}
</script>

<template>
    <div class="sho-menu__sidebar">
        <ul>
            <li
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.name }}
            </li>
        </ul>
    </div>
</template>