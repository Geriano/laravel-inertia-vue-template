<template>
  <DashboardLayout title="Menu">
    <div class="flex flex-col sm:flex-row w-full space-y-2 sm:space-y-0 sm:space-x-2">
      <div class="flex-none flex-wrap w-full sm:w-1/4">
        <h3 class="text-xl font-semibold first-letter:capitalize lowercase">{{ __('create new menu') }}</h3>
      </div>

      <form @submit.prevent="submit" class="w-full bg-white border border-slate-200 rounded-md shadow-xl">
        <div class="flex flex-col space-y-2 p-4">
          <label for="name" class="first-letter:capitalize lowercase text-sm">{{ __('name') }}</label>

          <input v-model="form.name" type="text" name="name" class="w-full sm:w-2/3 bg-white text-sm border border-slate-300 rounded-md shadow placeholder:capitalize" :placeholder="__('menu name')" autofocus>

          <transition name="fade">
            <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
          </transition>

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

            <transition name="fade">
              <p v-if="form.errors.route_or_url" class="text-xs text-red-500">{{ form.errors.route_or_url }}</p>
            </transition>

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
              :createTag="false"
              mode="tags"
              class="uppercase placeholder:capitalize" 
              :placeholder="__('select routes')" />


            <transition name="fade">
              <p v-if="form.errors.routes" class="text-xs text-red-500">{{ form.errors.routes }}</p>
            </transition>

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
              :createTag="false"
              mode="tags"
              class="uppercase placeholder:capitalize" 
              :placeholder="__('select permissions')" />
            
            
            <transition name="fade">
              <p v-if="form.errors.permissions" class="text-xs text-red-500">{{ form.errors.permissions }}</p>
            </transition>
            
            <ul class="flex-wrap text-xs text-slate-400 mt-2 list-disc list-inside">
              <li class="first-letter:capitalize lowercase">{{ __('if the user has the selected permission then the menu will appear') }}</li>
            </ul>
          </div>

          <div class="w-full sm:w-2/3">
            <p for="icon" class="lowercase first-letter:capitalize">{{ __('icon') }}</p>

            <div class="flex items-center justify-between space-x-2">
              <div v-if="form.icon && typeof form.icon === 'string'" class="flex-none w-12 p-2">
                <Icon :src="form.icon" class="text-slate-700 fa-xl" />
              </div>

              <button @click.prevent="show = ! show" class="bg-blue-600 rounded-md px-3 py-1 uppercase text-white text-sm font-semibold">change</button>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end bg-slate-200 px-4 py-2 space-x-2 text-xs">
          <button class="bg-slate-700 text-slate-200 border border-slate-800 rounded-md shadow px-3 py-2 uppercase font-bold">
            {{ __('create') }}
          </button>
        </div>
      </form>
    </div>

    <transition name="fade">
      <DataTable v-if="menus.length" :menus="menus" />
    </transition>

    <transition name="fade">
      <IconPicker v-if="show" @change="form.icon = $event; show = false" @close="show = false" />
    </transition>
  </DashboardLayout>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link, useForm } from '@inertiajs/inertia-vue3'
  import Multiselect from '@vueform/multiselect'
  import DashboardLayout from '@/Layouts/DashboardLayout'
  import Icon from '@/Components/Icon'
  import DataTable from './DataTable'
  import IconPicker from './IconPicker'

  export default defineComponent({
    props: {
      icons: Array,
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
      IconPicker,
    },

    data() {
      return {
        form: useForm({
          name: new String,
          icon: 'circle',
          route_or_url: new String,
          routes: [],
          permissions: [],
        }),

        show: false,
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