<template>
  <div v-click-outside="hide" class="dropdown" :class="`dropdown--${variant}`">
    <button class="t-button dropdown__trigger" @click="toggle">
      {{ label }}<b-icon name="arrow-down" />
    </button>
    <div v-if="isActive" class="dropdown__body">
      <div v-for="(item, idx) in items" :key="idx">
        <slot :item="item" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { IDropdownItem } from '@/types/ui'
import { vClickOutside } from '@/composables/useClickOutside'
import BIcon from '@/components/base/icon/BIcon.vue'

type Variant = 'light' | 'dark'

withDefaults(
  defineProps<{
    label: string
    items: IDropdownItem[]
    variant?: Variant
  }>(),
  {
    variant: 'light'
  }
)

const isActive = ref(false)

const toggle = () => (isActive.value = !isActive.value)
const hide = () => (isActive.value = false)
</script>

<style src="./styles/dropdown.css" scoped></style>
