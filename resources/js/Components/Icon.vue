<script>
  import { h } from 'vue'

  export default {
    props: {
      src: {
        type: String,
      },

      r: {
        type: Number,
        default: 255,
      },

      g: {
        type: Number,
        default: 255,
      },

      b: {
        type: Number,
        default: 255,
      },

      a: {
        type: Number,
        default: 1,
      },
    },

    setup(props, { attrs }) {
      return props => {
        const file = props.src
        const r = Number(props.r)
        const g = Number(props.g)
        const b = Number(props.b)
        const a = Number(props.a)
        const src = route('icon.rgba', { file, r, g, b, a })

        return h('img', {
          ...attrs,
          src,
          class: 'object-center',
        })
      }
    },
  }
</script>