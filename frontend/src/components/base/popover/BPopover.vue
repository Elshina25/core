<template>
  <div class="popover">
    <div class="popover__trigger" @click="toggle">
      <slot name="trigger"></slot>
    </div>
    <div v-show="isActive" class="popover__wrapper">
      <div class="popover__body">
        <slot name="body"></slot>
        <b-button class="mt-22 md:hidden" variant="black-outline" @click="hide">
          Закрыть
        </b-button>
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import BButton from '@/components/base/button/BButton.vue'
import { ref } from 'vue'

const isActive = ref<boolean>(false)

const hide = () => (isActive.value = false)

const toggle = () => (isActive.value = !isActive.value)
</script>
<style src="./popover.css" scoped></style>
