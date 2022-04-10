<template>
  <div class="flex flex-col space-y-2 bg-white border border-slate-300 rounded-md shadow-xl">
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
  })
</script>