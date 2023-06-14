<template>
  <template v-if="size === 'original'">
    <component :is="iconComponent" fill="currentColor" />
  </template>
  <template v-else>
    <component
      :is="iconComponent"
      :width="customSize.width"
      :height="customSize.height"
      :viewBox="customSize.viewBox"
      fill="currentColor"
    />
  </template>
</template>

<script setup lang="ts">
import { Component as IComponent } from 'vue'
import { computed, defineAsyncComponent } from 'vue'
import { lowerCaseFirstLetter } from '@/utils'

const props = withDefaults(
  defineProps<{
    name: string
    collection?: string
    width?: number
    height?: number
    viewBox?: string
    size?: 24 | 36 | 44 | 'original'
  }>(),
  {
    collection: 'ui',
    width: 24,
    height: 24,
    viewBox: '0 0 24 24',
    size: 'original'
  }
)

/**
 * Пользовательские или стандартные размеры
 */
const customSize = computed(() => {
  return {
    width: props.size ? props.size : props.width,
    height: props.size ? props.size : props.height,
    viewBox: props.size ? `0 0 ${props.size} ${props.size}` : props.viewBox
  }
})

/**
 * Импортируем все иконки из папки
 */
const iconImports = import.meta.glob<IComponent>(
  '../../../assets/icons/**/*.svg'
)

/**
 * Получение имени svg файла
 * @param path ../../../assets/icons/arrow.svg
 * @return arrow
 */
const getFileNameFromPath = (path: string) => {
  const file = path.split('/').pop() ?? ''

  if (!file) return file

  return file.split('.').shift() ?? ''
}

/**
 * Получение имени коллекции
 * @param path ../../../assets/icons/logo/google.svg
 * @return logo
 */
const getFileCollectionFromPath = (path: string) => {
  const collection = path.split('/').at(-2) ?? ''

  if (!collection || collection === 'icons') return ''

  return collection
}

const getCollectionName = (collection: string) => {
  return collection ? collection + '-' : collection
}

/**
 * Составляем список иконок
 */
const getIconsList = () => {
  const icons: Record<string, () => Promise<IComponent>> = {}

  for (const path in iconImports) {
    const iconName = lowerCaseFirstLetter(getFileNameFromPath(path))
    const iconCollection = getFileCollectionFromPath(path).toLowerCase()

    const iconKey = getCollectionName(iconCollection) + iconName
    icons[iconKey] = iconImports[path]
  }

  return icons
}

const icons = getIconsList()

/**
 * Проверяем наличие иконки
 * и отдаем компонент, если существует
 */
const iconComponent = computed(() => {
  const icon = icons[getCollectionName(props.collection) + props.name]

  /**
   * Icon is not defined.
   * Show error message from development.
   */
  if (!icon && process.env.NODE_ENV !== 'production') {
    console.warn(`Icon "${props.name}" is not found`)
  }

  // Stop render: icon is not defined
  if (!icon) return

  return defineAsyncComponent(icon)
})
</script>

<style>
svg > * {
  @apply fill-inherit;
}
</style>
