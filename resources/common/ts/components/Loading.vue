<template>
    <div class="sho-menu-loading-wrapper">
        <div class="sho-menu-loading-inner">
            <div class="sho-menu-loading__line"></div>
            <div class="sho-menu-loading__line"></div>
            <div class="sho-menu-loading__line"></div>
        </div>

        <span>Завантаження...</span>
    </div>
</template>

<style scoped>
.sho-menu-loading-wrapper {
    text-align: center;
    margin: 30px 0;
}

.sho-menu-loading-wrapper span {
    display: block;
    margin-top: 18px;
}

.sho-menu-loading__line {
    display: inline-block;
    width: 15px;
    height: 15px;
    margin-right: 5px;
    border-radius: 15px;
    background-color: #556ee6;
}

.sho-menu-loading__line:last-child {
    margin-right: 0;
}

.sho-menu-loading-inner .sho-menu-loading__line:nth-last-child(1) {
    animation: sho-menu-loading-animation 0.6s 0.1s linear infinite;
}

.sho-menu-loading-inner .sho-menu-loading__line:nth-last-child(2) {
    animation: sho-menu-loading-animation 0.6s 0.2s linear infinite;
}

.sho-menu-loading-inner .sho-menu-loading__line:nth-last-child(3) {
    animation: sho-menu-loading-animation 0.6s 0.3s linear infinite;
}

@keyframes sho-menu-loading-animation {
    0% {
        transform: translate(0, 0);
    }

    50% {
        transform: translate(0, 15px);
    }

    100% {
        transform: translate(0, 0);
    }
}
</style>