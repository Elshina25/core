<template>
  <div
    class="favorite-group"
    :class="`favorite-group--${variant}`"
    @click="favorites.toggle(props.id)"
  >
    <b-icon class="favorite-group__icon" :name="favoriteIcon" />
    <span class="favorite-group__label link link-button">
      {{ favoriteLabel }}
    </span>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useFavoritesStore } from '@/stores/favorites'
import BIcon from '@/components/base/icon/BIcon.vue'

type Variant = 'collapsed' | 'default'

const props = withDefaults(
  defineProps<{
    id: string
    label?: string
    activeLabel?: string
    variant?: Variant
  }>(),
  {
    label: 'Добавить в избранное',
    activeLabel: 'Убрать из избранного',
    variant: 'default'
  }
)

const favorites = useFavoritesStore()
const isActive = computed(() => favorites.isActive(props.id))

const favoriteLabel = computed(() =>
  isActive.value ? props.activeLabel : props.label
)

const favoriteIcon = computed(() => (isActive.value ? 'heart-fill' : 'heart'))
</script>

<style src="./favorite.css" scoped></style>
