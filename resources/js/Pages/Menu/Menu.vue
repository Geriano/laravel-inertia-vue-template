<template>
  <div class="flex items-center bg-slate-200 border border-slate-300 rounded mb-1 p-2 uppercase">
    <div class="flex-wrap w-full">{{ __(menu.name) }}</div>

    <div class="flex-none flex items-center space-x-2">
      <Link :href="route('superuser.menu.edit', menu.id)" class="bg-blue-600 text-slate-200 border border-blue-700 rounded shadow p-1 w-6 h-6">
        <Icon src="pen" />
      </Link>
    </div>
  </div>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link } from '@inertiajs/inertia-vue3'
  import Icon from '@/Components/Icon'

  export default defineComponent({
    props: {
      menu: Object,
    },

    components: {
      Icon,
      Link,
    },
  })
</script>