import type { Category } from '@/types'
import getParamFromUrl from '@/modules/getParamFromUrl'

export default (categories: Category[]): Category | null => {
    const parent = getParamFromUrl('parent')

    if (!parent) {
        return null
    }

    const parentId = parseInt(parent)
    const selectedParent = categories.find(c => c.id === parentId)

    if (!selectedParent) {
        return null
    }

    return selectedParent
}