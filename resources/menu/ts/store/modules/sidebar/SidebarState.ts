import type { Category } from '@/types'

export default interface SidebarState {
    allCategories: Category[]
    parentCategories: Category[]
    childCategories: Category[]
    loading: boolean
    selectedParent: Category | null
    selectedChild: Category | null
}