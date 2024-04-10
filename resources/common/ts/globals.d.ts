import { Globals } from '@/types'

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