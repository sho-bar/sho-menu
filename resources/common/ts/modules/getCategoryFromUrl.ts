import type { Category } from '@/types'
import getParamFromUrl from '@/modules/getParamFromUrl'

export default (param: string, categories: Category[]): Category | null => {
    const parentSlug = getParamFromUrl(param)

    if (!parentSlug) {
        return null
    }

    const selectedParent = categories.find(c => c.slug === parentSlug)

    if (!selectedParent) {
        return null
    }

    return selectedParent
}