import type { Dish, Category } from '@/types'

export default (dishes: Dish[], categories: Category[]): Category[] => {
    const result: Category[] = []

    for (const category of categories) {
        const matchedDishes = dishes.filter(dish => dish['sho-menu-dish-category'].includes(category.id))

        if (matchedDishes.length === 0) {
            continue
        }

        category.dishes = matchedDishes

        result.push(category)
    }

    return result
}