<script setup lang="ts">
import type { Category, Dish } from '@/types'
import { computed } from 'vue'
import { useStore } from 'vuex'
import DishItem from '@menu/components/Dishes/DishItem.vue'
import CategoryItem from '@menu/components/Dishes/CategoryItem.vue'
import CategoriesBar from '@menu/components/Dishes/CategoriesBar.vue'
import DishesLoading from '@/components/DishesLoading.vue'

const store = useStore()
const childCategories = computed<Category[]>(() => store.getters['sidebar/childCategories'])
const selectedParent = computed<Category | null>(() => store.getters['sidebar/selectedParent'])
const dishes = computed<Dish[]>(() => store.getters['dishes/dishes'])
const loading = computed<boolean>(() => store.getters['dishes/loading'])
</script>

<template>
    <div
        class="sho-menu__dishes"
        :class="{ 'sho-menu__dishes--show': selectedParent !== null }"
    >
        <categories-bar />

        <dishes-loading v-if="loading" />

        <div
            v-else-if="childCategories.length === 0"
            class="sho-menu__dishes__empty"
        >
            Позицій у цій категорії ще немає
        </div>

        <div v-else>
            <div v-for="category in childCategories" :key="category.id">
                <div class="sho-menu__dishes__section">
                    <category-item :category="category" />

                    <div v-if="category.dishes && category.dishes.length > 0">
                        <dish-item
                            v-for="dish in category.dishes"
                            :key="dish.id"
                            :dish="dish"
                        />
                    </div>

                    <div v-else class="sho-menu__dishes__empty">
                        Позицій у цій категорії ще немає
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>