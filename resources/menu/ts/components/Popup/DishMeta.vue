<script setup lang="ts">
import type { Dish } from '@/types'
import DishWeight from '@menu/components/DishWeight.vue'

type Props = {
    dish: Dish
}

defineProps<Props>()
</script>

<template>
    <div class="sho-menu__dish-popup__meta">
        <span v-if="dish.price && dish.price !== '0'" class="sho-menu__dish-popup__price">
            {{ dish.price }} грн
        </span>

        <small
            v-if="dish.weight !== '0' && dish.weight !== ''"
            class="sho-menu__dish-popup__weight"
        >
            <dish-weight :dish="dish" />
        </small>
    </div>
</template>