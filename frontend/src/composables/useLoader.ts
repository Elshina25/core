import { loadingSymbol } from '@/components/base/loader/injectionKeys'
import { computed, provide, reactive } from 'vue'

export const useLoader = () => {
  const loading = reactive({
    status: false,
    start: () => (loading.status = true),
    stop: () => (loading.status = false)
  })

  provide(
    loadingSymbol,
    computed(() => loading.status)
  )

  return loading
}
