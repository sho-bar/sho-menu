<template>
    <div class="sho-menu__dishes__loading">
        <div
            v-for="i in 15"
            :key="i"
            class="sho-menu__dishes__loading__item"
        >
            <div class="sho-menu__dishes__loading__item__content">
                <h3></h3>
                <div class="sho-menu__dishes__loading__item__price"></div>

                <div class="sho-menu__dishes__loading__item__description"></div>
                <div class="sho-menu__dishes__loading__item__description"></div>
                <div class="sho-menu__dishes__loading__item__description"></div>
                <div class="sho-menu__dishes__loading__item__description"></div>
                <div class="sho-menu__dishes__loading__item__description"></div>

                <div class="sho-menu__dishes__loading__item__weight"></div>
            </div>

            <div class="sho-menu__dishes__loading__item__image"></div>
        </div>
    </div>
</template>