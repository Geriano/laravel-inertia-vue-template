<script>
  import { h } from 'vue'

  export default {
    props: {
      src: {
        type: String,
      },
    },

    setup(props, { attrs }) {
      return props => {
        const file = props.src

        return h('i', {
          ...attrs,
          class: `fa fa-${file} ${attrs.class || ''}`,
        })
      }
    },
  }
</script>