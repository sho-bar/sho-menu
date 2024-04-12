import type { Dish } from '@/types'

export default interface DishesState {
    loading: boolean
    selectedDish: Dish | null
}