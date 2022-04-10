<template>
  <DashboardLayout title="Role">
    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 p-2">
      <div class="flex-none flex-wrap w-full sm:w-1/4">
        <h3 class="text-xl font-semibold first-letter:capitalize lowercase">{{ __('create new role') }}</h3>
      </div>

      <form @submit.prevent="submit" class="w-full bg-white border border-slate-200 rounded-md shadow-xl">
        <div class="flex flex-col space-y-2 p-4">
          <label for="name" class="capitalize">{{ __('role name') }}</label>

          <input v-model="form.name" type="text" name="name" :class="form.errors.name && 'border-red-500'" class="bg-white border border-slate-300 rounded w-full sm:w-2/3 py-2 placeholder:capitalize" :placeholder="__('role name')">
        </div>

        <div class="w-full sm:w-2/3 pl-4 pr-1 mb-2">
          <Multiselect
            v-model="form.permissions" 
            :options="permissions.map(permission => ({
              value: permission.id,
              label: __(permission.name),
            }))" 
            :clearOnSearch="false"
            :clearOnSelect="false"
            :searchable="true"
            :createTag="true"
            mode="tags"
            class="uppercase placeholder:capitalize" 
            :placeholder="__('select permissions')" />

          <div v-if="form.errors.permissions" class="text-right text-xs text-slate-500">
            {{ form.errors.permissions }}
          </div>
        </div>

        <div class="flex items-center justify-end w-full bg-slate-200 p-2 text-sm">
          <button class="bg-slate-700 border border-slate-800 text-white rounded-md shadow px-3 py-1 uppercase">{{ __('create') }}</button>
        </div>
      </form>
    </div>

    <DataTable :roles="roles" />
  </DashboardLayout>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  import { Link, useForm } from '@inertiajs/inertia-vue3'
  import Swal from 'sweetalert2'
  import Multiselect from '@vueform/multiselect'
  import DashboardLayout from '@/Layouts/DashboardLayout'
  import Icon from '@/Components/Icon'
  import DataTable from './DataTable'

  export default defineComponent({
    props: {
      permissions: Array,
      roles: Array,
    },

    components: {
      DashboardLayout,
      Multiselect,
      Link,
      Icon,
      DataTable,
    },

    data() {
      return {
        form: useForm({
          name: new String,
          permissions: [],
        }),
      }
    },
    
    methods: {
      submit() {
        this.form.post(route('superuser.role.store'), {
          onSuccess: () => this.form.reset(),
        })
      },
    },
  })
</script>

<style src="@vueform/multiselect/themes/default.css"></style>