<template>
  <form
    v-show="showSearchInput"
    class="search-group"
    @submit.prevent="redirectToSearch"
  >
    <b-input
      ref="input"
      v-model="search"
      class="search-group__input"
      name="search"
      placeholder="Поиск по сайту"
      :disabled="loading.status"
    />
    <b-button
      :disabled="isBtnDisabled"
      class="search-group__button"
      variant="dark"
    >
      <span v-if="loading.status" class="search-group__spinner"></span>
      <b-icon v-else class="search-group__search-icon" name="search" />
    </b-button>
  </form>
  <div class="icon">
    <b-icon :name="iconName" @click="toggleInputSearch" />
  </div>
</template>

<script lang="ts" setup>
import BInput from '@/components/base/input/BInput.vue'
import BButton from '@/components/base/button/BButton.vue'
import BIcon from '@/components/base/icon/BIcon.vue'
import { usePageContext } from '~/renderer/utils'
import { navigate } from 'vite-plugin-ssr/client/router'
import { ROUTE } from '@/routes'
import { useLoader } from '@/composables/useLoader'
import { ref, computed, nextTick, watch } from 'vue'

const emit = defineEmits<{
  (e: 'toggle-search-activity', event: boolean): void
}>()

const pageContext = usePageContext()
const loading = useLoader()

// Значение поля с поиском
const search = ref<string>(pageContext.urlParsed.search.query || '')
// ref input-a
const input = ref<typeof BInput | null>(null)
// Флаг переключающий активность поля с поиском
const showSearchInput = ref<boolean>(
  pageContext.urlPathname === ROUTE.SEARCH.slug
)

const iconName = computed<string>(() =>
  showSearchInput.value ? 'close-light' : 'search'
)

/**
 * Закрываем поиск при смене роута (кроме страницы с результатом поиска)
 */
watch(
  () => pageContext.urlPathname,
  () => {
    if (
      showSearchInput.value &&
      pageContext.urlPathname !== ROUTE.SEARCH.slug
    ) {
      showSearchInput.value = false
      emit('toggle-search-activity', showSearchInput.value)
    }
  }
)

/**
 * Переключение активность формы поиска
 * @returns
 * @description метод переключает активность поля с поиском и задает focus на input, если поле активно
 */
const toggleInputSearch = async () => {
  showSearchInput.value = !showSearchInput.value

  // Прокидываем значение вверх, для того что бы скрыть пункты меню
  emit('toggle-search-activity', showSearchInput.value)
  if (!showSearchInput.value) return

  await nextTick()

  // Если поле активно, накидываем фокус
  input.value?.$refs.input.focus()
}

const isBtnDisabled = computed<boolean>(
  () =>
    !search.value ||
    loading.status ||
    pageContext.urlParsed.search.query === search.value
)

/**
 * Редирект
 * @returns
 * @description метод перекидывает на страницу с результатами поиска
 */
const redirectToSearch = async () => {
  loading.start()

  // Формируем url
  const path = `${ROUTE.SEARCH.slug}?query=${search.value}`
  // Редиректим на страницу поиска
  await navigate(path)

  loading.stop()
}
</script>

<style lang="postcss" scoped>
.icon {
  @apply cursor-pointer transition mobile:mr-8 md:mr-12 hover:text-green 
    mobile:min-w-[24px] mobile:h-24 md:min-w-[36px] md:h-36;

  svg {
    @apply mobile:w-24 mobile:h-24;
  }
}

.search-group {
  @apply relative w-full;

  &__spinner {
    @apply inline-block w-24 h-24;

    &::after {
      @apply content-[''] block w-24 h-24 rounded-[80%] border-2
    border-t-black border-r-transparent border-b-black border-l-transparent animate-spin;
    }
  }

  &__button {
    @apply flex items-center justify-center absolute 
      top-0 bottom-0 m-auto right-8 md:right-10 2xl:right-20
      w-34 h-24 md:w-54 md:h-36 mobile:rounded-[6px] bg-auxiliary-6/50 border-transparent
    hover:border-green hover:bg-green 
      disabled:border-transparent disabled:bg-auxiliary-6/50;
  }

  &__button:hover &__search-icon {
    @apply fill-white;
  }

  &__button:disabled &__search-icon {
    @apply opacity-50;
  }

  &__search-icon {
    @apply mobile:w-24 mobile:h-24 mr-2 fill-black;
  }

  &__input {
    @apply w-full;

    :deep(input) {
      @apply h-44 md:h-[70px] t-p2 p-20 pr-48 md:pr-72 2xl:pr-84 border rounded 
        border-auxiliary-5/50 focus:border-green hover:border-green;
    }
  }
}
</style>
