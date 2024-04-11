export default (size: number): boolean => {
    return window.matchMedia(`(min-width: ${size}px)`).matches
}