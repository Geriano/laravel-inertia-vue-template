<template>
  <div class="flex flex-col w-full h-full space-y-2 bg-slate-100 border rounded-md shadow-md p-2">
    <div class="flex items-center w-full space-x-2 text-sm">
      <div class="w-1/3">
        <Link v-if="route().current('user.create') === false" :href="route('superuser.user.create')" class="bg-green-500 border border-green-600 rounded-md px-3 py-1 uppercase">
          {{ __('create') }}
        </Link>
      </div>
      <div class="flex items-center space-x-2 w-2/3">
        <div class="flex-none flex items-center space-x-4">
          <label for="per_page">{{ __('per page') }}</label>

          <select ref="perPage" @change.prevent="updateUserData" :value="perPage" name="per_page" class="bg-transparent border rounded-md text-xs">
            <option value="2">2 </option>
            <option value="10">10</option>
          </select>
        </div>

        <input ref="search" @input.prevent="updateUserData" :value="search" type="text" class="w-full bg-transparent border rounded-md text-xs placeholder:capitalize" autofocus :placeholder="__('search something')">
      </div>
    </div>

    <div class="w-full overflow-auto h-full bg-inherit border rounded-md">
      <table class="w-full h-full min-h-fit border-collapse border rounded-md shadow-md bg-inherit">
        <thead class="uppercase text-center sm:sticky top-0 bg-inherit z-10">
          <tr>
            <th class="border px-3 py-2">{{ __('no') }}</th>
            <th class="border px-3 py-2">{{ __('profile') }}</th>
            <th class="border px-3 py-2">{{ __('name') }}</th>
            <th class="border px-3 py-2">{{ __('username') }}</th>
            <th class="border px-3 py-2">{{ __('email') }}</th>
            <th class="border px-3 py-2">{{ __('verified at') }}</th>
            <th class="border px-3 py-2">{{ __('created at') }}</th>
            <th class="border px-3 py-2">{{ __('deleted at') }}</th>
            <th class="border px-3 py-2">{{ __('action') }}</th>
          </tr>
        </thead>

        <tbody class="bg-inherit">
          <tr v-for="(user, i) in users.data" :key="i" class="bg-inherit hover:bg-slate-200 z-0">
            <td class="border p-2 text-center sticky left-0 bg-inherit">{{ i + 1 }}</td>
            <td class="border p-2 text-center">
              <img :src="user.profile_photo_url" :alt="user.username" class="w-14 h-14 object-center rounded-full border">
            </td>
            <td class="border p-2 capitalize">{{ user.name }}</td>
            <td class="border p-2 lowercase">{{ user.username }}</td>
            <td class="border p-2 lowercase">{{ user.email }}</td>
            <td class="border p-2">{{ user.email_verified_at && new Date(user.email_verified_at).toLocaleString('id') }}</td>
            <td class="border p-2">{{ user.created_at && new Date(user.created_at).toLocaleString('id') }}</td>
            <td class="border p-2">{{ user.deleted_at && new Date(user.deleted_at).toLocaleString('id') }}</td>
            <td class="border p-2">
              <div class="flex-wrap flex-shrink items-center text-xs text-center px-2 space-y-2">
                <Link :href="route('superuser.user.edit', user.id)" class="flex-none flex items-center justify-center space-x-1 w-auto px-3 py-1 font-semibold text-slate-100 bg-blue-600 border border-blue-600 rounded-md uppercase">
                  <Icon src="pen" class="w-3 h-3" /> <span>{{ __('edit') }}</span>
                </Link>
                
                <button @click.prevent="$emit('show-modal', 'permissions', user)" class="flex-none flex items-center justify-center space-x-1 w-auto px-3 py-1 font-semibold text-slate-100 bg-slate-600 border border-slate-600 rounded-md uppercase">
                  <Icon src="user-cog" class="w-3 h-3" /> <span>{{ __('permissions') }}</span>
                </button>
                
                <button @click.prevent="$emit('show-modal', 'roles', user)" class="flex-none flex items-center justify-center space-x-1 w-auto px-3 py-1 font-semibold text-slate-100 bg-pink-500 border border-pink-500 rounded-md uppercase">
                  <Icon src="user-shield" class="w-3 h-3" /> <span>{{ __('roles') }}</span>
                </button>
                
                <button v-if="user.deleted_at === null" @click.prevent="reset(user)" class="flex-none flex items-center justify-center space-x-1 w-auto px-3 py-1 font-semibold text-slate-100 bg-orange-500 border border-orange-500 rounded-md uppercase">
                  <Icon src="spinner" class="w-3 h-3" /> <span>{{ __('reset password') }}</span>
                </button>

                <button v-if="user.deleted_at" @click.prevent="restore(user)" class="flex-none flex items-center justify-center space-x-1 w-auto px-3 py-1 font-semibold text-slate-100 bg-blue-600 border border-blue-600 rounded-md uppercase">
                  <Icon src="spinner" class="w-3 h-3" /> <span>{{ __('restore') }}</span>
                </button>

                <button @click.prevent="destroy(user)" class="flex-none flex items-center justify-center space-x-1 w-auto px-3 py-1 font-semibold text-slate-100 bg-red-600 border border-red-600 rounded-md uppercase">
                  <Icon src="trash" class="w-3 h-3" /> <span>{{ __('delete') }}</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="flex items-center space-x-2">
      <div class="flex-none flex-wrap w-1/4 text-sm">
        {{ __('displaying') }} {{ users.from }} {{ __('to') }} {{ users.to }} {{ __('from') }} {{ users.total }}
      </div>

      <div class="flex items-center justify-end w-full overflow-auto">
        <Link v-for="(link, i) in users.links" :key="i" :href="url(link.url)" :class="(i === 0 && 'rounded-l-md') || (i + 1 === users.links.length && 'rounded-r-md') || (link.active && 'bg-blue-600 text-slate-100')" class="border px-2" v-html="link.label" />
      </div>
    </div>
  </div>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link, useForm } from '@inertiajs/inertia-vue3'
  import axios from 'axios'
  import Swal from 'sweetalert2'
  import Icon from '@/Components/Icon'

  export default defineComponent({
    props: {
      users: Object,
      perPage: Number,
      search: String,
    },

    components: {
      Link,
      Icon,
    },

    methods: {
      updateUserData() {
        const per_page = this.$refs.perPage?.value
        const search = this.$refs.search?.value
        
        this.$inertia.get(route('superuser.user.index', {per_page, search}))
      },

      url(base) {
        if (!base) {
          return '#'
        }

        const per_page = this.$refs.perPage?.value || this.$props.perPage
        const search = (this.$refs.search?.value || this.$props.search)?.trim()
        const url = new URLSearchParams()

        if (per_page) {
          url.append('per_page', per_page)
        }

        if (search && search !== '') {
          url.append('search', search)
        }

        return base + (url.toString() && `&${url.toString()}`)
      },

      destroy(user) {
        return Swal.fire({
          icon: 'question',
          text: user.deleted_at ? 'Are you want to delete permanently?' : 'Are you sure want to delete?',
          showCancelButton: true,
        }).then(response => {
          if (response.isConfirmed) {
            this.$inertia.delete(route('superuser.user.destroy', user.id) + (user.deleted_at ? '?force=1' : ''), {
              onSuccess: () => {
                this.showFlashMessage()
              }
            })
          }
        })
      },

      reset(user) {
        return Swal.fire({
          icon: 'question',
          text: __('are you sure?'),
          showCancelButton: true,
        }).then(response => {
          if (response.isConfirmed) {
            return axios.patch(route('api.v1.superuser.user.reset-password', user.id))
                        .then(response => {
                          if (response.status === 200) {
                            const flash = response.data

                            window.$flashes.value.push(flash)

                            setTimeout(() => removeFlashMessage(flash), flash.timer)
                          }
                        })
          }
        })
      },

      restore(user) {
        return useForm({id: user.id}).patch(route('superuser.user.restore', user.id), {
          onSuccess: () => showFlashMessage(),
        })
      },
    },

    mounted() {
      this.$refs.search?.focus()
    },
  })
</script>