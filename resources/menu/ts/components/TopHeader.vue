<script setup lang="ts">
import ShowSidebarBtn from '@menu/components/Sidebar/ShowSidebarBtn.vue'
</script>

<template>
    <div class="sho-menu__header">
        <img :src="shoMenuGlobals.mainUrl + 'assets/img/menu-header.webp'" />
        <show-sidebar-btn />
    </div>
</template>