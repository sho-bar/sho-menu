/// <reference types="vite/client" />

declare module "*.vue" {
    import { defineComponent } from "vue"
    const Component: ReturnType<typeof defineComponent>
    export default Component
}

declare module 'vue/dist/vue.esm-bundler' {
    import type { CreateAppFunction } from '@vue/runtime-core'
    export const createApp: CreateAppFunction<Element>
}
