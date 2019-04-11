<template>
  <div class="rate">
    <span>☆☆☆☆☆</span>
    <div class="hollow" :style="style">
      ★★★★★
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {type: [Number, String], default: 0}
  },

  computed: {
    style () {
      return `width: ${this.value / 2}em`
    }
  }
}
</script>

<style lang="scss">
.rate {
  display: inline-block;
  position: relative;
  .hollow {
    position: absolute;
    top: 0;
    left: 0;
    display: inline-block;
    overflow: hidden;
  }
}
</style>
