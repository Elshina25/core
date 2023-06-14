<template>
  <div class="person" :class="`person-${variant}`">
    <img
      v-if="person.image"
      class="person__photo"
      :src="getFullUrl(person.image)"
    />
    <div v-else class="person__photo"></div>

    <div>
      <div class="person__name">{{ person.name }}</div>
      <div v-if="person.jobTitle" class="person__position">
        {{ person.jobTitle }}
      </div>

      <b-dropdown
        v-if="contacts.length"
        label="Связаться"
        :items="contacts"
        class="mt-8 md:mt-12"
      >
        <template #default="{ item }">
          <a v-if="!item.isTextLink" :href="item.value" target="_blank">
            <b-dropdown-item
              :text="item.label"
              :icon="item.icon"
              :collection="item.collection"
            />
          </a>

          <b-dropdown-item
            v-else
            :icon="item.icon"
            :collection="item.collection"
          >
            <a class="transition-none" :href="item.value">{{ item.label }}</a>
          </b-dropdown-item>
        </template>
      </b-dropdown>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { IPerson } from '@/types'
import { getFullUrl } from '@/utils'
import { getPersonContacts } from '@/utils/show'
import BDropdown from '@/components/base/dropdown/BDropdown.vue'
import BDropdownItem from '@/components/base/dropdown/BDropdownItem.vue'

const props = withDefaults(
  defineProps<{
    variant?: 'object' | 'research' | 'project' | 'story' | 'vacancy'
    person: IPerson
  }>(),
  {
    variant: 'object'
  }
)

const contacts = computed(() => getPersonContacts(props.person))
</script>

<style src="./person.css" scoped></style>
