import type { SearchResultItem, NoDishesResponse } from '@/types'
import axios from 'axios'

const HIDE_CLASS = 'sho-recommended-dishes__dropdown--hide'
const SPINNER_HTML = `<div class="sho-recommended-dishes__loading">
    <span class="spinner is-active"></span> Загрузка...
</div>`

export default class DishSearchDropdown {
    constructor(private input: HTMLInputElement, private dropdown: HTMLElement) {
        this.hideDropdown()
    }

    public init(): void {
        this.input.addEventListener('input', this.handleInput.bind(this))
        this.input.addEventListener('focusout', this.hideDropdown.bind(this))
    }

    private handleInput(e: Event): void {
        const target = e.target as HTMLInputElement
        const inpValue = target.value

        if (inpValue.length < 2) {
            return
        }

        this.fetchMatchingDishes(inpValue)
    }

    private fetchMatchingDishes(inpValue: string): void {
        this.loading(true)

        let url = '/wp-json/wp/v2/search'
            + `?subtype=sho-menu-dishes`
            + `&search=${inpValue}`

        axios.get<SearchResultItem[] | NoDishesResponse>(url)
            .then(resp => {
                this.loading(false)
                this.renderDishes(resp.data)
            })
            .catch(err => {
                this.loading(false)
                console.error(err)
            })
    }

    private renderDishes(dishes: SearchResultItem[] | NoDishesResponse): void {
        if ('message' in dishes || dishes.length === 0) {
            this.hideDropdown()
            console.info('No dishes found')
            return
        }

        this.showDropdown()

        this.createDishesList(dishes)
    }

    private createDishesList(dishes: SearchResultItem[]): void {
        for (const dish of dishes) {
            const li = document.createElement('li')
            li.innerHTML = dish.title
            this.dropdown.appendChild(li)
        }
    }

    private loading(show: boolean): void {
        this.dropdown.innerHTML = show ? SPINNER_HTML : ''
    }

    private hideDropdown(): void {
        this.loading(false)
        this.dropdown.classList.add(HIDE_CLASS)
    }

    private showDropdown(): void {
        this.dropdown.classList.remove(HIDE_CLASS)
    }
}