<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.CONTACTS.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <contact-first-screen />

    <b-map
      v-bind="CONTACTS_MAP"
      class="mb-60 2xl:mb-120 h-360 md:h-[418px] lg:h-[526px] xl:h-[599px] 2xl:h-[636px]"
    />
    <contact-requisites />
  </div>

  <suspense>
    <form-feedback />
  </suspense>
</template>

<script lang="ts" setup>
import { CONTACTS_MAP } from '@/config/contacts.config'
import BMap from '@/components/base/map/BMap.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import FormFeedback from '@/components/form/feedback/FormFeedback.vue'
import ContactFirstScreen from '@/pages/contacts/components/ContactFirstScreen.vue'
import ContactRequisites from '@/pages/contacts/components/ContactRequisites.vue'
</script>
<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style scoped></style>
