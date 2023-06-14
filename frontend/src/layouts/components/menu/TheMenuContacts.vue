<template>
  <div class="menu-contacts">
    <div class="contacts">
      <component
        :is="getTagByActiveLink('/')"
        href="/"
        class="contacts__head"
        title="На главную"
      >
        <div class="logo"></div>
        <div class="t-caption" v-html="SLOGAN.menu"></div>
      </component>

      <div class="contacts__list">
        <span>
          <a :href="phoneLinkFormatter(PHONE)" class="link link-white">{{
            PHONE
          }}</a>
        </span>
        <span>
          <a :href="emailLinkFormatter(EMAIL)" class="link link-white">{{
            EMAIL
          }}</a>
        </span>
        <span class="address" v-html="ADDRESS"></span>
      </div>

      <div class="contacts__socials">
        <a v-for="(social, idx) in SOCIALS" :key="idx" :href="social.link">
          <b-icon :name="social.icon" collection="socials" />
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { EMAIL, PHONE, SOCIALS, ADDRESS } from '@/config/main.config'
import { emailLinkFormatter, phoneLinkFormatter } from '@/utils'
import { getTagByActiveLink } from '@/utils/show'
import { SLOGAN } from '@/config/main.config'
import BIcon from '@/components/base/icon/BIcon.vue'
</script>

<style src="./styles/menu-contacts.css" scoped></style>
