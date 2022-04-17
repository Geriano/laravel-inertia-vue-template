<template>
  <Card class="bg-white">
    <template #body>
      <DataTable route="api.v1.superuser.user.paginate" :softDeletes="true" :interval="5000">
        <template v-slot:head="props">
          <tr class="border-b border-slate-300">
            <DTh class="sticky left-0 px-3 py-1" :sortable="false">no</DTh>
            <DTh class="px-3 py-1" :sortable="false">profile</DTh>
            <DTh class="px-3 py-1" :table="props" column="name">name</DTh>
            <DTh class="px-3 py-1" :table="props" column="username">username</DTh>
            <DTh class="px-3 py-1" :table="props" column="email">email</DTh>
            <DTh class="px-3 py-1" :table="props" column="email_verified_at">verified at</DTh>
            <DTh class="px-3 py-1" :table="props" column="created_at">created at</DTh>
            <DTh class="px-3 py-1" :table="props" column="deleted_at">deleted at</DTh>
            <DTh class="px-3 py-1" :sortable="false">action</DTh>
          </tr>
        </template>

        <template v-slot:body="props">
          <tr class="hover:bg-slate-100">
            <td class="sticky top-0 left-0 border border-slate-200 text-center">{{ props.i + 1 }}</td>
            <td class="border border-slate-200 p-2">
              <img :src="props.item.profile_photo_url" :alt="props.item.username" class="w-12 h-12 border border-slate-200 rounded-full mx-auto">
            </td>
            <td class="border border-slate-200 px-3 py-1 capitalize">{{ props.item.name }}</td>
            <td class="border border-slate-200 px-3 py-1 lowercase">{{ props.item.username }}</td>
            <td class="border border-slate-200 px-3 py-1 uppercase">{{ props.item.email }}</td>
            <td class="border border-slate-200 px-3 py-1">{{ props.item.email_verified_at && new Date(props.item.email_verified_at).toLocaleString('id') }}</td>
            <td class="border border-slate-200 px-3 py-1">{{ props.item.created_at && new Date(props.item.created_at).toLocaleString('id') }}</td>
            <td class="border border-slate-200 px-3 py-1">{{ props.item.deleted_at && new Date(props.item.deleted_at).toLocaleString('id') }}</td>
            <td class="border border-slate-200 px-3 py-1">
              <BtnSplit class="bg-slate-700 border-slate-800 text-slate-50 hover:bg-slate-800" :r="248" :g="250" :b="252">
                <template #text>
                  <span class="font-semibold text-sm uppercase">action</span>
                </template>

                <template #actions>
                  <div class="absolute top-[102.5%] right-0 grid bg-slate-700 border border-slate-800 rounded shadow z-10">
                    <Link v-if="props.item.deleted_at === null" :href="route('superuser.user.show', props.item.id)" class="text-sm uppercase px-3 py-1 hover:bg-slate-800">
                      {{ __('profile') }}
                    </Link>

                    <Link v-if="props.item.deleted_at === null" :href="route('superuser.user.edit', props.item.id)" class="text-sm uppercase px-3 py-1 hover:bg-slate-800">
                      {{ __('edit') }}
                    </Link>

                    <button v-if="props.item.deleted_at === null" @click.prevent="reset(props.item)" class="text-sm uppercase px-3 py-1 hover:bg-slate-800">
                      {{ __('reset password') }}
                    </button>

                    <button v-if="props.item.deleted_at" @click.prevent="recovery(props.item, props.refresh)" class="text-sm uppercase px-3 py-1 hover:bg-slate-800">
                      {{ __('recovery') }}
                    </button>

                    <button @click.prevent="destroy(props.item, props.refresh)" class="text-sm uppercase px-3 py-1 hover:bg-slate-800">
                      {{ __('delete') }}
                    </button>
                  </div>
                </template>
              </BtnSplit>
            </td>
          </tr>
        </template>
      </DataTable>
    </template>
  </Card>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link, usePage } from '@inertiajs/inertia-vue3'
  import { Inertia } from '@inertiajs/inertia'
  import Swal from 'sweetalert2'
  import Icon from '@/Components/Icon'
  import DataTable from '@/Components/DataTable'
  import DTh from '@/Components/DataTableTh'
  import BtnSplit from '@/Components/Button/Split'
  import Card from '@/Components/Card'

  export default defineComponent({
    props: {
      users: Object,
    },

    components: {
      Link,
      Icon,
      DataTable,
      DTh,
      BtnSplit,
      Card,
    },

    methods: {
      destroy(user, refresh) {
        return Swal.fire({
          icon: 'question',
          html: `<span class="first-letter:capitalize lowercase">${__(user.deleted_at ? 'are you want to delete permanently' : 'are you sure want to delete')}?</span>`,
          showCancelButton: true,
        }).then(response => {
          if (response.isConfirmed) {
            Inertia.delete(route('superuser.user.destroy', user.id) + (user.deleted_at ? '?force=1' : ''))
            
            setTimeout(() => refresh(), 500)
          }
        })
      },

      reset(user) {
        return Inertia.patch(route('superuser.user.reset-password', user.id))
      },

      recovery(user, refresh) {
        Inertia.patch(route('superuser.user.recovery', user.id))

        return setTimeout(() => refresh(), 500)
      },
    },

    mounted() {
      this.$refs.search?.focus()
    },
  })
</script>