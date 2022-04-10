<template>
  <DashboardLayout title="Permissions">
    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full p-2">
      <div class="flex-none w-full sm:w-1/4">
        <h1 class="first-letter:capitalize lowercase text-xl text-slate-800 font-semibold">{{ __('create new permission') }}</h1>
      </div>

      <form @submit.prevent="submit" class="flex flex-col space-y-2 w-full sm:w-3/4 bg-white border rounded-md shadow-md">
        <div class="flex flex-col space-y-2 p-2">
          <label for="name" class="first-letter:capitalize lowercase">{{ __('permission name') }}</label>

          <input ref="name" v-model="form.name" type="text" class="bg-transparent border rounded-md shadow text-sm w-2/3 placeholder:capitalize" :placeholder="__('permission name')" autofocus>

          <div class="text-xs">{{ form.errors.name }}</div>
        </div>

        <div class="flex items-center justify-end bg-slate-200 p-2">
          <button class="px-3 py-1 bg-slate-800 text-slate-100 text-sm border border-slate-700 rounded-md shadow-md uppercase">
            {{ __(form.edit ? 'edit' : 'create') }}
          </button>
        </div>
      </form>
    </div>

     <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full p-2">
      <div class="flex-none w-full sm:w-1/4">
        <h1 class="first-letter:capitalize lowercase text-xl text-slate-800 font-semibold">{{ __('all permission') }}</h1>
      </div>

      <div class="flex flex-col space-y-2 w-full sm:w-3/4 bg-white border rounded-md shadow-md">
        <div class="flex items-center justify-end w-full bg-slate-200 px-4 py-2">
          <input v-model="search" class="w-full sm:w-2/3 bg-white border border-slate-300 rounded-md px-3 py-1 text-sm placeholder:capitalize" :placeholder="__('search something')">
        </div>

        <div class="flex-wrap p-4">
          <button v-for="(permission, i) in permissions.filter(permission => search.trim() ? permission.name.includes(search.trim()) : true)" :key="i" class="border rounded-md shadow px-2 py-1 m-1 cursor-default">
            <div class="flex items-center w-full space-x-2">
              <span class="uppercase text-sm">{{ __(permission.name) }}</span>
              <Icon @click.prevent="destroy(permission)" src="times" class="rounded-md bg-slate-700 text-white px-1 hover:bg-red-500" />
            </div>
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
  import { defineComponent } from "vue";
  import { Link, useForm } from '@inertiajs/inertia-vue3'
  import { Inertia } from "@inertiajs/inertia";
  import DashboardLayout from '@/Layouts/DashboardLayout'
  import Icon from '@/Components/Icon'
  import Swal from "sweetalert2";

  export default defineComponent({
    props: {
      permissions: {
        type: Array,
        required: true
      }
    },

    components: {
      DashboardLayout,
      Link,
      Icon,
    },

    data() {
      return {
        search: new String,
        form: useForm({
          edit: false,
          name: new String,
        }),
      }
    },

    methods: {
      submit() {
        this.form.post(route('superuser.permission.store'), {
          onSuccess: () => {
            this.form.reset()
          },
        })
      },

      destroy(permission) {
        return Swal.fire({
          icon: 'question',
          text: __(`are you sure wan't to delete permission`) + ' ' + __(`"${permission.name}"`) + '?',
          showCancelButton: true,
        }).then(response => {
          if (response.isConfirmed) {
            return Inertia.delete(route('superuser.permission.destroy', permission.id))
          }
        })
      },
    },

    mounted() {
      this.$refs.name?.focus()
    },
  })
</script>