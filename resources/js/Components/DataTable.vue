<template>
  <div class="bg-inherit p-0 rounded-md">
    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 sm:items-center justify-between mb-2">
      <div class="flex w-full sm:w-auto items-center space-x-2 justify-between sm:justify-start">
        <span class="lowercase first-letter:capitalize">{{ __('per page') }}</span>

        <select v-model="config.perPage" @change.prevent="refresh" class="bg-inherit border border-slate-300 rounded-md p-1">
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="108">100</option>
          <option value="508">500</option>
        </select>

        <button v-if="softDeletes" @click.prevent="config.withTrashed = ! config.withTrashed; refresh()" class="border border-slate-300 rounded px-3 py-1 uppercase">
          {{ __(config.withTrashed ? 'without trashed' : 'with trashed') }}
        </button>
      </div>

      <input type="search" v-model="config.search" @input.prevent="refresh" class="bg-inherit sm:w-2/5 border border-slate-300 rounded-md placeholder:capitalize text-xs" :placeholder="__('search something')">
    </div>

    <transition name="fade">
      <div class="overflow-auto border border-slate-300 rounded-md mb-2 max-h-[25rem]">
        <table class="w-full border-collapse">
          <thead class="bg-slate-200 sticky top-0 z-10">
            <slot name="head" :config="config" :sort="sort" />
          </thead>

          <tfoot class="bg-slate-200 sticky bottom-0 z-10">
            <slot name="foot" :config="config" :sort="sort" />
          </tfoot>

          <tbody>
            <transition-group name="fade">
              <slot name="body" v-for="(item, i) in data.data" :key="i" :item="item" :i="i" :refresh="refresh" />
            </transition-group>
          </tbody>
        </table>
      </div>
    </transition>

    <div class="flex items-center">
      <div class="flex-none w-1/4">
        <p class="lowercase first-letter:capitalize text-xs">
          {{ __('displaying') }} {{ data.from }} {{ __('to') }} {{ data.to }} {{ __('from') }} {{ data.total }}
        </p>
      </div>

      <div class="flex flex-wrap w-full items-center justify-end">
        <div
          v-for="(link, i) in data.links"
          :key="i"
          v-html="__(link.label)"
          :class="(i == 0 && 'rounded-l-md') || (i == data.links.length - 1 && 'rounded-r-md') || (link.active && 'bg-slate-700 border-slate-600 text-slate-100')"
          @click.prevent="page(link)"
          class="text-xs border p-1 shadow cursor-pointer"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import { defineComponent } from 'vue'

  export default defineComponent({
    props: {
      route: {
        type: String,
        required: true,
      },

      softDeletes: {
        type: Boolean,
        default: false,
      },

      interval: {
        type: Number,
        default: 0,
      },
    },

    data() {
      return {
        config: {
          url: new String,
          perPage: 10,
          search: new String,
          withTrashed: false,
          sort: {
            key: new String,
            order: new String,
          },
        },

        loading: true,

        data: {},
        intervalHandlerId: null,
      }
    },

    methods: {
      refresh() {
        this.loading = true

        const data = {}

        if (this.config.perPage) data.perPage = this.config.perPage
        if (this.config.search.trim()) data.search = this.config.search
        if (this.config.sort.key.trim() && this.config.sort.order.trim()) data.sort = {key: this.config.sort.key, order: this.config.sort.order}
        if (this.softDeletes) data.withTrashed = this.config.withTrashed ? 1 : 0

        return axios.post(this.config.url, data)
                    .then(response => response.data)
                    .then(data => this.data = data)
                    .finally(() => this.loading = false)
      },

      sort(key) {
        if (this.config.sort.key === key) {
          this.config.sort.order = this.config.sort.order === 'asc' ? 'desc' : 'asc'
        } else {
          this.config.sort.key = key
          this.config.sort.order = 'asc'
        }

        this.refresh()
      },

      page(link) {
        if (link.url) {
          this.config.url = link.url
          this.refresh()
        }
      },
    },

    mounted() {
      this.config.url = route(this.route)
      this.refresh()

      if (this.interval) {
        this.intervalHandlerId = setInterval(() => this.refresh(), this.interval)
      }
    },

    unmounted() {
      if (this.intervalHandlerId) {
        clearInterval(this.intervalHandlerId)
      }
    },
  })
</script>