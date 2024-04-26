<script setup lang="ts">
import type { Dish } from '@/types'
import { useStore } from 'vuex'
import WeightIcon from '@/components/Icons/WeightIcon.vue'
import VolumeIcon from '@/components/Icons/VolumeIcon.vue'
import EditButton from '@menu/components/Dishes/EditButton.vue'
import Designations from '@menu/components/Dishes/Designations.vue'

type Props = {
    dish: Dish
}

const { dish } = defineProps<Props>()

const store = useStore()

function selectDish(): void {
    store.dispatch('dishes/selectDish', dish)
}

function isDrink(): boolean {
    return dish.weight_unit !== null && ['ml', 'мл', 'ml.', 'мл.'].includes(dish.weight_unit)
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
                <volume-icon v-if="isDrink()" width="16" height="16" />
                <weight-icon v-else width="16" height="16" />
                {{ dish.weight }}
                {{ dish.weight_unit }}
            </small>

            <designations :dish="dish" />
        </div>

        <div v-if="dish.image_url" class="sho-menu__dishes__item__image">
            <img
                v-if="dish.image_url"
                :src="dish.image_url"
                :alt="dish.title.rendered"
            />
        </div>
    </div>
</template>