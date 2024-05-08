export default (): void => {
    const elem = document.querySelector('.sho-menu__dishes__section')

    if (!elem) {
        return
    }

    elem.scrollIntoView({ behavior: 'instant', block: 'start' })
}