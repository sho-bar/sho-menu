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
}

export type Dish = {
    id: number
    date: string
    date_gmt: string
    guid: { rendered: string }
    modified: string
    modified_gmt: string
    slug: string
    status: string
    type: string
    link: string
    title: { rendered: string }
    content: { rendered: string, protected: boolean }
    featured_media: number
    template: string
    "sho-menu-dish-category": number[]
    price: number | null
    weight: number | null
}