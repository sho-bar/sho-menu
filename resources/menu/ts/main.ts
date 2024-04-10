import { createApp } from 'vue'
import MainMenu from '@menu/components/MainMenu.vue'

const app = createApp({
    components: {
        MainMenu,
    },
})

app.config.globalProperties.shoMenuGlobals = window.shoMenuGlobals

app.mount("#sho-menu")