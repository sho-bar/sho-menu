<script setup lang="ts">
import type { Dish } from '@/types'
import { ref, onMounted, watch } from 'vue'
import Toggle from '@/components/Toggle.vue'

type Props = {
    dish: Dish
}

const props = defineProps<Props>()
const isAvailable = ref(false)

watch(isAvailable, toggleAvailability)

onMounted(() => setInitialValue())

function setInitialValue(): void {
    isAvailable.value = !props.dish.designations.some(item => item.slug === 'ended')
}

function toggleAvailability(): void {
    console.log(isAvailable.value, props.dish.designations)
}
</script>

<template>
    <div class="sho-menu__toggle">
        <toggle v-model="isAvailable" />
    </div>
</template>

