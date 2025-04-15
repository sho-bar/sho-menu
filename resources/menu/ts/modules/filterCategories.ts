import { Category } from "@/types";

const HIDE_KEYWORD = 'hide'

export default function filterCategories(categories: Category[]): Category[] {
    // Hide categories that contain the word `hide` in the description
    // Check first 4 characters
    return categories.filter(c => {
        const desc = c.description.toLowerCase().slice(0, 4)
        return desc !== HIDE_KEYWORD
    })
}
