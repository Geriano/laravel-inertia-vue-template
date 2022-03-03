<template>
  <DashboardLayout title="User">
    <Card :count="count" />

    <DataTable :users="users" :perPage="perPage" :search="search" />
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
      }
    },
  });
</script>