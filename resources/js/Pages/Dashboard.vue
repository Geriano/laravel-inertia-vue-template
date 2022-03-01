<template>
    <DashboardLayout title="Dashboard">
        <h1>Hello World</h1>
    </DashboardLayout>
</template>

<script>
    import { defineComponent } from 'vue'
    import DashboardLayout from '@/Layouts/DashboardLayout.vue'

    export default defineComponent({
        components: {
            DashboardLayout,
        },
    })
</script>
