<script setup lang="ts">
import TopHeader from '@menu/components/TopHeader.vue'
import Sidebar from '@menu/components/Sidebar/Sidebar.vue'
import Dishes from '@menu/components/Dishes/Dishes.vue'
import DishPopup from '@menu/components/DishPopup.vue'
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