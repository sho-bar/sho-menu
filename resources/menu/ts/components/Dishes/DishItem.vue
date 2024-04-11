<script setup lang="ts">
import type { Dish } from '@/types'

type Props = {
    dish: Dish
}

const { dish } = defineProps<Props>()
</script>

<template>
    <div class="sho-menu__dishes__item">
        <h4>{{ dish.title.rendered }}</h4>
        <div v-html="dish.content.rendered"></div>
        <div>{{ dish.price }} грн</div>
        <div>{{ dish.weight }} г</div>
    </div>
</template>