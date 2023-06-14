<template>
  <the-header />
  <the-menu />
  <main>
    <slot />
    <the-more-page />
  </main>
  <the-footer />
</template>

<script lang="ts" setup>
import TheHeader from './components/TheHeader.vue'
import TheMenu from './components/menu/TheMenu.vue'
import TheFooter from './components/TheFooter.vue'
import TheMorePage from './components/TheMorePage.vue'
</script>
