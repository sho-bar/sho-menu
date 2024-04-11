<script setup lang="ts">
import type { Dish } from '@/types'

type Props = {
    dish: Dish
}

const { dish } = defineProps<Props>()
</script>

<template>
    <div class="sho-menu__dishes__item">
        <div class="sho-menu__dishes__item__content">
            <h3 v-html="dish.title.rendered"></h3>
            <div v-html="dish.content.rendered"></div>
            <div>{{ dish.price }} грн</div>
            <div>{{ dish.weight }} г</div>
        </div>
        <div class="sho-menu__dishes__item__image">
            <img
                v-if="dish.image_url"
                :src="dish.image_url"
                :alt="dish.title.rendered"
            />
        </div>
    </div>
</template>