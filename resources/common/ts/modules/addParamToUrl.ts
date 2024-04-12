export default (param: string, value: string): void => {
    const url = new URL(location.href)
    url.searchParams.set(param, value)
    history.pushState({}, '', url.toString())
}