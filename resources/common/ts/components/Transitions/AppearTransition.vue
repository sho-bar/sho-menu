<template>
    <transition name="sho-menu-appear-transition" appear>
        <slot></slot>
    </transition>
</template>

<style scoped>
.sho-menu-appear-transition-enter-active,
.sho-menu-appear-transition-leave-active {
    transition: opacity .2s;
}
.sho-menu-appear-transition-enter-from,
.sho-menu-appear-transition-leave-to {
    opacity: 0;
}
</style>