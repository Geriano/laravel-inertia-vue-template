<template>
  <DashboardLayout title="Data Table">
    <DataTable route="api.v1.superuser.data-table">
      <template v-slot:head="props">
        <tr class="border-b border-slate-300">
          <DTh class="px-3 py-2 uppercase" :sortable="false">no</DTh>
          <DTh class="px-3 py-2 uppercase" :sortable="false" @click.prevent="props?.sort('profile')">profile</DTh>
          <DTh class="px-3 py-2 uppercase" :sort="props.table.sort.key === 'name' && props.table.sort.order" @click.prevent="props?.sort('name')">name</DTh>
          <DTh class="px-3 py-2 uppercase" :sort="props.table.sort.key === 'username' && props.table.sort.order" @click.prevent="props?.sort('username')">username</DTh>
          <DTh class="px-3 py-2 uppercase" :sort="props.table.sort.key === 'email' && props.table.sort.order" @click.prevent="props?.sort('email')">email</DTh>
          <DTh class="px-3 py-2 uppercase" :sort="props.table.sort.key === 'verified_at' && props.table.sort.order" @click.prevent="props?.sort('verified_at')">verified at</DTh>
          <DTh class="px-3 py-2 uppercase" :sort="props.table.sort.key === 'created_at' && props.table.sort.order" @click.prevent="props?.sort('created_at')">created at</DTh>
          <DTh class="px-3 py-2 uppercase" :sort="props.table.sort.key === 'deleted_at' && props.table.sort.order" @click.prevent="props?.sort('deleted_at')">deleted at</DTh>
          <DTh class="px-3 py-2 uppercase" :sortable="false">action</DTh>
        </tr>
      </template>

      <template v-slot:foot>
        <tr class="border-t border-slate-300 hover:bg-slate-100">
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">no</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">profile</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">name</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">username</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">email</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">verified at</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">created at</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">deleted at</th>
          <th class="text-sm px-3 py-1 uppercase" :sortable="false">action</th>
        </tr>
      </template>

      <template v-slot:body="props">
        <tr class="hover:bg-slate-200">
          <td class="sticky top-0 left-0 border border-slate-200 text-center">{{ props.i + 1 }}</td>
          <td class="border border-slate-200 p-2">
            <img :src="props.item.profile_photo_url" :alt="props.item.username" class="w-12 h-12 border border-slate-200 rounded-full mx-auto">
          </td>
          <td class="border border-slate-200 px-3 py-1 capitalize">{{ props.item.name }}</td>
          <td class="border border-slate-200 px-3 py-1 lowercase">{{ props.item.username }}</td>
          <td class="border border-slate-200 px-3 py-1 uppercase">{{ props.item.email }}</td>
          <td class="border border-slate-200 px-3 py-1">{{ props.item.verified_at && new Date(props.item.verified_at).toLocaleString('id') }}</td>
          <td class="border border-slate-200 px-3 py-1">{{ props.item.created_at && new Date(props.item.created_at).toLocaleString('id') }}</td>
          <td class="border border-slate-200 px-3 py-1">{{ props.item.deleted_at && new Date(props.item.deleted_at).toLocaleString('id') }}</td>
          <td class="border border-slate-200 px-3 py-1"></td>
        </tr>
      </template>
    </DataTable>
  </DashboardLayout>
</template>

<script>
  import { defineComponent } from 'vue'
  import DashboardLayout from '@/Layouts/DashboardLayout'
  import DataTable from '@/Components/DataTable'
  import DTh from '@/Components/DataTableTh'

  export default defineComponent({
    components: {
      DashboardLayout,
      DataTable,
      DTh,
    },
  })
</script>