<template>
  <DashboardLayout title="Menu">
    <div class="flex flex-col sm:flex-row w-full space-y-2 sm:space-y-0 sm:space-x-2">
      <div class="flex-none flex-wrap w-full sm:w-1/4">
        <h3 class="text-xl font-semibold first-letter:capitalize lowercase">{{ __('create new menu') }}</h3>
      </div>

      <form @submit.prevent="submit" class="w-full bg-slate-100 border border-slate-200 rounded-md shadow-xl">
        <div class="flex flex-col space-y-2 p-4">
          <label for="name" class="first-letter:capitalize lowercase text-sm">{{ __('name') }}</label>

          <input v-model="form.name" type="text" name="name" class="w-full sm:w-2/3 bg-white text-sm border border-slate-300 rounded-md shadow placeholder:capitalize" :placeholder="__('menu name')" autofocus>

          <label for="route" class="first-letter:capitalize lowercase text-sm">{{ __('route') }} {{ __('or') }} {{ __('url') }}</label>

          <div class="w-full sm:w-2/3">
            <Multiselect 
              v-model="form.route_or_url"
              :options="routes"
              :clearOnSearch="false"
              :clearOnSelect="false"
              :searchable="true"
              :createTag="true"
              class="uppercase placeholder:capitalize" 
              :placeholder="__('choose route or write url')" />

            <ul class="flex-wrap text-xs text-slate-400 mt-2 list-disc list-inside">
              <li class="first-letter:capitalize lowercase">{{ __('choose a route name or write your own url if the route doesn\'t have a name / the route leads to an external link') }}</li>
            </ul>
          </div>

          <label for="routes" class="first-letter:capitalize lowercase text-sm">{{ __('active') }}</label>

          <div class="w-full sm:w-2/3">
            <Multiselect
              v-model="form.routes"
              :options="routes"
              :clearOnSearch="false"
              :clearOnSelect="false"
              :searchable="true"
              :createTag="true"
              mode="tags"
              class="uppercase placeholder:capitalize" 
              :placeholder="__('select routes')" />

            <ul class="flex-wrap text-xs text-slate-400 mt-2 list-disc list-inside">
              <li class="first-letter:capitalize lowercase">{{ __('choose the route to bookmark the page is now part of the menu to be created') }}</li>
              <li class="first-letter:capitalize lowercase">{{ __('will not work if the route is not registered in the app') }}</li>
            </ul>
          </div>

          <label for="permissions" class="first-letter:capitalize lowercase text-sm">{{ __('permissions') }}</label>

          <div class="w-full sm:w-2/3">
            <Multiselect
              v-model="form.permissions"
              :options="permissions.map(permission => ({
                value: permission.id,
                label: permission.name,
              }))"
              :clearOnSearch="false"
              :clearOnSelect="false"
              :searchable="true"
              :createTag="true"
              mode="tags"
              class="uppercase placeholder:capitalize" 
              :placeholder="__('select permissions')" />
            
            
            <ul class="flex-wrap text-xs text-slate-400 mt-2 list-disc list-inside">
              <li class="first-letter:capitalize lowercase">{{ __('if the user has the selected permission then the menu will appear') }}</li>
            </ul>
          </div>
        </div>

        <div class="flex items-center justify-end bg-slate-200 px-4 py-2 text-xs">
          <button class="bg-slate-700 text-slate-200 border border-slate-800 rounded-md shadow px-3 py-2 uppercase font-bold">
            {{ __('create') }}
          </button>
        </div>
      </form>
    </div>

    <DataTable :menus="menus" />
  </DashboardLayout>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  import { Link, useForm } from '@inertiajs/inertia-vue3'
  import Multiselect from '@vueform/multiselect'
  import DashboardLayout from '@/Layouts/DashboardLayout'
  import Icon from '@/Components/Icon'
  import DataTable from './DataTable'

  export default defineComponent({
    props: {
      menus: Array,
      routes: Array,
      permissions: Array,
    },

    components: {
      DashboardLayout,
      Icon,
      Link,
      Multiselect,
      DataTable,
    },

    data() {
      return {
        form: useForm({
          name: new String,
          icon: new String,
          route_or_url: new String,
          routes: [],
          permissions: [],
        }),
      }
    },

    methods: {
      submit() {
        return this.form.post(route('superuser.menu.store'), {
          onSuccess: () => this.form.reset(),
        })
      },
    },
  })
</script>

<style src="@vueform/multiselect/themes/default.css"></style>