<script setup lang="ts">
import type { Category } from '@/types'
import { ref, computed } from 'vue'
import DishItem from '@menu/components/Dishes/DishItem.vue'
import ShowSidebarBtn from '@menu/components/Sidebar/ShowSidebarBtn.vue'
import CategoryItem from '@menu/components/Dishes/CategoryItem.vue'
import { useStore } from 'vuex'


const store = useStore()
const childCategories = computed<Category[]>(() => store.getters['sidebar/childCategories'])
const selectedParent = computed<Category | null>(() => store.getters['sidebar/selectedParent'])
const loading = ref<boolean>(true)
</script>

<template>
    <div
        class="sho-menu__dishes"
        :class="{ 'sho-menu__dishes--show': selectedParent !== null }"
    >
        <show-sidebar-btn />

        <div v-if="loading">Завантаження...</div>
        <div v-else="categories.length === 0">Позицій у цій категорії ще немає</div>
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