<template>
  <DashboardLayout :title="`@${user.username} - Profile`">
    <div class="bg-slate-100 border rounded-md shadow-xl p-4">
      <div class="relative w-full h-32 border rounded-md">
        <img :src="user.profile_photo_url" :alt="user.username" class="absolute -bottom-14 left-7 sm:left-14 w-28 h-28 border rounded-full shadow">
      </div>

      <div class="flex flex-col pl-36 sm:pl-44">
        <h5 class="font-md font-semibold capitalize">{{ user.name }}</h5>
        <p class="text-xs lowercase">@{{ user.username }}</p>
        <p class="text-xs lowercase">{{ user.email }}</p>
      </div>

      <div class="flex items-center space-x-2 mt-2">
        <button @click.prevent="role = ! role; permission = false" class="uppercase bg-blue-600 text-xs text-slate-200 border border-blue-700 rounded-md shadow px-3 py-1">{{ __('roles') }}</button>
        <button @click.prevent="permission = ! permission; role = false" class="uppercase bg-cyan-500 text-xs text-slate-200 border border-cyan-600 rounded-md shadow px-3 py-1">{{ __('permissions') }}</button>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
        <transition-group name="fade">
          <template v-if="role">
            <div v-for="(role, i) in roles" :key="i" class="flex items-center space-x-2">
              <input @click.prevent="toggleRole(role)" :checked="user.roles.find(r => r.id === role.id)" type="checkbox" :name="`role[${i}]`" class="bg-transparent border rounded-md">
              <label @click.prevent="toggleRole(role)" :for="`role[${i}]`" class="text-sm cursor-pointer uppercase">{{ __(role.name) }}</label>
            </div>
          </template>
        </transition-group>

        <transition-group name="fade">
          <template v-if="permission">
            <div v-for="(permission, i) in permissions" :key="i" class="flex items-center space-x-2">
              <input @click.prevent="togglePermission(permission)" :checked="user.permissions.find(p => p.id === permission.id) || user.roles.find(r => r.permissions.find(p => p.id === permission.id))" type="checkbox" :name="`permission[${i}]`" class="bg-transparent border rounded-md">
              <label @click.prevent="togglePermission(permission)" :for="`permission[${i}]`" class="text-sm cursor-pointer uppercase">{{ __(permission.name) }}</label>
            </div>
          </template>
        </transition-group>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: all 500ms ease;
  }

  .fade-leave-active {
    transition-duration: 250ms;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
</style>

<script>
  import { defineComponent } from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  import DashboardLayout from '@/Layouts/DashboardLayout'

  export default defineComponent({
    props: {
      user: Object,
      permissions: Array,
      roles: Array,
    },

    components: {
      DashboardLayout,
    },

    data() {
      return {
        permission: false,
        role: false,
      }
    },

    methods: {
      toggleRole(role) {
        Inertia.on('success', e => {
          this.permission = false
          this.role = true
        })

        return Inertia.patch(route('superuser.user.toggle-role', {
          user: this.user.id,
          role: role.id
        }))
      },

      togglePermission(permission) {
        Inertia.on('success', e => {
          this.permission = true
          this.role = false
        })

        return Inertia.patch(route('superuser.user.toggle-permission', {
          user: this.user.id,
          permission: permission.id,
        }))
      },
    },
  })
</script>