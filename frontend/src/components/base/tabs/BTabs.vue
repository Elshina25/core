<template>
  <div class="tabs">
    <b-tags v-model="selectedTitle" :items="tabs" size="small" />
    <slot />
  </div>
</template>

<script lang="ts" setup>
import { useSlots, ref, provide, onBeforeUpdate, watchEffect } from 'vue'
import { selectedTitleSymbol } from './injectionKeys'
import type { VNode, VNodeChild, Slots } from 'vue'
import type { ITag } from '@/types'
import BTags from '../tags/BTags.vue'

const props = defineProps<{
  activeTab?: string
}>()

const tabs = ref<ITag[]>([])
const slots: Slots = useSlots()

// TODO Vlad: разобраться с типами
// Проверка на то, является ли элемент табом
// @ts-ignore:next-line
const isTab = (node: VNodeChild): boolean => node?.type?.name === 'BTab'

// Проверка на наличие дочерних элементов с табами
const hasTabs = (node: VNode): boolean =>
  Array.isArray(node.children) && node.children.some(isTab)

// Формируем массив с табами из слота
const update = () => {
  if (!slots.default) return
  tabs.value = slots
    .default()
    .filter((node: VNode) => isTab(node) || hasTabs(node))
    .flatMap((node: VNode) => (hasTabs(node) ? node.children : node))
    .map((el): ITag => {
      const { props } = el as VNode
      return { name: props?.title, code: props?.title }
    })
}

update()
onBeforeUpdate(() => update())

const selectedTitle = ref(props.activeTab || tabs.value?.[0]?.code)

watchEffect(() => {
  if (!props.activeTab) return
  selectedTitle.value = props.activeTab
})

provide(selectedTitleSymbol, selectedTitle)
</script>
