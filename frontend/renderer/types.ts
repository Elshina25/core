import { IBeforeRenderReturn } from '@/types/renderer'
import type { StateTree } from 'pinia'
import internal from 'stream'
import type { PageContextBuiltIn } from 'vite-plugin-ssr'
// import type { PageContextBuiltInClient } from 'vite-plugin-ssr/client/router' // When using Client Routing
import type { PageContextBuiltInClient } from 'vite-plugin-ssr/client' // When using Server Routing
import type { ComponentPublicInstance } from 'vue'

type Page = ComponentPublicInstance // https://stackoverflow.com/questions/63985658/how-to-type-vue-instance-out-of-definecomponent-in-vue-3/63986086#63986086

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export type Component = any

export type PageContextCustom = {
  Page: Page
  Layout: Component
  initialStoreState: Record<string, StateTree>
  urlPathname: string
  isHydration: false
  exports: IBeforeRenderReturn['pageContext']
} & IBeforeRenderReturn['pageContext']

export type PageContextServer = PageContextBuiltIn<Page> &
  PageContextCustom & { stream: internal.Readable }
export type PageContextClient = PageContextBuiltInClient<Page> &
  PageContextCustom

export type PageContext = PageContextClient | PageContextServer
