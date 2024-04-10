<script setup lang="ts">
import TopHeader from '@menu/components/TopHeader.vue'
import Sidebar from '@menu/components/Sidebar/Sidebar.vue'
</script>

<template>
    <div class="sho-menu">
        <top-header />

        <div class="sho-menu__columns">
            <sidebar />
            <div>dishes</div>
        </div>
    </div>
</template>