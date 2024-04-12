import type { Dish } from '@/types'
import getParamFromUrl from '@/modules/getParamFromUrl'

export default (dishes: Dish[]): Dish | null => {
    const parentId = getParamFromUrl('dish')

    if (!parentId) {
        return null
    }

    const id = parseInt(parentId)
    const selectedParent = dishes.find(d => d.id === id)

    if (!selectedParent) {
        return null
    }

    return selectedParent
}