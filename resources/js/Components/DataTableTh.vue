<template>
  <th :class="`whitespace-nowrap ${sortable && 'cursor-pointer'} ${$props.class}`" @click.prevent="table && table.sort(column)">
    <div class="flex items-center space-x-2 justify-center">
      <span class="uppercase">
        <slot />
      </span>

      <div v-if="sortable" class="flex flex-none flex-col">
        <Icon src="caret-up" class="w-2 h-2 fa-xs" :class="table && table.config.sort.key === column && table.config.sort.order === 'asc' ? 'text-slate-700' : 'text-slate-400'" />
        <Icon src="caret-down" class="w-2 h-2 fa-xs" :class="table && table.config.sort.key === column && table.config.sort.order === 'desc' ? 'text-slate-700' : 'text-slate-400'" />
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
      sortable: {
        type: Boolean,
        default: true,
      },
      table: {
        type: Object,
        default: null,
      },
      column: {
        type: String,
        default: new String,
      },
    },

    components: {
      Icon,
    },
  })
</script>