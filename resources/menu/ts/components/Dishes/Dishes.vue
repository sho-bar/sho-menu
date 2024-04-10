<script setup lang="ts">
import { onMounted } from 'vue'
import axios from 'axios'

onMounted(() => fetchDishes())

function fetchDishes(): void {
    //
}
</script>

<template>
    <div class="sho-menu__dishes">
        Dishes
    </div>
</template>