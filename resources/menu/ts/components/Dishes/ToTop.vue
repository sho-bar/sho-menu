<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'

const showButton = ref(false)

function onScroll() {
    showButton.value = window.scrollY > 600
}

onMounted(() => {
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll)
})

function scrollToTop(): void {
    const target = document.querySelector('.sho-menu__header')

    if (!target) {
        console.error('.sho-menu__header class not found')
        return
    }

    target.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
    <button type="button" @click="scrollToTop" :class="{ 'show-button': showButton }">
        На початок
    </button>
</template>

<style lang="sass" scoped>
button
    cursor: pointer
    position: fixed
    z-index: 10
    left: 50%
    transform: translateX(-50%)
    border: none
    border-radius: 7px
    padding: 5px 13px
    text-transform: uppercase
    color: white
    font-weight: bold
    background-color: gray
    top: 60px
    opacity: 0
    transition: opacity 300ms

    &.show-button
        opacity: .95 !important
</style>
