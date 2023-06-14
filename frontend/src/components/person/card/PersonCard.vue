<template>
  <div class="person">
    <img class="person__image" :src="person.image" />
    <div class="person__info">
      <div>
        <h4 class="person__info-title">{{ person.name }}</h4>
        <div class="person__info-job">
          {{ person.jobTitle }}
        </div>
      </div>
      <b-dropdown
        label="Связаться"
        :items="contacts"
        class="person__info-contacts"
      >
        <template #default="{ item }">
          <b-dropdown-item :icon="item.icon" :collection="item.collection">
            <a class="transition-none" :href="item.value">{{ item.label }}</a>
          </b-dropdown-item>
        </template>
      </b-dropdown>
      <div class="person__info-links">
        <a
          v-for="(contact, idx) in contacts"
          :key="idx"
          :href="contact.value"
          class="link"
        >
          {{ contact.label }}
        </a>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import BDropdown from '@/components/base/dropdown/BDropdown.vue'
import BDropdownItem from '@/components/base/dropdown/BDropdownItem.vue'

import type { IPerson } from '@/types'
import { getPersonContacts } from '@/utils/show'
import { computed } from 'vue'

const props = defineProps<{
  person: IPerson
}>()

const contacts = computed(() => getPersonContacts(props.person))
</script>

<style src="./card.css" lang="postcss" scoped></style>
