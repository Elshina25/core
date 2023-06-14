<template>
  <div class="search-item">
    <!-- TODO Vlad: Убрать :class после добавления иконки для объектов -->
    <b-icon
      v-if="icon"
      :name="icon"
      :collection="collection"
      :class="{ 'search-item__icon--object': icon === 'location-fill' }"
      class="search-item__icon"
    />
    {{ prefix + label }}
  </div>
</template>

<script setup lang="ts">
import BIcon from '@/components/base/icon/BIcon.vue'

withDefaults(
  defineProps<{
    label: string
    icon?: string
    prefix?: string
    collection?: string
  }>(),
  {
    icon: '',
    prefix: '',
    collection: 'ui'
  }
)
</script>

<style src="./search-item.css" scoped></style>
