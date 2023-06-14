import { onMounted, ref } from 'vue'

/**
 * Client only
 * Если нам нужно отобразить что-то только в браузере,
 * исключая отображение на сервере.
 */
export const useClientOnly = () => {
  const status = ref(false)

  onMounted(() => {
    status.value = true
  })

  return status
}
