<template>
    <button :class="class" @click="$emit('myEvent')">{{text}}</button>
    from xComp
</template>

<script>
  export default {
    data() {
      return {
        test: 'testing'
      }
    },
    emits: ['myEvent'],
    props: {
      text: String,
      class: String,
    },
  }
</script>
