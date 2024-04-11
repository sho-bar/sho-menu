import { createApp } from 'vue'
import MainMenu from '@menu/components/MainMenu.vue'
import store from '@menu/store'

const app = createApp({
    components: { MainMenu },
})

app.use(store)

app.config.globalProperties.shoMenuGlobals = window.shoMenuGlobals

app.mount("#sho-menu")