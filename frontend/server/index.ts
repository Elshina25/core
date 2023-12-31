import express from 'express'
import compression from 'compression'
import { renderPage } from 'vite-plugin-ssr'

const isProduction = process.env.NODE_ENV === 'production'
const root = `${__dirname}/..`

async function startServer() {
  const app = express()

  app.use(compression())

  if (isProduction) {
    // eslint-disable-next-line @typescript-eslint/no-var-requires
    const sirv = require('sirv')
    app.use(sirv(`${root}/dist/client`))
  } else {
    // eslint-disable-next-line @typescript-eslint/no-var-requires
    const vite = require('vite')
    const viteDevMiddleware = (
      await vite.createServer({
        root,
        server: { middlewareMode: true }
      })
    ).middlewares
    app.use(viteDevMiddleware)
  }

  app.get('*', async (req, res, next) => {
    const pageContextInit = { urlOriginal: req.originalUrl }
    const pageContext = await renderPage(pageContextInit)
    const { httpResponse } = pageContext

    if (!httpResponse) return next()

    res.status(httpResponse.statusCode).type(httpResponse.contentType)
    httpResponse.pipe(res)
  })

  const port = process.env.PORT || 3000
  app.listen(port)
  console.log(`Server running at http://localhost:${port}`)
}

startServer()
