<template>
  <div>
    <Head :title="title" />

    <div class="flex bg-gray-300 w-screen h-screen">
      <transition name="slide">
        <div v-if="showingSidebar" class="flex-none flex flex-col w-full sm:w-64 h-screen bg-slate-600">
          <div class="flex-none flex items-center w-full h-14 bg-slate-700 shadow-lg">
            <div class="flex-none w-14 h-14 p-3">
              <button @click.prevent="showingSidebar = ! showingSidebar" class="sm:hidden w-full h-full border border-slate-800 rounded-md shadow-md p-1">
                <Icon src="bars" class="text-slate-700 w-full h-full" />
              </button>
            </div>

            <h5 class="w-full text-xl text-center font-bold text-gray-300">{{ name }}</h5>

            <div class="flex-none w-14 h-14 p-3">
              <!--  -->
            </div>
          </div>

          <div class="flex flex-col w-full h-full overflow-y-auto">
            <Menu />
          </div>
        </div>
      </transition>

      <div :class="showingSidebar && 'hidden'" class="sm:flex flex-col w-full overflow-auto h-screen">
        <!-- Topbar -->
        <div class="flex-none flex items-center w-full h-14 bg-cyan-500 shadow-lg z-10">
          <div class="flex-none w-14 h-14 p-3">
            <button @click.prevent="showingSidebar = ! showingSidebar" class="w-full h-full border border-slate-600 rounded-md shadow-md p-1">
              <Icon src="bars" class="text-slate-700 w-full h-full" />
            </button>
          </div>

          <div class="w-full"></div>

          <div class="flex-none relative w-14 h-14 p-3">
            <button @click.prevent="showingNavigationDropdown = ! showingNavigationDropdown" class="w-full h-full border border-slate-600 rounded-full shadow-md">
              <img :src="usePage().props.value.user.profile_photo_url" :alt="usePage().props.value.user.name" class="w-full h-full rounded-full">
            </button>

            <transition name="fade">
              <div v-if="showingNavigationDropdown" class="absolute -bottom-14 right-5 flex flex-col w-32 bg-slate-100 rounded-md shadow-xl p-2">
                <Link :href="route('profile.show')" class="flex items-center justify-start space-x-2 px-2 border-b">
                  <Icon src="user" r=71 g=85 b=105 class="w-3 h-3" /> <div class="capitalize">{{ __('profile') }}</div>
                </Link>

                <button class="flex items-center justify-start space-x-2 px-2" @click.prevent="logout">
                  <Icon src="door-closed" r=71 g=85 b=105 class="w-3 h-3" /> <div class="capitalize">{{ __('logout') }}</div>
                </button>
              </div>
            </transition>
          </div>
        </div>
        <!-- Topbar -->

        <!-- Main Content -->
        <div class="flex flex-col w-full h-full overflow-auto space-y-4 p-4">
          <slot />
        </div>
        <!-- Main Content -->

        <!-- Footer -->
        <footer class="text-center w-full h-10 p-2 bg-cyan-500 first-letter:capitalize lowercase text-sm">
          {{ __('powered by') }} <span class="capitalize font-bold">laravel</span> {{ __('and') }} <span class="capitalize font-bold">vue.js</span> {{ __('with') }} <span class="capitalize font-bold">inertia.js</span>
        </footer>
        <!-- Footer -->

        <!-- Flash Message -->
        <div class="flex flex-col space-y-2 absolute top-14 sm:top-10 right-0 sm:right-4 w-full sm:max-w-md max-h-96 overflow-auto px-2 sm:px-0 z-10">
          <transition-group name="slide-left">
            <div v-for="(flash, i) in $flashes()" :key="i" :class="(flash.type === 'success' && 'border-green-600') || (flash.type === 'warning' && 'border-yellow-400') || (flash.type === 'info' && 'border-cyan-400') || (flash.type === 'error' && 'border-red-600')" class="w-full flex items-center space-x-2 bg-slate-100 border-l-8 rounded-md shadow-xl p-2">
              <div class="w-full flex-wrap first-letter:capitalize">{{ flash.text }}</div>
              <button @click.prevent="removeFlashMessage(flash)" class="w-6 h-6 border border-slate-700 rounded-md p-1">
                <Icon src="times" r="51" g="65" b="85" class="w-full h-full" />
              </button>
            </div>
          </transition-group>
        </div>
        <!-- Flash Message -->
      </div>
    </div>
  </div>
</template>

<style>
  .slide-enter-active,
  .slide-leave-active {
    transition: all 0.5s ease;
  }

  .slide-enter-from,
  .slide-leave-to {
    width: 0;
  }

  .slide-enter-to,
  .slide-leave-from {
    width: 100%;
  }

  @media (min-width: 639px) {
    .slide-enter-to,
    .slide-leave-from {
      width: 16rem;
    }
  }

  .slide-left-enter-active,
  .slide-left-leave-active {
    transition: all 0.5s ease;
  }

  .slide-left-enter-from,
  .slide-left-leave-to {
    opacity: 0;
  }

  .slide-left-enter-to,
  .slide-left-leave-from {
    opacity: 1;
  }

  @media (min-width: 639px) {
    .slide-left-enter-to,
    .slide-left-leave-from {
      opacity: 1;
    }
  }

  .fade-enter-active {
    transition: all 750ms ease;
  }

  .fade-leave-active {
    transition: all 300ms ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }

  .fade-enter-to,
  .fade-leave-from {
    opacity: 1;
  }
</style>

<script>
  import { defineComponent } from 'vue'
  import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
  import axios from 'axios';
  import Icon from '@/Components/Icon';
  import Menu from '@/Components/Menu';

  export default defineComponent({
    props: {
      title: String,
    },

    components: {
      Head,
      Link,
      Icon,
      Menu,
    },

    data() {
      return {
        showingSidebar: window.innerWidth > 639,
        showingNavigationDropdown: false,
        name: usePage().props.value.app.name,
      }
    },

    methods: {
      logout() {
        this.$inertia.post(route('logout'));
      },
    },

    mounted() {
      window.addEventListener('resize', () => {
        this.showingSidebar = window.innerWidth > 639;
      });

      if (usePage().props.value.token) {
        axios.defaults.headers['Authorization'] = `Bearer ${usePage().props.value.token}`
      }
    },
  })
</script>
