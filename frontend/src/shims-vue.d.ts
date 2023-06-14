import { ROUTE } from './routes'

declare module 'vue' {
  interface ComponentCustomProperties {
    $ROUTE: typeof ROUTE
  }
}
