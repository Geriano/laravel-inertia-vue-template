<template>
  <th :class="`${sortable && 'cursor-pointer'} ${$props.class}`">
    <div class="flex items-center space-x-2 justify-center">
      <span class="uppercase">
        <slot />
      </span>

      <div v-if="sortable" class="flex flex-none flex-col">
        <Icon src="caret-up" class="w-2 h-2 fa-xs" :class="sort === 'asc' ? 'text-slate-700' : 'text-slate-400'" />
        <Icon src="caret-down" class="w-2 h-2 fa-xs" :class="sort === 'desc' ? 'text-slate-700' : 'text-slate-400'" />
      </div>
    </div>
  </th>
</template>

<script>
  import { defineComponent } from 'vue'
  import Icon from '@/Components/Icon'

  export default defineComponent({
    props: {
      class: {
        type: String,
        default: new String,
      },
      sort: {
        type: String,
        default: false,
      },
      sortable: {
        type: Boolean,
        default: true,
      },
    },

    components: {
      Icon,
    },
  })
</script>