import * as path from 'path'
import vue from '@vitejs/plugin-vue'
import ssr from 'vite-plugin-ssr/plugin'
import SvgLoader from 'vite-svg-loader'
import VueTypeImports from 'vite-plugin-vue-type-imports'
import { UserConfig } from 'vite'

const config: UserConfig = {
  plugins: [
    vue(),
    ssr(),
    SvgLoader({
      defaultImport: 'component',
      svgoConfig: {
        plugins: [
          {
            name: 'preset-default',
            params: {
              overrides: {
                removeViewBox: false
              }
            }
          }
        ]
      }
    }),
    // TODO: Отказаться от этого решения, после обновления на Vue 3.3 (https://github.com/vuejs/core/issues/4294)
    VueTypeImports()
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
      '~': path.resolve(__dirname, './')
    }
  },
  optimizeDeps: {
    exclude: ['swiper/vue', 'swiper/types']
  }
}

export default config
