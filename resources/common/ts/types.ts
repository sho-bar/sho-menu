export type Globals = {
    ajaxUrl: string
    nonce: string
    mainUrl: string
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
}