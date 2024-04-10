import type { Category } from '@/types'

export type ParentCategoryIsSelectedEventData = {
    parentCategory: Category
    childCategories: Category[]
}