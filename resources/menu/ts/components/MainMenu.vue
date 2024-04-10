<script setup lang="ts">
import TopHeader from '@menu/components/TopHeader.vue'
import Sidebar from '@menu/components/Sidebar/Sidebar.vue'
import Dishes from '@menu/components/Dishes/Dishes.vue'
</script>

<template>
    <div class="sho-menu">
        <top-header />

        <div class="sho-menu__columns">
            <sidebar />
            <dishes />
        </div>
    </div>
</template>