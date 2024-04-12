<script setup lang="ts">
import type { Category } from '@/types'
import { computed } from 'vue'
import { useStore } from 'vuex'
import DishItem from '@menu/components/Dishes/DishItem.vue'
import CategoryItem from '@menu/components/Dishes/CategoryItem.vue'
import CategoriesBar from '@menu/components/Dishes/CategoriesBar.vue'
import Loading from '@/components/Loading.vue'

const store = useStore()
const childCategories = computed<Category[]>(() => store.getters['sidebar/childCategories'])
const selectedParent = computed<Category | null>(() => store.getters['sidebar/selectedParent'])
const loading = computed<boolean>(() => store.getters['dishes/loading'])
</script>

<template>
    <div
        class="sho-menu__dishes"
        :class="{ 'sho-menu__dishes--show': selectedParent !== null }"
    >
        <categories-bar />

        <loading v-if="loading" />

        <div v-else="childCategories.length === 0" class="sho-menu__dishes__empty">
            Позицій у цій категорії ще немає
        </div>

        <div v-else>
            <div v-for="category in childCategories" :key="category.id">
                <div
                    v-if="category.dishes && category.dishes.length > 0"
                    class="sho-menu__dishes__section"
                >
                    <category-item :category="category" />

                    <dish-item
                        v-for="dish in category.dishes"
                        :key="dish.id"
                        :dish="dish"
                    />
                </div>
            </div>
        </div>
    </div>
</template>