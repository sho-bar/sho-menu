<script setup lang="ts">
import type { Category } from '@/types'
import { computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const childCategories = computed<Category[]>(() => store.getters['sidebar/childCategories'])
const selectedChild = computed<Category | null>(() => store.getters['sidebar/selectedChild'])

function selectChildCategory(category: Category): void {
    store.dispatch('sidebar/selectChildCategory', category)
}
</script>

<template>
    <div class="sho-menu__categories-bar">
        <div
            v-for="category in childCategories"
            :key="category.id"
            class="sho-menu__categories-bar__item"
            :class="{ 'sho-menu__categories-bar__item--is-selected': selectedChild && category.id === selectedChild.id }"
            @click="selectChildCategory(category)"
        >
            {{ category.name }}
        </div>
    </div>
</template>