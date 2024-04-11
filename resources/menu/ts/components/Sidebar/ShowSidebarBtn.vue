<script setup lang="ts">
import ChevronLeftIcon from '@/components/Icons/ChevronLeftIcon.vue'
import dispatchEvent from '@/modules/dispatchEvent'
import { events } from '@menu/config'

function showSidebar(): void {
    dispatchEvent(events.showMobileSidebar)
}
</script>

<template>
    <button
        @click="showSidebar"
        type="button"
        class="sho-menu__sidebar__btn"
    >
        <chevron-left-icon width="24" height="24" />
        <span>Назад</span>
    </button>
</template>