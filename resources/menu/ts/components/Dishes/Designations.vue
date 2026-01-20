<script setup lang="ts">
import { computed } from 'vue'
import type { Dish } from '@/types'

const props = defineProps<{ dish: Dish }>()

const designations = computed(() => {
    // Make 'blackout' designation last in the list
    return props.dish.designations
        .sort((a, b) => {
            if (a.slug === 'blackout') return 1
            if (b.slug === 'blackout') return -1
            return 0
        })
})
</script>

<template>
    <div v-if="designations.length > 0" class="sho-menu__designations">
        <div
            v-for="des in designations"
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
