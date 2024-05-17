<script setup lang="ts">
import type { Dish } from '@/types'
import { useStore } from 'vuex'
import addParamToUrl from '@/modules/addParamToUrl'

type Props = {
    dish: Dish
}

const store = useStore()
const { dish } = defineProps<Props>()


function selectDish(): void {
    addParamToUrl('dish', dish.id.toString())
    window.location.reload()
}
</script>

<template>
    <li @click="selectDish">
        <a href="javascript:">
            <img
                v-if="dish.image_url"
                :src="dish.image_url"
                :alt="dish.title.rendered"
            />

            <span>{{ dish.title.rendered }}</span>
        </a>
    </li>
</template>