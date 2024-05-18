<script setup lang="ts">
import type { Dish } from '@/types'
import DishWeight from '@menu/components/DishWeight.vue'

type Props = {
    dish: Dish
}

const props = defineProps<Props>()
</script>

<template>
    <div class="sho-menu__dish-popup__meta">
        <span v-if="props.dish.price" class="sho-menu__dish-popup__price">
            {{ props.dish.price }} грн
        </span>

        <small
            v-if="props.dish.weight !== '0' && props.dish.weight !== ''"
            class="sho-menu__dish-popup__weight"
        >
            <dish-weight :dish="props.dish" />
        </small>
    </div>
</template>