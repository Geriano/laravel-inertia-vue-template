<template>
  <button
    :draggable="true"
    @dragstart="start($event, menu)"
    @dragend="end"
    @dragover.prevent
    @dragenter.prevent
    @dragleave.prevent
    @drop="drop($event, menu)"
    :class="drag && 'bg-slate-100'"
    class="flex items-center bg-slate-200 border border-slate-300 rounded rounded-r-none border-r-0 p-2 uppercase text-left">
    <div :draggable="false" class="flex-wrap w-full">{{ menu.position }}  - {{ __(menu.name) }}</div>

    <div :draggable="false" class="flex-none flex items-center space-x-1">
      <Link v-if="menu.parent_id" :href="route('superuser.menu.remove-parent', menu.id)" :draggable="false" method="patch" as="button" class="border border-slate-300 rounded shadow w-6 h-6 text-center">
        <Icon src="caret-left" class="text-slate-700 fa-xs w-full h-full" />
      </Link>

      <Link v-if="menu.position > 1" :href="route('superuser.menu.set-parent', menu.id)" :draggable="false" method="patch" as="button" class="border border-slate-300 rounded shadow w-6 h-6 text-center">
        <Icon src="caret-right" class="text-slate-700 fa-xs w-full h-full" />
      </Link>

      <button v-if="minimize" @click.prevent="$emit('toggle')" :draggable="false" class="border border-slate-300 rounded shadow w-6 h-6 text-center">
        <Icon :src="open ? 'minus' : 'plus'" class="text-slate-700 fa-xs w-full h-full" />
      </button>

      <Link :href="route('superuser.menu.edit', menu.id)" :draggable="false" class="bg-blue-600 text-slate-200 border border-blue-700 rounded shadow w-6 h-6 text-center">
        <Icon src="pen" class="text-white fa-xs w-full h-full" />
      </Link>

      <button v-if="menu.deleteable" @click.prevent="destroy(menu)" class="bg-red-600 text-slate-200 border border-red-700 rounded shadow w-6 h-6 text-center">
        <Icon src="trash" class="text-white fa-xs w-full h-full" />
      </button>
    </div>
  </button>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link, useForm } from '@inertiajs/inertia-vue3'
  import { Inertia } from '@inertiajs/inertia'
  import Icon from '@/Components/Icon'
  import Swal from 'sweetalert2'

  export default defineComponent({
    props: {
      menus: Array,
      menu: Object,
      minimize: {
        type: Boolean,
        default: false,
      },
      open: {
        type: Boolean,
        default: true,
      },
    },

    components: {
      Icon,
      Link,
    },

    methods: {
      destroy(menu) {
        return Swal.fire({
          text: __('are you sure') + '?',
          icon: 'question',
          showCancelButton: true,
        }).then(response => {
          if (response.isConfirmed) {
            return Inertia.delete(route('superuser.menu.destroy', menu.id))
          }
        })
      },

      start(e, menu) {
        e.dataTransfer.setData('id', menu.id)
      },

      end(e) {
        this.drag = {}
      },

      drop(e, menu) {
        const drag = this.menus.find(menu => Number(menu.id) === Number(e.dataTransfer.getData('id')))

        if (!drag) return;
        if (drag.id === menu.id) return;
        if (drag.parent_id !== menu.parent_id) return;
        
        return useForm({
          left: drag.id,
          right: menu.id,
        }).patch(route('superuser.menu.swap'))
      },
    },
  })
</script>