<template>
  <DashboardLayout title="User">
    <Card :count="count" />

    <form @submit.prevent="submit" action="route('superuser.user.store')" method="post" class="flex flex-col space-y-2 bg-slate-100 border rounded-md shadow-md">
      <div class="flex flex-col space-y-2 p-2">
        <div class="flex flex-col space-y-2">
          <label for="name" class="capitalize text-sm">{{ __('name') }}</label>
          <input v-model="form.name" type="text" class="w-2/3 bg-transparent border rounded-md text-xs placeholder-gray-600 placeholder:capitalize py-2 px-3 leading-tight focus:placeholder-gray-600" :placeholder="__('name')" required>

          <div v-if="form.errors.name" class="text-slate-400 text-xs">
            {{ form.errors.name }}
          </div>
        </div>
        
        <div class="flex flex-col space-y-2">
          <label for="username" class="capitalize text-sm">{{ __('username') }}</label>
          <input v-model="form.username" type="text" class="w-2/3 bg-transparent border rounded-md text-xs placeholder-gray-600 placeholder:capitalize py-2 px-3 leading-tight focus:placeholder-gray-600" :placeholder="__('username')" required>
          
          <div v-if="form.errors.username" class="text-slate-400 text-xs">
            {{ form.errors.username }}
          </div>
        </div>
        
        <div class="flex flex-col space-y-2">
          <label for="email" class="capitalize text-sm">{{ __('email') }}</label>
          <input v-model="form.email" type="email" class="w-2/3 bg-transparent border rounded-md text-xs placeholder-gray-600 placeholder:capitalize py-2 px-3 leading-tight focus:placeholder-gray-600" :placeholder="__('email')" required>
          
          <div v-if="form.errors.email" class="text-slate-400 text-xs">
            {{ form.errors.email }}
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end w-full space-x-2 p-2 bg-slate-200">
        <Link :href="route('superuser.user.index')" class="bg-slate-800 px-3 py-1 border border-slate-900 rounded-md uppercase text-xs font-semibold text-slate-100">{{ __('back') }}</Link>

        <button type="submit" class="bg-emerald-400 px-3 py-1 border border-emerald-500 rounded-md uppercase text-xs font-semibold">
          {{ __('update') }}
        </button>
      </div>
    </form>
  </DashboardLayout>
</template>

<style scoped>
  .opacity-enter-active,
  .opacity-leave-active {
    transition: all 1000ms ease;
    opacity: 1;
  }

  .opacity-enter-from,
  .opacity-leave-to {
    opacity: 0;
  }
</style>

<script>
  import { defineComponent } from "vue";
  import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
  import DashboardLayout from '@/Layouts/DashboardLayout';
  import Card from './Card'

  export default defineComponent({
    props: {
      user: Object,
      count: Object,
    },

    components: {
      DashboardLayout,
      Card,
      Link,
    },

    data() {
      return {
        form: useForm({
          id: new Number(),
          name: new String(),
          username: new String(),
          email: new String(),
        }),
      }
    },

    methods: {
      submit() {
        this.form.patch(route('superuser.user.update', this.user.id), {
          onSuccess: () => {
            this.reset()
            
            this.showFlashMessage()
          },
        });
      },

      reset() {
        this.form = useForm({
          id: this.user.id,
          name: this.user.name,
          username: this.user.username,
          email: this.user.email,
        })
      },
    },

    mounted() {
      this.reset() 
    },
  });
</script>