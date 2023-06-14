<template>
  <div class="contacts">
    <h1 class="ttl mb-20 md:mb-30 uppercase">Контакты</h1>

    <div class="contacts__info">
      <b-icon name="phone" />
      <a :href="phoneLinkFormatter(CONTACTS_PHONE)" class="t-p1">{{
        CONTACTS_PHONE
      }}</a>
    </div>

    <div class="contacts__info">
      <b-icon name="mail" />
      <a :href="emailLinkFormatter(CONTACTS_EMAIL)" class="t-p1">{{
        CONTACTS_EMAIL
      }}</a>
    </div>

    <div class="contacts__info md:mr-[15%]">
      <b-icon name="location-fill" />
      <div class="t-p1" v-html="CONTACTS_ADDRESS"></div>
    </div>

    <div class="contacts__info">
      <b-icon name="clock" />
      <div class="t-p1" v-html="CONTACTS_WORK_TIME"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {
  CONTACTS_PHONE,
  CONTACTS_EMAIL,
  CONTACTS_ADDRESS,
  CONTACTS_WORK_TIME
} from '@/config/contacts.config'
import { phoneLinkFormatter, emailLinkFormatter } from '@/utils'
import BIcon from '@/components/base/icon/BIcon.vue'
</script>

<style scoped lang="postcss">
.contacts {
  @apply mb-40 md:mb-60 2xl:mb-100;

  &__info {
    @apply flex gap-12 md:gap-14 2xl:gap-20 mb-16 md:mb-20 2xl:mb-30;

    svg {
      @apply flex-none fill-green
        w-18 h-[22px]
        md:w-24 md:h-[28px]
        2xl:w-36 2xl:h-[45px];
    }
  }
}
</style>
