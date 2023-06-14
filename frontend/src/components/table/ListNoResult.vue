<template>
  <div class="no-result">
    <h3>{{ title }}</h3>
    <div class="t-p2">
      {{ description }}
      <a v-if="link" :href="link" class="link">{{ linkLabel }}</a
      >.
    </div>
  </div>
</template>

<script lang="ts" setup>
withDefaults(
  defineProps<{
    title?: string
    description?: string
    link?: string
    linkLabel?: string
  }>(),
  {
    title: 'По данному запросу ничего не найдено.',
    description:
      'Попробуйте поменять параметры фильтра, или перейти на стартовую',
    link: '',
    linkLabel: ''
  }
)
</script>

<style scoped lang="postcss">
.no-result {
  @apply bg-white rounded flex flex-col;
  @apply gap-16 md:gap-20 xl:gap-30 p-16 md:p-26 xl:p-40;

  h3 {
    @apply mb-0;
  }
}
</style>
