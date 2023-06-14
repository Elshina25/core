/* eslint-disable prettier/prettier */
var __create = Object.create
var __defProp = Object.defineProperty
var __getOwnPropDesc = Object.getOwnPropertyDescriptor
var __getOwnPropNames = Object.getOwnPropertyNames
var __getProtoOf = Object.getPrototypeOf
var __hasOwnProp = Object.prototype.hasOwnProperty
var __copyProps = (to, from, except, desc) => {
  if ((from && typeof from === 'object') || typeof from === 'function') {
    for (let key of __getOwnPropNames(from))
      if (!__hasOwnProp.call(to, key) && key !== except)
        __defProp(to, key, {
          get: () => from[key],
          enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable
        })
  }
  return to
}
var __toESM = (mod, isNodeMode, target) => (
  (target = mod != null ? __create(__getProtoOf(mod)) : {}),
  __copyProps(
    isNodeMode || !mod || !mod.__esModule
      ? __defProp(target, 'default', { value: mod, enumerable: true })
      : target,
    mod
  )
)

// server/index.ts
var import_express = __toESM(require('express'))
var import_compression = __toESM(require('compression'))
var import_vite_plugin_ssr = require('vite-plugin-ssr')
var isProduction = process.env.NODE_ENV === 'production'
var root = `${__dirname}/..`
async function startServer() {
  const app = (0, import_express.default)()
  app.use((0, import_compression.default)())
  if (isProduction) {
    const sirv = require('sirv')
    app.use(sirv(`${root}/dist/client`))
  } else {
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
    const pageContext = await (0, import_vite_plugin_ssr.renderPage)(
      pageContextInit
    )
    const { httpResponse } = pageContext
    if (!httpResponse) return next()
    res.status(httpResponse.statusCode).type(httpResponse.contentType)
    httpResponse.pipe(res)
  })
  const port = process.env.PORT || 3e3
  app.listen(port)
  console.log(`Server running at http://localhost:${port}`)
}
startServer()
