<script setup lang="ts">
import Toggle from '@/components/Toggle.vue'

type Props = {
    id: number
}

const { id } = defineProps<Props>()

function toggleAvailability(elem: HTMLInputElement | null): void {
    if (!elem) {
        console.error('elem input not found for toggleAvailablity')
        return
    }

    console.log(elem.checked)
}
</script>

<template>
    <div class="sho-menu__toggle">
        <toggle
            @switched="toggleAvailability"
        />
    </div>
</template>

