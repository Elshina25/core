<template>
  <Teleport v-if="client" to="#teleported">
    <transition
      enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="opacity-0 translate-y-10 scale-105"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-10 translate-y-0 scale-105"
    >
      <div
        v-show="isOpen"
        ref="modal"
        role="dialog"
        aria-modal="true"
        class="modal"
        :class="[
          { 'modal--container': container },
          { 'modal--background': background }
        ]"
      >
        <div class="modal-content">
          <div class="close" @click="close">
            <b-icon name="close" />
          </div>
          <slot />
        </div>
        <div class="modal-overflow" @click="close"></div>
      </div>
    </transition>
  </Teleport>
</template>

<script setup lang="ts">
import { onMounted, provide, ref, watch } from 'vue'
import { useModelValue } from '@/composables/useModelValue'
import { useScrollLock } from '@/composables/useScrollLock'
import { ModalSymbol } from './injectionKeys'
import BIcon from '../icon/BIcon.vue'

defineEmits<{
  (e: 'update:modelValue', event: boolean): boolean
}>()

const props = withDefaults(
  defineProps<{
    modelValue: boolean
    container?: boolean
    background?: boolean
  }>(),
  {
    container: false,
    background: false
  }
)

// Телепортируем модалки только на клиенте
const client = ref(false)
onMounted(() => (client.value = true))

const modal = ref()
const { value: isOpen, setValue } = useModelValue(props)

/**
 * Закрытие модалки
 */
const close = () => setValue(false)

/**
 * Следим за изменением отображение модалки,
 */
watch(isOpen, (is) => useScrollLock(is, modal))

provide(ModalSymbol, {
  close
})
</script>

<style src="./modal.css" scoped></style>
