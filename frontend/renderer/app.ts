import { createSSRApp, defineComponent, h, markRaw, reactive } from 'vue'
import { setPageContext } from './utils'
import type { Component, PageContext } from './types'
import type { DefaultPageProps } from '@/types/renderer'
import LayoutDefault from '@/layouts/LayoutDefault.vue'
import VeeValidatePlugin from '@/plugins/VeeValidatePlugin'
import { ROUTE } from '@/routes'
import pinia from '@/stores'

interface Data {
  Page: PageContext['Page']
  pageProps: PageContext['pageProps']
  Layout: PageContext['Layout']
}

export function createApp(pageContext: PageContext) {
  const { Page } = pageContext

  let rootComponent: Component
  const PageWithWrapper = defineComponent({
    data: (): Data => ({
      Page: markRaw(Page),
      pageProps: markRaw(pageContext.pageProps || <DefaultPageProps>{}),
      Layout: markRaw(pageContext.exports.Layout || LayoutDefault)
    }),
    created() {
      // eslint-disable-next-line @typescript-eslint/no-this-alias
      rootComponent = this
    },
    render() {
      return h(
        this.Layout,
        {},
        {
          default: () => {
            return h(this.Page, this.pageProps)
          }
        }
      )
    }
  })

  const app = createSSRApp(PageWithWrapper)
  const store = pinia()

  app.use(store)
  app.use(VeeValidatePlugin)

  // We use `app.changePage()` to do Client Routing, see `_default.page.client.js`
  objectAssign(app, {
    changePage: (pageContext: PageContext) => {
      if (!pageContext.pageProps) {
        pageContext.pageProps = <DefaultPageProps>{}
      }

      Object.assign(pageContextReactive, pageContext)
      rootComponent.Page = markRaw(pageContext.Page)
      rootComponent.pageProps = markRaw(pageContext.pageProps || {})
      rootComponent.Layout = markRaw(
        pageContext.exports.Layout || LayoutDefault
      )
    }
  })

  // When doing Client Routing, we mutate pageContext (see usage of `app.changePage()` in `_default.page.client.js`).
  // We therefore use a reactive pageContext.
  const pageContextReactive = reactive(pageContext)

  // Make `pageContext` accessible from any Vue component
  setPageContext(app, pageContextReactive)
  app.config.globalProperties.$ROUTE = ROUTE

  return { app, store }
}

// Same as `Object.assign()` but with type inference
function objectAssign<Obj extends object, ObjAddendum>(
  obj: Obj,
  objAddendum: ObjAddendum
): asserts obj is Obj & ObjAddendum {
  Object.assign(obj, objAddendum)
}
