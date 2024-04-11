<script setup lang="ts">
import ChevronLeftIcon from '@/components/Icons/ChevronLeftIcon.vue'
import { useStore } from 'vuex'

const store = useStore()

function showSidebar(): void {
    store.dispatch('sidebar/resetSidebarCategories')
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