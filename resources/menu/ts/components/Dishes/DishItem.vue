<script setup lang="ts">
import type { Dish } from '@/types'
import { useStore } from 'vuex'
import WeightIcon from '@/components/Icons/WeightIcon.vue'
import EditButton from '@menu/components/Dishes/EditButton.vue'

type Props = {
    dish: Dish
}

const { dish } = defineProps<Props>()

const store = useStore()

function selectDish(): void {
    store.dispatch('dishes/selectDish', dish)
}
</script>

<template>
    <div @click="selectDish" class="sho-menu__dishes__item">
        <edit-button :id="dish.id" />

        <div class="sho-menu__dishes__item__content">
            <h3 v-html="dish.title.rendered"></h3>

            <span class="sho-menu__dishes__item__price">
                {{ dish.price }} грн
            </span>

            <p
                v-html="dish.content.rendered"
                class="sho-menu__dishes__item__description"
            ></p>

            <small class="sho-menu__dishes__item__weight">
                <weight-icon width="16" height="16" />
                {{ dish.weight }}
                {{ dish.weight_unit }}
            </small>
        </div>

        <div class="sho-menu__dishes__item__image">
            <img
                v-if="dish.image_url"
                :src="dish.image_url"
                :alt="dish.title.rendered"
            />
        </div>
    </div>
</template>