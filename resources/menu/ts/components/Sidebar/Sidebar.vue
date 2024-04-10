<script setup lang="ts">
import type { Category } from '@/types'
import { onMounted, ref } from 'vue'
import { events } from '@menu/config'
import dispatchEvent from '@/modules/dispatchEvent'
import axios from 'axios'

const loading = ref<boolean>(false)
const allCategories = ref<Category[]>([])
const categories = ref<Category[]>([])
const selectedParentCategory = ref<number | null>(null)
const selectedChildCategory = ref<number | null>(null)
const children = ref<Category[]>([])

onMounted(() => fetchCategories())

function fetchCategories(): void {
    let url = '/wp-json/wp/v2/sho-menu-dish-category'
    url += '?_fields=id,slug,name,parent'

    axios.get<Category[]>(url)
        .then(resp => {
            allCategories.value = resp.data
            categories.value = resp.data.filter(c => c.parent === 0)
        })
        .catch(err => console.error(err))
        .finally(() => loading.value = false)
}

function selectParentCategory(category: Category): void {
    selectedParentCategory.value = category.id
    displayChildren(category.id)
    dispatchEvent(events.parentCategoryIsSelected, category)
}

function selectChildCategory(category: Category): void {
    selectedChildCategory.value = category.id
    dispatchEvent(events.childCategoryIsSelected, category)
}

function displayChildren(id: number): void {
    children.value = allCategories.value.filter(c => c.parent === id)
}
</script>

<template>
    <div class="sho-menu__sidebar">
        <small class="sho-menu__sidebar__label is-selected">Меню:</small>

        <ul>
            <li
                v-for="c in categories"
                :key="c.id"
                @click.self="selectParentCategory(c)"
                :class="{ 'is-selected': c.id === selectedParentCategory }"
            >
                {{ c.name }}

                <ul v-if="children.length > 0 && selectedParentCategory == c.id">
                    <li
                        v-for="child in children"
                        :key="child.id"
                        @click="selectChildCategory(child)"
                        :class="{ 'is-selected': child.id === selectedChildCategory }"
                    >
                        {{ child.name }}
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>