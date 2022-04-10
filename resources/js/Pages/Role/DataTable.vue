<template>
  <div class="bg-white border rounded-md shadow-xl p-4">
    <div class="flex items-center justify-end mb-2">
      <input v-model="search" type="text" name="search" class="bg-white text-xs border border-slate-200 rounded w-full sm:w-1/3 placeholder:capitalize" :placeholder="__('search something')">
    </div>

    <div class="overflow-auto border border-slate-200 rounded">
      <table class="border-collapse w-full">
        <thead class="bg-slate-200">
          <tr>
            <th class="border border-slate-200 px-3 py-2 uppercase">{{ __('no') }}</th>
            <th class="border border-slate-200 px-3 py-2 uppercase">{{ __('name') }}</th>
            <th class="border border-slate-200 px-3 py-2 uppercase">{{ __('permissions') }}</th>
            <th class="border border-slate-200 px-3 py-2 uppercase">{{ __('action') }}</th>
          </tr>
        </thead>

        <tbody class="bg-white">
          <tr v-for="(role, i) in roles.filter(role => search.trim() ? (role.name.includes(search.trim()) || role.permissions.find(permission => permission.name.includes(search.trim()))) : true)" :key="i" class="hover:bg-slate-100">
            <td class="border border-slate-200 px-3 py-1 text-center">{{ i + 1 }}</td>
            <td class="border border-slate-200 px-3 py-1 uppercase">{{ __(role.name) }}</td>
            <td class="border border-slate-200 px-3 py-1">
              <template v-if="role.permissions.length">
                <button v-for="(permission, j) in role.permissions" :key="j" class="px-2 py-1 text-xs text-white bg-slate-600 border border-slate-700 rounded m-1 uppercase cursor-default">
                  <div class="flex items-center space-x-2">
                    <span>{{ __(permission.name) }}</span>
                    <Icon @click.prevent="detach(role, permission)" src="times" class="px-1 hover:bg-red-600 rounded cursor-pointer" />
                  </div>
                </button>
              </template>
            </td>
            <td class="border border-inherit px-3 py-1">
              <div class="flex-wrap">
                <Link :href="route('superuser.role.edit', role.id)" as="button" class="bg-blue-600 border border-blue-700 rounded shadow px-3 py-1 m-[1px] text-xs uppercase">
                  <div class="flex items-center space-x-2">
                    <Icon src="pen" class="text-white flex-none w-3 h-3" />

                    <div class="flex-wrap pr-1">
                      <span class="text-white font-semibold">{{ __('edit') }}</span>
                    </div>
                  </div>
                </Link>

                <button @click.prevent="destroy(role)" class="bg-red-600 border border-red-700 rounded shadow px-3 py-1 m-[1px] text-xs uppercase">
                  <div class="flex items-center space-x-2">
                    <Icon src="trash" class="text-white flex-none w-3 h-3" />

                    <div class="flex-wrap pr-1">
                      <span class="text-white font-semibold">{{ __('delete') }}</span>
                    </div>
                  </div>
                </button>
              </div>
            </td>
          </tr>
        </tbody>

        <tfoot class="bg-slate-200">
          <tr class="text-sm">
            <th class="border border-slate-200 px-3 py-1 uppercase">{{ __('no') }}</th>
            <th class="border border-slate-200 px-3 py-1 uppercase">{{ __('name') }}</th>
            <th class="border border-slate-200 px-3 py-1 uppercase">{{ __('permissions') }}</th>
            <th class="border border-slate-200 px-3 py-1 uppercase">{{ __('action') }}</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link, useForm } from '@inertiajs/inertia-vue3'
  import { Inertia } from '@inertiajs/inertia'
  import Swal from 'sweetalert2'
  import Icon from '@/Components/Icon'

  export default defineComponent({
    props: {
      roles: Array,
    },

    components: {
      Link,
      Icon,
    },

    data() {
      return {
        search: new String,
      }
    },

    methods: {
      destroy(role) {
        return Swal.fire({
          text: __('are you sure want to delete') + '?',
          icon: 'question',
          showCancelButton: true,
        }).then(response => {
          if (response.isConfirmed) {
            return Inertia.delete(route('superuser.role.destroy', { id: role.id }))
          }
        })
      },

      detach(role, permission) {
        return Swal.fire({
          text: __('are you sure want to detach permission') + ` "${__(permission.name)}"?`,
          icon: 'question',
          showCancelButton: true,
        }).then(response => {
          if (response.isConfirmed) {
            return useForm({permission: permission.id}).patch(route('superuser.role.detach', role.id))
          }
        })
      },
    },
  })
</script>