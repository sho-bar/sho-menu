<script setup lang="ts">
import type { Dish } from '@/types'
import WeightIcon from '@/components/Icons/WeightIcon.vue'
import VolumeIcon from '@/components/Icons/VolumeIcon.vue'

type Props = {
    dish: Dish
}

const { dish } = defineProps<Props>()

function isDrink(): boolean {
    return dish.weight_unit !== null && ['ml', 'мл', 'ml.', 'мл.'].includes(dish.weight_unit)
}
</script>

<template>
    <volume-icon v-if="isDrink()" width="16" height="16" />
    <weight-icon v-else width="16" height="16" />
    {{ dish.weight }}
    {{ dish.weight_unit }}
</template>