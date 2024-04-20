<script setup lang="ts">
import type { Category } from '@/types'

type Props = {
    category: Category
}

const { category } = defineProps<Props>()
</script>

<template>
    <div
        class="sho-menu__dishes__category"
        :id="`sho-menu-category-${category.id}`"
        :data-category-id="category.id"
    >
        <h2 v-html="category.name"></h2>
        <p v-html="category.description"></p>
    </div>
</template>