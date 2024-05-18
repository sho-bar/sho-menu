export default (callback: (categoryId: number) => void): void => {
    const elements = document.querySelectorAll('.sho-menu__dishes__section')
    const categoryElements: Element[] = Array.from(elements)

    if (categoryElements.length === 0) {
        console.warn('No category elements found')
        return
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                return
            }

            const target = entry.target as HTMLElement
            const child = target.querySelector<HTMLElement>('.sho-menu__dishes__category')!
            const categoryId = child.getAttribute('data-category-id')

            if (!categoryId) {
                return
            }

            callback(parseInt(categoryId))
        })
    })

    for (const element of categoryElements) {
        observer.observe(element)
    }
}