<template>
  <div class="error">
    <img class="error-code" :src="`/images/${error.code}.svg`" />

    <div class="max-w-[500px]">
      <h2 class="mb-10 2xl:mb-20">{{ error.title }}</h2>
      <p class="t-p2 mb-20">{{ error.description }}</p>

      <a href="/" class="t-p2 link">Вернуться на главную</a>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

const props = defineProps<{
  is404: boolean
}>()

const error = computed(() => {
  if (props.is404) {
    return {
      code: 404,
      title: 'Страница не найдена',
      description:
        'Неправильно набран адрес, или такой страницы на сайте больше не существует.'
    }
  }

  return {
    code: 500,
    title: 'Ошибка сервера',
    description:
      'На сервере произошла внутренняя ошибка. Мы постараемся решить проблему как можно скорее.'
  }
})
</script>

<style lang="postcss" scoped>
.error {
  @apply container mt-60 mb-80;

  @screen md {
    @apply flex gap-[77px] justify-center mt-100 mb-120;
  }

  @screen 2xl {
    @apply gap-[86px] mt-[144px] mb-[180px];
  }

  &-code {
    @apply w-[211px] md:w-[256px] 2xl:w-[394px]
      mobile:mb-30 md:ml-44 2xl:ml-50;
  }
}
</style>
