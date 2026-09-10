<script setup lang="ts">
import type { Dish } from '@/types'
import { ref, onMounted } from 'vue'
import { useStore } from 'vuex'
import Toggle from '@/components/Toggle.vue'

const store = useStore()

type Props = {
    dish: Dish
}

const props = defineProps<Props>()
const isAvailable = ref(false)

onMounted(() => setInitialValue())

function setInitialValue(): void {
    isAvailable.value = !props.dish.designations.some(item => item.slug === 'ended')
}

function toggleAvailability(newVal: boolean): void {
    isAvailable.value = newVal
    store.dispatch('dishes/toggleAvailability', props.dish.id)
}
</script>

<template>
    <div class="sho-menu__toggle">
        <toggle
            @updated="toggleAvailability"
            :available="isAvailable"
            :id="`availability-toggle-switch-${dish.id}`"
        />
    </div>
</template>

