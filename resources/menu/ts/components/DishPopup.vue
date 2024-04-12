<script setup lang="ts">
import type { Dish } from '@/types'
import { useStore } from 'vuex'
import { computed } from 'vue'
import WeightIcon from '@/components/Icons/WeightIcon.vue'
import EditButton from '@menu/components/Dishes/EditButton.vue'

const store = useStore()
const selectedDish = computed<Dish | null>(() => store.getters['dishes/selectedDish'])
</script>

<template>
    <div v-if="selectedDish" class="sho-menu__dish-popup--overlay">
        <div class="sho-menu__dish-popup">
            <edit-button :id="selectedDish.id" />

            <div class="sho-menu__dish-popup__content">
                <div class="sho-menu__dish-popup__image">
                    <img
                        v-if="selectedDish.image_url"
                        :src="selectedDish.image_url"
                        :alt="selectedDish.title.rendered"
                    />
                </div>

                <h2 v-html="selectedDish.title.rendered"></h2>

                <div class="sho-menu__dish-popup__meta">
                    <span class="sho-menu__dish-popup__price">
                        {{ selectedDish.price }} грн
                    </span>

                    <small class="sho-menu__dish-popup__weight">
                        <weight-icon width="16" height="16" />
                        {{ selectedDish.weight }}
                        {{ selectedDish.weight_unit }}
                    </small>
                </div>

                <p
                    v-html="selectedDish.content.rendered"
                    class="sho-menu__dish-popup__description"
                ></p>
            </div>
        </div>
    </div>
</template>