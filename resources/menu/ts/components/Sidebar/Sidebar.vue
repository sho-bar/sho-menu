<script setup lang="ts">
import type { Category } from '@/types'
import { onMounted, computed } from 'vue'
import { useStore } from 'vuex'
import ChevronRightIcon from '@/components/Icons/ChevronRightIcon.vue'

const store = useStore()
const parentCategories = computed<Category[]>(() => store.getters['sidebar/parentCategories'])
const selectedParent = computed<Category | null>(() => store.getters['sidebar/selectedParent'])
const selectedChild = computed<Category | null>(() => store.getters['sidebar/selectedChild'])
const childCategories = computed<Category[]>(() => store.getters['sidebar/childCategories'])

onMounted(() => {
    store.dispatch('sidebar/fetchCategories')
})

function selectParentCategory(category: Category): void {
    store.dispatch('sidebar/selectParentCategory', category)
}

function selectChildCategory(category: Category): void {
    store.dispatch('sidebar/selectChildCategory', category)
}
</script>

<template>
    <div
        class="sho-menu__sidebar"
        :class="{ 'sho-menu__sidebar--hide': selectedParent !== null }"
    >
        <small class="sho-menu__sidebar__label">Меню:</small>

        <ul>
            <li
                v-for="c in parentCategories"
                :key="c.id"
                @click.self="selectParentCategory(c)"
                :class="{ 'is-selected': selectedParent && c.id === selectedParent.id }"
            >
                {{ c.name }}

                <chevron-right-icon width="22" height="22" />

                <ul v-if="childCategories.length > 0 && selectedParent && selectedParent.id == c.id">
                    <li
                        v-for="child in childCategories"
                        :key="child.id"
                        @click="selectChildCategory(child)"
                        :class="{ 'is-selected': selectedChild && child.id === selectedChild.id }"
                    >
                        {{ child.name }}
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>