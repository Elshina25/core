<template>
  <label class="w-fit">
    <slot name="label"></slot>
    <div class="filter-element filter-element--border">
      <input
        v-model="startModelValue"
        :placeholder="startPlaceholder"
        :class="startClass"
        class="!pr-0"
      />
      <span class="mx-10">—</span>
      <input
        v-model="endModelValue"
        :placeholder="endPlaceholder"
        :class="endClass"
        class="!px-0"
      />
      <span v-if="caption" class="ml-10 mr-20">{{ caption }}</span>
    </div>
  </label>
</template>

<script lang="ts" setup>
import { computed, toRefs } from 'vue'

type Value = string | number

const emit = defineEmits<{
  (e: 'update:start', event: Value): void
  (e: 'update:end', event: Value): void
}>()

const props = defineProps<{
  start: Value
  end: Value
  startPlaceholder?: string
  endPlaceholder?: string
  startClass?: string
  endClass?: string
  caption?: string
}>()

const { start, end } = toRefs(props)

const startModelValue = computed({
  get() {
    return start.value
  },

  set(val) {
    emit('update:start', val)
  }
})

const endModelValue = computed({
  get() {
    return end.value
  },

  set(val) {
    emit('update:end', val)
  }
})
</script>

<style src="./filter-range.css" scoped></style>
