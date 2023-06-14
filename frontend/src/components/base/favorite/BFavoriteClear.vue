<template>
  <div class="favorite-group" @click="clearFavorites">
    <b-icon class="favorite-group__icon" name="trash-bin" />
    <span class="favorite-group__label link link-button">
      Очистить избранное
    </span>
  </div>
</template>

<script lang="ts" setup>
import BIcon from '@/components/base/icon/BIcon.vue'
import { useFavoritesStore } from '@/stores/favorites'

const favorites = useFavoritesStore()
const clearFavorites = () => favorites.save([])
</script>

<style src="./favorite.css" scoped></style>
