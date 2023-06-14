<template>
  <header class="header" :class="{ 'header--home': isActiveLink('/') }">
    <div class="container">
      <the-header-logo :class="{ 'hide-logo': isSearchFormActive }" />
      <nav v-show="!isSearchFormActive" class="header__nav">
        <component
          :is="getTagByActiveLink(item.slug)"
          v-for="(item, idx) in HEADER_LINKS"
          :key="idx"
          :href="item.slug"
          class="nav-link"
          :class="{ active: isActiveLink(item.slug) }"
          >{{ item.title }}</component
        >
        <a class="nav-link phone" :href="phoneLinkFormatter(PHONE)">{{
          PHONE
        }}</a>
      </nav>
      <the-search @toggle-search-activity="isSearchFormActive = $event" />
      <a class="header__icon mr-12" :href="$ROUTE.FAVORITES.slug">
        <b-icon name="heart" />
      </a>
      <div class="header__icon" @click="menu.open">
        <b-icon name="menu" />
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import BIcon from '@/components/base/icon/BIcon.vue'
import TheHeaderLogo from './TheHeaderLogo.vue'
import TheSearch from './TheSearch.vue'
import { PHONE } from '@/config/main.config'
import { phoneLinkFormatter } from '@/utils'
import { useMenuStore } from '@/stores/menu'
import { useFavoritesStore } from '@/stores/favorites'
import { isActiveLink } from '~/renderer/utils'
import { getTagByActiveLink } from '@/utils/show'
import { ROUTE } from '@/routes'
import { onMounted, ref } from 'vue'

/**
 * Ссылки в шапке
 */
const HEADER_LINKS = [
  {
    title: ROUTE.ESTATE.title, // TODO: улучшить
    slug: ROUTE.ESTATE.slug + '/office'
  },
  ROUTE.RESEARCHES,
  ROUTE.SERVICES,
  ROUTE.ABOUT,
  ROUTE.CONTACTS
]

const menu = useMenuStore()
const favorites = useFavoritesStore()

const isSearchFormActive = ref<boolean>(isActiveLink(ROUTE.SEARCH.slug))

onMounted(() => {
  favorites.loadData()
})
</script>

<style lang="postcss">
:root {
  --h-header: 68px;
  --h-header-md: 94px;
  --h-header-xl: 106px;
}
</style>
<style lang="postcss" scoped>
.header {
  @apply h-[var(--h-header)] md:h-[var(--h-header-md)] xl:h-[var(--h-header-xl)];
  @apply flex bg-white select-none transition-background z-[900];
  box-shadow: 0px 4px 35px rgba(0, 0, 0, 0.05);

  :deep(.search-group) {
    @apply mr-10 md:ml-18 xl:ml-60;
  }

  .hide-logo {
    :deep(.logo) {
      @apply hidden md:block;
    }

    :deep(.slogan) {
      @apply hidden xl:block;
    }
  }

  .container {
    @apply flex items-center justify-between;
  }

  &__nav {
    @apply xl:flex gap-[clamp(10px,2vw,30px)] ml-auto mr-10 md:mr-20 xl:mr-[clamp(42px,2vw,62px)];

    .nav-link {
      @apply device:hidden hover:text-green transition;

      &.active {
        @apply text-black/60 cursor-default;
      }

      &.phone {
        @apply device:block md-only:text-base xl:ml-30;
      }
    }
  }

  &__icon {
    @apply cursor-pointer transition hover:text-green;

    svg {
      @apply mobile:w-24 mobile:h-24 md:w-36 md:h-36;
    }
  }
}

/* Стили для шапки на главной странице */
.header--home {
  @apply absolute inset-x-0 top-0 bg-white/5 text-white;
  backdrop-filter: blur(5px);
  box-shadow: none;

  :deep(.header-logo) {
    .logo {
      @apply bg-[url('/images/logo-white.png')];
    }
  }

  .header__hamburger {
    @apply text-white hover:text-green;
  }
}
</style>
