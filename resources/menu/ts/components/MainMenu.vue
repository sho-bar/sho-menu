<script setup lang="ts">
import { onMounted } from 'vue'
import { useStore } from 'vuex'
import TopHeader from '@menu/components/TopHeader.vue'
import Sidebar from '@menu/components/Sidebar/Sidebar.vue'
import Dishes from '@menu/components/Dishes/Dishes.vue'
import DishPopup from '@menu/components/Popup/DishPopup.vue'

const store = useStore()

onMounted(() => {
    window.addEventListener('keydown', (event: KeyboardEvent) => {
        if (event.key === 'Escape') {
            store.dispatch('dishes/clearSelectedDish')
        }
    })
})
</script>

<template>
    <dish-popup />

    <div class="sho-menu">
        <top-header />

        <div class="sho-menu__columns">
            <sidebar />
            <dishes />
        </div>
    </div>
</template>