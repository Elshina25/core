<template>
  <div class="topics">
    <b-tag
      v-for="(item, idx) in items"
      :key="idx"
      variant="button"
      :active="isActive(item.code)"
      @click="setValue(item.code)"
    >
      {{ item.name }}
    </b-tag>
  </div>
</template>

<script setup lang="ts">
import { IFeedbackTopic } from '@/types'
import { useModelValue } from '@/composables/useModelValue'
import BTag from '@/components/base/tags/BTag.vue'

type TopicValue = IFeedbackTopic['code']

defineEmits(['update:modelValue'])
const props = defineProps<{
  modelValue: TopicValue
  items: IFeedbackTopic[]
}>()

const { value: activeTopic, setValue } = useModelValue(props)
const isActive = (value: TopicValue) => activeTopic.value === value
</script>

<style lang="postcss" scoped>
.topics {
  @apply flex flex-wrap
    gap-x-4 gap-y-8
    md:gap-x-8 md:gap-y-12
    2xl:gap-x-10 2xl:gap-y-20;
}
</style>
