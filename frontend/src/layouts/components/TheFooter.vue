<template>
  <footer class="footer">
    <div class="container">
      <div class="footer__head">
        <component
          :is="getTagByActiveLink('/')"
          href="/"
          class="footer__head-logo"
          title="На главную"
        />
        <div class="footer__head-slogan t-caption" v-html="SLOGAN.footer"></div>
      </div>

      <div
        v-for="column in FOOTER_COLUMNS"
        :key="column.type"
        class="footer__column"
        :class="`footer__column--${column.type}`"
      >
        <component
          :is="getTagByActiveLink(column.slug)"
          :href="column.slug"
          class="footer__column-title text-base leading-140 lg:t-h4"
          :class="{
            'link link-white': column.slug && !isActiveLink(column.slug)
          }"
          >{{ column.title }}</component
        >

        <div class="footer__column-links t-p3">
          <div v-for="(item, idx) in column.items" :key="idx" class="item">
            <b-icon v-if="item.icon" :name="item.icon" />

            <span v-if="item.slug">
              <component
                :is="getTagByActiveLink(item.slug)"
                :href="item.slug"
                :class="{ 'link link-white': !isActiveLink(item.slug) }"
                >{{ item.title }}</component
              >
            </span>
            <span v-else v-html="item.title"></span>

            <span v-if="item.notice" class="notice" :class="item.notice.color">
              {{ item.notice.text }}</span
            >
          </div>
        </div>

        <div v-if="column.socials" class="footer__column-socials">
          <a
            v-for="(social, idx) in column.socials"
            :key="idx"
            :href="social.link"
          >
            <b-icon :name="social.icon" collection="socials" />
          </a>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="footer__bottom t-caption">
        <span class="copyright">&copy;&nbsp;{{ YEAR }}&nbsp;CORE.XP</span>
        <span class="privacy">
          <a
            :href="FOOTER_PRIVACY_POLICY.slug"
            target="_blank"
            class="link link-white"
            >{{ FOOTER_PRIVACY_POLICY.title }}</a
          >
        </span>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { SLOGAN } from '@/config/main.config'
import { getTagByActiveLink } from '@/utils/show'
import { FOOTER_COLUMNS, FOOTER_PRIVACY_POLICY } from '@/config/footer.config'
import BIcon from '@/components/base/icon/BIcon.vue'
import { isActiveLink } from '~/renderer/utils'

const YEAR = new Date().getFullYear()
</script>

<style lang="postcss">
:root {
  --mt-footer: 60px;
  --h-footer-md: 330px;
  --h-footer-lg: 500px;
}
</style>
<style lang="postcss" scoped>
.footer {
  @apply mt-[var(--mt-footer)] bg-black text-white
    h-auto md:h-[var(--h-footer-md)] lg:h-[var(--h-footer-lg)] 
    pt-40 lg:pt-60;

  .container {
    @apply relative flex mobile:flex-col mobile:gap-32;
  }

  &__head {
    @apply md:flex-none md:mr-50 md-only:w-[202px] lg:mr-[140px] lg:mt-4;

    &-logo {
      @apply block w-120 h-20 bg-[url('/images/logo-white.png')] bg-contain md:mb-20;
    }

    &-slogan {
      @apply hidden lg:block lg:w-[194px];
    }
  }

  &__column {
    &-title {
      @apply inline-block mb-16 md:mb-10 lg:mb-22;
    }

    &-links {
      @apply flex flex-col gap-12 lg:gap-22;

      .item {
        @apply flex gap-10 lg:gap-8;

        svg {
          @apply relative top-1 flex-none device:w-16 device:h-16;
        }

        .notice {
          @apply lg:ml-4 h-max;

          &.green {
            @apply text-[8px] md:text-10 leading-130 bg-green text-white px-10 py-2 rounded-full;
          }
        }
      }
    }

    &--contacts {
      @apply md:w-[202px] lg:w-[285px] lg:mr-50;

      @screen md-only {
        @apply absolute top-54 left-[theme('container.padding.md')];
      }

      .footer__column-socials {
        @apply flex items-center mt-16 md:mt-12 lg:mt-0;
        @apply lg:absolute lg:top-[130px]
          lg:left-[theme('container.padding.lg')]
          xl:left-[theme('container.padding.xl')]
          2xl:left-[theme('container.padding.2xl')];

        @media screen and (min-width: 1320px) {
          left: 60px;
        }

        a {
          @apply h-22 md:h-16 lg:h-auto;
          @apply mr-12 last:mr-0;

          svg {
            @apply device:w-full device:h-full;
          }
        }
      }

      .footer__column-title {
        @apply md-only:hidden;
      }
    }

    &--services {
      @apply md:w-[231px] lg:w-[302px] md:mr-[21px] lg:mr-[33px];
    }

    &--sections {
      @apply md:w-160 lg:w-[200px];
    }
  }

  &__bottom {
    @apply w-full lg:w-[1195px]
      mt-36 md:mt-[55px] lg:mt-60 
      flex md:justify-between md-only:flex-row-reverse
      mobile:flex-col-reverse mobile:gap-12 mobile:mb-30;

    .copyright {
      @apply opacity-60;
    }
  }
}
</style>
