export default (...params: string[]): void => {
    const url = new URL(location.href)

    for (const param of params) {
        url.searchParams.delete(param)
    }

    history.pushState({}, '', url.toString())
}