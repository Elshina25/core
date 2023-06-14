<template>
  <div class="stub">
    <div class="stub__text">
      <h3 class="mb-8">{{ title }}</h3>
      <p class="t-p2">{{ subtitle }}</p>
    </div>

    <b-button
      variant="white-blue"
      :transparent="true"
      @click="scrollToSelector"
    >
      {{ buttonLabel }}
    </b-button>
  </div>
</template>

<script setup lang="ts">
import BButton from '@/components/base/button/BButton.vue'

const props = withDefaults(
  defineProps<{
    scrollTo: string
    title?: string
    subtitle?: string
    buttonLabel?: string
  }>(),
  {
    title: 'Заказать исследование',
    subtitle:
      'Предоставим необходимую информацию о ситуации на рынке коммерческой недвижимости для принятия лучшего решения.',
    buttonLabel: 'Отправить заявку'
  }
)

// TODO: нужна проверка на существование элемента
const scrollToSelector = () => {
  document.querySelector(props.scrollTo)?.scrollIntoView({
    behavior: 'smooth'
  })
}
</script>

<style lang="postcss" scoped>
.stub {
  @apply min-h-[288px] text-white bg-[url(/images/form/form-blue-bg-mobile.png)] bg-cover rounded p-16;
  @apply flex-col justify-between hidden mobile:flex;

  &__text {
    @apply xl:col-span-7 md:col-span-2 md:w-4/5;
  }
}
</style>
