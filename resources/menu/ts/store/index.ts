import { createStore } from 'vuex'
import sidebar from '@menu/store/modules/sidebar/sidebar'
import dishes from '@menu/store/modules/dishes/dishes'

export default createStore({
    modules: {
        sidebar,
        dishes,
    }
})