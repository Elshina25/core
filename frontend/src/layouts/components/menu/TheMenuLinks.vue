<template>
  <div class="menu-links">
    <b-icon name="menu-close" class="close" @click="menu.close" />

    <div class="menu-links-head">
      <the-header-logo />
    </div>

    <div class="menu-links-body">
      <div
        v-for="(column, idx) in MENU_COLUMNS"
        :key="idx"
        class="column"
        :class="`column--${column.type}`"
      >
        <div
          v-for="section in column.sections"
          :key="section.slug"
          class="column__section"
          :class="{ active: isOpen(idx) }"
        >
          <span class="toggle" @click="toggle(idx)">
            <component
              :is="getTagByActiveLink(section.slug)"
              :class="{ link: !isActiveLink(section.slug) }"
              :href="section.slug"
              class="column__section-title"
              >{{ section.title }}</component
            >

            <b-icon
              v-if="section.items.length"
              name="arrow-down"
              class="w-16 h-16 md:hidden transition"
            />
          </span>

          <div v-if="section.items.length" class="column__section-links">
            <span v-for="item in section.items" :key="item.slug">
              <component
                :is="getTagByActiveLink(item.slug)"
                :class="{ link: !isActiveLink(item.slug) }"
                :href="item.slug"
                >{{ item.title }}</component
              >
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Ref, ref } from 'vue'
import { MENU_COLUMNS } from '@/config/menu.config'
import { useMenuStore } from '@/stores/menu'
import { isActiveLink } from '~/renderer/utils'
import { getTagByActiveLink } from '@/utils/show'
import pull from 'lodash-es/pull'
import BIcon from '@/components/base/icon/BIcon.vue'
import TheHeaderLogo from '@/layouts/components/TheHeaderLogo.vue'

const menu = useMenuStore()

// Collapse sections
const opened: Ref<number[]> = ref([])

/**
 * Раскрыт ли список
 * @param idx
 */
const isOpen = (idx: number) => opened.value.includes(idx)

/**
 * Открытие/закрытие списка
 * @param id
 */
const toggle = (id: number) => {
  return !isOpen(id) ? opened.value.push(id) : pull(opened.value, id)
}
</script>

<style src="./styles/menu-links.css" scoped></style>
