<template>
  <transition
    enter-active-class="transition ease-out duration-300 transform"
    enter-from-class="opacity-0 -translate-y-10"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="ease-in duration-200"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 -translate-y-10 translate-y-0"
  >
    <menu v-show="menuStore.status" ref="menu" class="menu">
      <div class="menu-content">
        <the-menu-links />
        <the-menu-contacts />
      </div>
      <div ref="overflow" class="menu-overflow" @click="menuStore.close"></div>
    </menu>
  </transition>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useMenuStore } from '@/stores/menu'
import { usePageContext } from '~/renderer/utils'
import { useScrollLock } from '@/composables/useScrollLock'
import TheMenuLinks from './TheMenuLinks.vue'
import TheMenuContacts from './TheMenuContacts.vue'

const menu = ref()
const overflow = ref()

const menuStore = useMenuStore()
const pageContext = usePageContext()

/**
 * Отслеживаем открытие/закрытие меню
 * Добавляем отступ подложке, скроллим в самый верх страницы
 */
watch(
  () => menuStore.status,
  (is) => {
    const padding = useScrollLock(is, menu)
    overflow.value.style.width = `calc(100vw - ${padding})`

    document.querySelector('body')?.scrollIntoView({
      behavior: 'smooth'
    })
  }
)

/**
 * Закрываем меню при смене на роута
 */
watch(
  () => pageContext.urlPathname,
  () => menuStore.close()
)
</script>

<style src="./styles/menu.css" scoped></style>
