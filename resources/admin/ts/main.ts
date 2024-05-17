import DishSearchDropdown from '@admin/modules/DishSearchDropdown'

document.addEventListener('DOMContentLoaded', () => {
    (function ActivateDishSearchingDropdownWhenTyping() {
        const input = document.getElementById('sho-recommended-dishes') as HTMLInputElement
        const dropdown = document.getElementById('sho-recommended-dishes-dropdown') as HTMLElement

        if (!input) {
            console.error("Element with ID 'sho-recommended-dishes' not found")
            return
        }

        if (!dropdown) {
            console.error("Element with ID 'sho-recommended-dishes-dropdown' not found")
            return
        }

        new DishSearchDropdown(input, dropdown).init()
    })()
})