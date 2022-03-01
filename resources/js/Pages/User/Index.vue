<template>
  <DashboardLayout title="User">
    <Card :count="count" />

    <DataTable v-on:show-modal="showModal" :users="users" :perPage="perPage" :search="search" />

    <transition name="opacity">
      <div v-if="modal.show" class="fixed bottom-0 left-0 flex flex-col items-center justify-center w-full h-screen max-h-screen bg-slate-700 z-10 bg-opacity-75">
        <div class="flex flex-col w-full max-w-4xl bg-opacity-100 bg-slate-100 rounded-md shadow-xl">
          <div class="flex-none flex items-center justify-end w-full h-10 border-b border-slate-300 p-2">
            <div class="w-full text-left capitalize">{{ __(`user ${modal.type}`) }}</div>
            <button @click.prevent="modal.show = false" class="border rounded-md p-1">
              <Icon src="times" r="0" g="0" b="0" class="w-4 h-4" />
            </button>
          </div>

          <div class="overflow-auto flex-wrap w-full p-4">
            <template v-if="modal.type === 'roles'">
              <button @click.prevent="toggleRole(role)" v-for="(role, i) in roles" :key="i" :class="user.roles.find(r => r.id === role.id) && 'bg-green-600'" class="px-3 py-1 m-1 border rounded-md shadow uppercase">{{ __(role.name) }}</button>
            </template>

            <template v-else-if="modal.type === 'permissions'">
              <button @click.prevent="togglePermission(permission)" v-for="(permission, i) in permissions" :key="i" :class="(user.permissions.find(r => r.id === permission.id) && 'bg-green-600') || (user.roles.find(role => role.permissions.find(p => p.id === permission.id)) && 'bg-green-600')" class="px-3 py-1 m-1 border rounded-md shadow uppercase">{{ __(permission.name) }}</button>
            </template>
          </div>
        </div>
      </div>
    </transition>
  </DashboardLayout>
</template>

<style scoped>
  .opacity-enter-active,
  .opacity-leave-active {
    transition: all 300ms ease;
    opacity: 1;
  }

  .opacity-enter-from,
  .opacity-leave-to {
    opacity: 0;
  }
</style>

<script>
  import { defineComponent } from "vue";
  import { Link} from '@inertiajs/inertia-vue3';
  import DashboardLayout from '@/Layouts/DashboardLayout';
  import DataTable from './DataTable'
  import Card from './Card'
  import Icon from '@/Components/Icon'
import axios from "axios";

  export default defineComponent({
    props: {
      users: Object,
      roles: Array,
      permissions: Array,
      count: Object,
      perPage: Number,
      search: String,
    },

    components: {
      DashboardLayout,
      DataTable,
      Card,
      Icon,
      Link,
    },

    data() {
      return {
        modal: {
          show: false,
          type: new String,
        },

        user: {
          permissions: [],
          roles: [],
        },
      }
    },

    methods: {
      showModal(type, user) {
        this.modal.show = true
        this.modal.type = type
        this.user = user
      },

      togglePermission(permission) {
        return axios.patch(route('api.v1.superuser.user.toggle-permission'), {
                      userId: this.user.id,
                      permissionId: permission.id,
                    })
                    .then(response => {
                      if (response.status === 200) {
                        pushFlashMessage(response.data)

                        return axios.get(route('api.v1.superuser.user.find', this.user.id))
                                    .then(response => {
                                      if (response.status === 200) {
                                        this.user = response.data
                                      }
                                    })
                      } else {
                        pushFlashMessage({
                          type: 'error',
                          text: response.data.message,
                        })
                      }
                    })
                    .catch(error => {
                      pushFlashMessage({
                        type: 'error',
                        text: error.message,
                      })
                    })
      },

      toggleRole(role) {
        return axios.patch(route('api.v1.superuser.user.toggle-role'), {
                      userId: this.user.id,
                      roleId: role.id,
                    })
                    .then(response => {
                      if (response.status === 200) {
                        pushFlashMessage(response.data)

                        return axios.get(route('api.v1.superuser.user.find', this.user.id))
                                    .then(response => {
                                      if (response.status === 200) {
                                        this.user = response.data
                                      }
                                    })
                      } else {
                        pushFlashMessage({
                          type: 'error',
                          text: response.data.message,
                        })
                      }
                    })
                    .catch(error => {
                      pushFlashMessage({
                        type: 'error',
                        text: error.message,
                      })
                    })
      },
    },
  });
</script>