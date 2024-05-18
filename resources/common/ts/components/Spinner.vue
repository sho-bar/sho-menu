<template>
    <div class="sho-menu__spinner">
        <div class="sho-menu__spinner__item"></div>
        <div class="sho-menu__spinner__item"></div>
        <div class="sho-menu__spinner__item"></div>
        <div class="sho-menu__spinner__item"></div>
    </div>
</template>

<style lang="sass" scoped>
.sho-menu__spinner
    display: flex
    justify-content: center
    align-items: center
    height: 100%
    width: 100%
    margin-top: 30px

    &__item
        width: 17px
        height: 17px
        background-color: gray
        border-radius: 50%
        margin: 0 5px
        animation: spin 1s infinite

@keyframes spin
    0%
        transform: scale(1)
    50%
        transform: scale(1.5)
    100%
        transform: scale(1)
</style>