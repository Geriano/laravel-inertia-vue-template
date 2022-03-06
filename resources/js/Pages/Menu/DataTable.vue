<template>
  <div class="flex flex-col space-y-2 bg-slate-100 border border-slate-300 rounded-md shadow-xl">
    <div class="flex-none flex items-center justify-end space-x-2 bg-slate-200 py-2 px-4">
      <input v-model="search" type="text" class="w-full sm:w-1/3 bg-slate-100 text-xs border border-slate-300 rounded-md shadow placeholder:capitalize" :placeholder="__('search something')">
    </div>

    <div class="w-full sm:w-2/3 p-4">
      <Builder :menus="menus" />
    </div>
  </div>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  import { Link } from '@inertiajs/inertia-vue3'
  import Icon from '@/Components/Icon'
  import Builder from './Builder'

  export default defineComponent({
    props: {
      menus: Array,
    },

    components: {
      Link,
      Icon,
      Builder,
    },

    data() {
      return {
        search: new String,
      }
    },
  })
</script>