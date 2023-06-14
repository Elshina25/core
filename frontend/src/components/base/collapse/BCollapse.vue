<template>
  <div
    class="collapse"
    :class="{
      'collapse--active': isActive
    }"
  >
    <div
      class="collapse__head"
      :class="{ 'cursor-pointer': !panel, 'cursor-default': panel }"
      @click="toggle"
    >
      {{ title }}
      <b-icon
        v-if="!panel"
        class="collapse__arrow"
        :class="{ 'collapse__arrow--active': isActive }"
        name="arrow-down-sharp"
      />
    </div>
    <div
      class="collapse__content"
      :class="{ 'collapse__content--active': isActive }"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
import BIcon from '@/components/base/icon/BIcon.vue'

import { ref } from 'vue'

const props = defineProps<{
  title: string
  panel?: boolean
}>()

const isActive = ref(false)

const toggle = () => {
  if (props.panel) return
  isActive.value = !isActive.value
}
</script>

<style src="./collapse.css" scoped></style>
