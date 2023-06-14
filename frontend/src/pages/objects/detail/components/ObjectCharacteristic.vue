<template>
  <div>
    <h2 class="mb-20 md:mb-30 xl:mb-40">{{ title }}</h2>
    <div class="characteristic">
      <div v-for="(label, idx) in labels" :key="idx">
        <div class="t-p3 md:t-caption xl:t-p2">
          {{ label }}
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
defineProps<{
  title: string
  labels: string[]
}>()
</script>

<style scoped lang="postcss">
.characteristic {
  @apply bg-white rounded p-16 md:p-26 xl:p-60;
  @apply flex flex-col gap-y-12 md:gap-y-8 md:gap-x-36 xl:gap-30;
}
</style>
