export default (callback: (categoryId: number) => void): void => {
    const dishes = document.querySelectorAll('.sho-menu__dishes__item')
    const dishesElements: Element[] = Array.from(dishes)

    if (dishesElements.length === 0) {
        console.warn('No category elements found')
        return
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                return
            }

            const categoryId = entry.target.getAttribute('data-category-id')

            if (!categoryId) {
                console.warn("Category element doesn't have data-category-id attribute")
                return
            }

            callback(parseInt(categoryId))
        })
    })

    for (const dish of dishesElements) {
        observer.observe(dish)
    }
}