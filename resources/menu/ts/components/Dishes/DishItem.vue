<script setup lang="ts">
import type { Dish } from '@/types'
import { useStore } from 'vuex'
import EditButton from '@menu/components/Dishes/EditButton.vue'
import Designations from '@menu/components/Dishes/Designations.vue'
import DishWeight from '@menu/components/DishWeight.vue'

type Props = {
    dish: Dish
    categoryId: number
}

const { dish, categoryId } = defineProps<Props>()

const store = useStore()

function selectDish(): void {
    store.dispatch('dishes/selectDish', dish)
}
</script>

<template>
    <div
        @click="selectDish"
        class="sho-menu__dishes__item"
        :data-category-id="categoryId"
    >
        <edit-button :id="dish.id" />

        <div class="sho-menu__dishes__item__content">
            <div class="sho-menu__dishes__item__heading">
                <h3 v-html="dish.title.rendered"></h3>

                <div class="sho-menu__dishes__item__heading__image">
                    <img
                        v-if="dish.image_url"
                        :src="dish.image_url + `?v=${dish.modified}`"
                        :alt="dish.title.rendered"
                    />
                </div>
            </div>

            <span v-if="dish.price && dish.price !== '0'" class="sho-menu__dishes__item__price">
                {{ dish.price }} грн
            </span>

            <p
                v-html="dish.content.rendered"
                class="sho-menu__dishes__item__description"
            ></p>

            <small
                v-if="dish.weight !== '0' && dish.weight !== ''"
                class="sho-menu__dishes__item__weight"
            >
                <dish-weight :dish />
            </small>

            <designations :dish />
        </div>

        <div v-if="dish.image_url" class="sho-menu__dishes__item__image">
            <img
                :src="dish.image_url + `?v=${dish.modified}`"
                :alt="dish.title.rendered"
            />
        </div>
    </div>
</template>
