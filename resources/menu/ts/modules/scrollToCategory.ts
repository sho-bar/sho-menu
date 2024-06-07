export default (id: number): void => {
    const selector = `.sho-menu__categories-bar__item[data-category-id="${id}"]`
    const el = document.querySelector<HTMLElement>(selector)

    if (!el) {
        console.warn(`Category with id ${id} not found in DOM`)
        return
    }

    const parent = el.parentNode as HTMLElement

    // Calculate the center of the element
    const elCenter = el.offsetLeft + el.offsetWidth / 2

    // Calculate the center of the parent
    const parentCenter = parent.offsetWidth / 2

    // Scroll the parent so the center of the element
    // aligns with the center of the parent
    parent.scrollLeft = elCenter - parentCenter
}