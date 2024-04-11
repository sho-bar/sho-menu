import type SidebarState from './modules/sidebar/SidebarState'
import type DishesState from './modules/dishes/DishesState'

export default interface RootState {
    sidebar: SidebarState
    dishes: DishesState
}