<script setup lang="ts">
import type { Dish } from '@/types'

type Props = {
    dish: Dish
}

const { dish } = defineProps<Props>()
</script>

<template>
    <div v-if="dish.designations.length > 0" class="sho-menu__designations">
        <div
            v-for="des in dish.designations"
            :key="des.slug"
            class="sho-menu__designations__item"
            :class="{ 'sho-menu__designations__item--warning': des.slug === 'ended' }"
        >
            <img
                v-if="des.icon"
                :src="des.icon"
                :alt="des.description"
                width="25"
                height="25"
            />

            <span>{{ des.description }}</span>
        </div>
    </div>
</template>
