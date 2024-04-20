import type { Dispatch } from 'vuex'

export type Globals = {
    ajaxUrl: string
    nonce: string
    mainUrl: string
    isAuth: '1' | '0'
}

export type Category = {
    id: number
    name: string
    slug: string
    parent: number
    description: string
    dishes?: Dish[]
}

export type Dish = {
    id: number
    slug: string
    title: { rendered: string }
    content: { rendered: string, protected: boolean }
    price: number | null
    weight: number | null
    weight_unit: string | null
    "sho-menu-dish-category": number[]
    image_url: string | null
    designations: Designation[]
}

export type Designation = {
    slug: string
    description: string
    icon: string
}

export type NoDishesResponse = {
    code: string
    message: string
    data: {
        status: number
    }
}

export type FetchDishesActionParams = {
    page: number
    loading: boolean
}

export type FetchDishesMutationParams = {
    selectedParent: Category
    dispatch: Dispatch
    page: number
    loading: boolean
}