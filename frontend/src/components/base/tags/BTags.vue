<template>
  <div
    class="tags"
    :class="[shadowClasses, { 'padding-container': paddingContainer }]"
  >
    <div ref="scrollContainer" class="tag-list" @scroll="scrollHandler">
      <b-tag
        v-for="item in items"
        :key="item.code"
        :size="size"
        :active="isActive(item.code)"
        @click="setValue(item.code)"
        >{{ item.name }}</b-tag
      >
    </div>

    <div class="tag-nav tag-nav-left">
      <div class="tag-nav__shadow" @click="scrollLeft">
        <b-icon name="arrow-left" />
      </div>
    </div>
    <div class="tag-nav tag-nav-right" @click="scrollRight">
      <div class="tag-nav__shadow">
        <b-icon name="arrow-left" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue'
import { ITag } from '@/types'
import { useModelValue } from '@/composables/useModelValue'
import { useShadowScroll } from '@/composables/useShadowScroll'
import BTag from '@/components/base/tags/BTag.vue'
import BIcon from '@/components/base/icon/BIcon.vue'

defineEmits(['update:modelValue'])

const props = withDefaults(
  defineProps<{
    modelValue: ITag['code']
    items: ITag[]
    size?: 'big' | 'small'
    paddingContainer?: boolean
  }>(),
  {
    size: 'big',
    paddingContainer: true
  }
)

const { value: active, setValue } = useModelValue(props)

const scrollContainer: Ref<HTMLInputElement | null> = ref(null)
const { shadowClasses, scrollHandler, scrollLeft, scrollRight } =
  useShadowScroll(scrollContainer)

/**
 * Активен ли тег
 * @param code
 */
const isActive = (code: ITag['code']) => String(active.value) === String(code)
</script>

<style src="./tags.css" scoped></style>
