import { createApp } from './app'
import { getPageTitle } from './utils/getPageHead'
import type { PageContext } from './types'
import type { PageContextBuiltInClient } from 'vite-plugin-ssr/client/router'
import '../src/assets/styles/main.css'

export const clientRouting = true
export const prefetchStaticAssets = false

let app: ReturnType<typeof createApp>['app']

export async function render(
  pageContext: PageContextBuiltInClient & PageContext
) {
  if (!app) {
    const instance = createApp(pageContext)
    app = instance.app
    instance.store.state.value = pageContext.initialStoreState
    app.mount('#app')
  } else {
    app.changePage(pageContext)
  }

  //Выпонение скрипта для CallTouch - выполняется при каждом переходе
  //@ts-ignore
  // prettier-ignore
  (function(w,d,n,c){w.CalltouchDataObject=n;w[n]=function(){w[n]["callbacks"].push(arguments)};if(!w[n]["callbacks"]){w[n]["callbacks"]=[]}w[n]["loaded"]=false;if(typeof c!=="object"){c=[c]}w[n]["counters"]=c;for(var i=0;i<c.length;i+=1){p(c[i])}function p(cId){var a=d.getElementsByTagName("script")[0],s=d.createElement("script"),i=function(){a.parentNode.insertBefore(s,a)},m=typeof Array.prototype.find === 'function',n=m?"init-min.js":"init.js";s.type="text/javascript";s.async=true;s.src="https://mod.calltouch.ru/"+n+"?id="+cId;if(w.opera=="[object Opera]"){d.addEventListener("DOMContentLoaded",i,false)}else{i()}}})(window,document,"ct","jqzw4a0b"); // eslint-disable-line

  document.head.querySelectorAll('script').forEach((a) => {
    if (a.src.match(/mod\.calltouch\.ru/is)) {
      a.remove()
    }
  })

  document.title = getPageTitle(pageContext)
}
export function onHydrationEnd() {
  // console.log('Hydration finished; page is now interactive.')
}

export function onPageTransitionStart() {
  // console.log('Page transition start')
  document.querySelector('.content')?.classList.add('page-transition')
}

export function onPageTransitionEnd() {
  // console.log('Page transition end')
  document.querySelector('.content')?.classList.remove('page-transition')
}
