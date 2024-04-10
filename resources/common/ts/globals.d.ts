type Globals = {
    ajaxUrl: string
    nonce: string
    mainUrl: string
}

export { }

declare global {
    interface Window {
        shoMenuGlobals: Globals
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        shoMenuGlobals: Globals
    }
}