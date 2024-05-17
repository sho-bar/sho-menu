<script setup lang="ts">
import type { Dish } from '@/types'
import { useStore } from 'vuex'
import { computed } from 'vue'
import EditButton from '@menu/components/Dishes/EditButton.vue'
import CloseButton from '@menu/components/Popup/CloseButton.vue'
import AppearTransition from '@/components/Transitions/AppearTransition.vue'
import Designations from '@menu/components/Dishes/Designations.vue'
import DishWeight from '@menu/components/DishWeight.vue'
import Recommended from '@menu/components/Popup/Recommended.vue'

const store = useStore()
const selectedDish = computed<Dish | null>(() => store.getters['dishes/selectedDish'])

function closePopup(): void {
    store.dispatch('dishes/clearSelectedDish')
}
</script>

<template>
    <appear-transition>
        <div
            v-if="selectedDish"
            class="sho-menu__dish-popup--overlay"
            @click.self="closePopup"
        >
            <div class="sho-menu__dish-popup">
                <edit-button :id="selectedDish.id" />
                <close-button @click="closePopup" />

                <div class="sho-menu__dish-popup__content">
                    <div
                        v-if="selectedDish.image_url"
                        class="sho-menu__dish-popup__image"
                    >
                        <img
                            v-if="selectedDish.image_url"
                            :src="selectedDish.image_url"
                            :alt="selectedDish.title.rendered"
                        />
                    </div>

                    <h2 v-html="selectedDish.title.rendered"></h2>

                    <div class="sho-menu__dish-popup__meta">
                        <span v-if="selectedDish.price" class="sho-menu__dish-popup__price">
                            {{ selectedDish.price }} грн
                        </span>

                        <small
                            v-if="selectedDish.weight !== '0' && selectedDish.weight !== ''"
                            class="sho-menu__dish-popup__weight"
                        >
                            <dish-weight :dish="selectedDish" />
                        </small>
                    </div>

                    <p
                        v-html="selectedDish.content.rendered"
                        class="sho-menu__dish-popup__description"
                    ></p>

                    <designations :dish="selectedDish" />
                    <recommended :dish="selectedDish" />
                </div>
            </div>
        </div>
    </appear-transition>
</template>