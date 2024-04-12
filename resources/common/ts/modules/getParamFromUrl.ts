export default (param: string): string | null => {
    const url = new URL(location.href)
    return url.searchParams.get(param)
}