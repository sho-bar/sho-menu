import { createApp } from 'vue'
import MainMenu from '@menu/components/MainMenu.vue'

const app = createApp({
    components: {
        MainMenu,
    },
})

app.mount("#sho-menu")