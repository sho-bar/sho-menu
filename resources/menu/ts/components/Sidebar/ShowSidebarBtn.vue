<script setup lang="ts">
import type { Category } from '@/types'
import ChevronLeftIcon from '@/components/Icons/ChevronLeftIcon.vue'
import { computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const selectedParent = computed<Category | null>(() => store.getters['sidebar/selectedParent'])

function showSidebar(): void {
    store.dispatch('sidebar/resetSidebarCategories')
}
</script>

<template>
    <button
        v-if="selectedParent !== null"
        @click="showSidebar"
        type="button"
        class="sho-menu__sidebar-btn"
    >
        <chevron-left-icon width="24" height="24" />
        <span>Назад</span>
    </button>
</template>