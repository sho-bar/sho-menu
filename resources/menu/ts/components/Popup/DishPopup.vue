<script setup lang="ts">
import type { Dish } from '@/types'
import { useStore } from 'vuex'
import { computed } from 'vue'
import EditButton from '@menu/components/Dishes/EditButton.vue'
import AvailabilityButton from '@menu/components/Dishes/AvailabilityButton.vue'
import CloseButton from '@menu/components/Popup/CloseButton.vue'
import AppearTransition from '@/components/Transitions/AppearTransition.vue'
import Designations from '@menu/components/Dishes/Designations.vue'
import Recommended from '@menu/components/Popup/Recommended.vue'
import DishMeta from '@menu/components/Popup/DishMeta.vue'

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
                <div class="sho-menu__actions">
                    <edit-button :id="selectedDish.id" />
                    <availability-button :id="selectedDish.id" />
                    <close-button @click="closePopup" />
                </div>

                <div class="sho-menu__dish-popup__content">
                    <div
                        v-if="selectedDish.image_url_lg"
                        class="sho-menu__dish-popup__image"
                    >
                        <img
                            :src="selectedDish.image_url_lg + `?v=${selectedDish.modified}`"
                            :alt="selectedDish.title.rendered"
                        />
                    </div>

                    <h2 v-html="selectedDish.title.rendered"></h2>

                    <dish-meta :dish="selectedDish" />

                    <p
                        v-html="selectedDish.content.rendered"
                        class="sho-menu__dish-popup__description"
                    ></p>

                    <designations :dish="selectedDish" />

                    <recommended
                        v-if="selectedDish.recommended_dishes.length > 0"
                        :dish="selectedDish"
                    />
                </div>
            </div>
        </div>
    </appear-transition>
</template>
