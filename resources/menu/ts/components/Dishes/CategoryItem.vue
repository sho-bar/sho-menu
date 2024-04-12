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
    >
        <h2>{{ category.name }}</h2>
        <p>{{ category.description }}</p>
    </div>
</template>