import DishSearchDropdown from '@admin/modules/DishSearchDropdown'

document.addEventListener('DOMContentLoaded', () => {
    (function ActivateDishSearchingDropdownWhenTyping() {
        const input = document.getElementById('sho-recommended-dishes') as HTMLInputElement

        if (!input) {
            console.error("Element with ID 'sho-recommended-dishes' not found")
            return
        }

        const dropdown = document.getElementById('sho-recommended-dishes-dropdown') as HTMLElement

        if (!dropdown) {
            console.error("Element with ID 'sho-recommended-dishes-dropdown' not found")
            return
        }

        const list = document.getElementById('sho-recommended-dishes-list') as HTMLElement

        if (!list) {
            console.error("Element with ID 'sho-recommended-dishes-list' not found")
            return
        }

        new DishSearchDropdown(input, dropdown, list).init()
    })()
})