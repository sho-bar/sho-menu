import type { Dispatch } from "vuex"

export type Globals = {
    ajaxUrl: string
    nonce: string
    mainUrl: string
    isAuth: "1" | "0"
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
    content: { rendered: string; protected: boolean }
    price: number | null
    weight: string | null
    weight_unit: string | null
    "sho-menu-dish-category": number[]
    image_url: string | null
    image_url_lg: string | null
    designations: Designation[]
    recommended_dishes: RecommendedDish[]
}

export type RecommendedDish = {
    id: number
    title: string
    slug: string
    image_url: string | null
}

export type SearchResultItem = {
    id: number
    title: string
    url: string
    type: string
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