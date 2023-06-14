import { renderToNodeStream } from '@vue/server-renderer'
import { escapeInject } from 'vite-plugin-ssr'
import { createApp } from './app'
import {
  getPageDescription,
  getPageTitle,
  getPageOgImage,
  getCanonicalLink
} from './utils/getPageHead'
import type { PageContext } from './types'
import type { PageContextBuiltIn } from 'vite-plugin-ssr'

export const passToClient: Partial<keyof PageContext>[] = [
  'initialStoreState',
  'documentProps',
  'pageProps',
  'routeParams'
]

export async function render(pageContext: PageContextBuiltIn & PageContext) {
  const { app, store } = createApp(pageContext)

  const stream = renderToNodeStream(app)
  const initialStoreState = store.state.value

  const title = getPageTitle(pageContext)
  const description = getPageDescription(pageContext)
  const image = getPageOgImage(pageContext)
  const canonicalLink = getCanonicalLink(pageContext)

  const documentHtml = escapeInject`<!DOCTYPE html>
  <html lang="ru">
    <head>
      <meta charset="UTF-8" />
      <link rel="icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAACOlBMVEUAAAAOsWlfRkUEtmwatW0JrWYPr28HsGYMqm4IrmgGqWUNr2sJrWcBqV8MrmoXr3kIsWUMsmQJr2gKr2YKrGYAuGwNrmQJrmcHrGMMqWQKrmcIq2kMrWgKqmkFzVoIr2gKp2UCs20IrWYIrWgNrWMIrGYHrWkAoHgJrGcPr2EIqWQJqWUHsWkTnmYIqmgAwHFFEicOpm4Us2sKr2cKrmgKrWgKrWcJrWcJrWcJrWcJrWcJrWcJrWcKrmgKrWcLrmkMr2gJrmcJrmcJrWcJrWcJrWcJrWcJrWcJrWYJrWUUrnUKr2gJrmcJrWcJrWcJrWcJrWcIr2gHsGgKrWcJrWcJrWcJrWcJrWcKrGkHsGkJrWcJrWcJrWgLrmkUr3UJrWcKrWgLq2oJr2gJrWcJrWcJrWcOrm4Kr2YJrmcJrWcJrWcJrWcJrWcJrmgIrmgJrWcJrWcKrWcJrmgJrWcKrWcJrWcJrWcJrGcJrWcJrWcJrWcJrWcKrmgJrmgJrmcJrmcKrWcKrWcJrWcJrmcJrWcJrWYJrGcJrWcJrmcNqmcKrWcJrWcKrmcKrGcJrWcJrmcPq2oKrmcJrWcJrWcIr2gJrmYJrWcJrGYKrGcJrWcJrWcIrWYHrmUIrWgJrWcJrGcGq2QIrmgJrWYJq2YJrGgOrmIJrGcJrWcJrWcJrmgJrWgJrWgJrWcJrWcJrGcJrWgJrWgHqGgHrmkJrWcJrWcJrWgJrGcJrGcJrWcJrWcIq2kOpm4JrWf///9N3J83AAAAvHRSTlMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEXP3yy3vb96suZXCcIFFOp7/77zX4zAgM5pez6z3INBVrT/PWWHwVg5qokA1+jFTnY94IHGqvkTGKsEhy06U1GkAyAyia48zvXU2luZcRE2i2iEyvVaAiE0TI21HwDbPK8F5XQQB+q20wCJKhQBBG1QAIHTPjJeBFUkcerbikDCyQ6WG9uZUQXA3ZcOZgAAAFdSURBVHjahMxjYoQxGATgqW2bF6i7Vr7aNre2bdvGSd671W6Sff4O8MEuOSU1LT0jk1RqjVanN9g74BdHo8lsYYxeMaZkZefkOuGbc15+QSH9VFRcUuriig9uZeUVlfRHVXWNuwfeeNbW1dN/qoZGL2+88Glqrice1tLqC8DPv62d+Kwdnc5AQFc3ifT0BgYhuC+ThPoHQjA4RGLDI6EYtZDE2DgmVCQxOYVpRhIzs5iTFlTzyCQZ1QJISrUIla2CWl5YwjIjCesKqqWF1TXoikhifQObWySmbIdhZ5fE9vbDEXFwSCKZR8eRiIo+OSWBs/MYADGlFwpxXV7F4lXc9Q0jjtu7+3i8Skh8eOR8PK99/gJVaMZRU1/onolmiMWixUs0EHlPc+myHORIWb5i5RQtbeTMq6O7avWatZWWIJDStG79ho16DGhA32DT5i1bt23fvmNn867dhnBxAJJQRU3SAWpXAAAAAElFTkSuQmCC"
        type="image/x-icon">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="description" content="${description}" />
      <link rel="canonical" href="${canonicalLink}"/>

      <meta property="og:title" content="${title}"/>
      <meta property="og:image" content="${image}" />
      <meta property="og:description" content="${description}" />
      <meta name="twitter:title" content="${title}"/>
      <meta name="twitter:image:src" content="${image}" />
      <meta name="twitter:description" content="${description}" />

      <title>${title}</title>

      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&family=Raleway:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">

      <!-- Favicon -->
      <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
      <link rel="manifest" href="/site.webmanifest">
      <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#09AD67">
      <meta name="msapplication-TileColor" content="#ffc40d">
      <meta name="theme-color" content="#09ad67">

      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-4WTHX2Z97B"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-4WTHX2Z97B');
      </script>

      <!-- Yandex.Metrika counter -->
      <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(23848627, "init", {
              clickmap:true,
              trackLinks:true,
              accurateTrackBounce:true,
              webvisor:true
        });
      </script>
      <noscript><div><img src="https://mc.yandex.ru/watch/23848627" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
      <!-- /Yandex.Metrika counter -->
    </head>
    <body>
      <div id="app">${stream}</div>
      <div id="teleported"></div>

    </body>
  </html>`

  const pageContextPromise = async () => {
    return {
      initialStoreState,
      enableEagerStreaming: true
    }
  }

  return {
    documentHtml,
    pageContext: pageContextPromise()
  }
}
