<template>
  <div>
    <div
      class="weui-cells__title"
      v-text="title"
      v-if="title"
    />
    <div class="content">
      <div class="weui-cells_after-title">请将空白处补充完整</div>
        <div :class="['weui-cells', {'weui-cells_after-title': title}]">
          <input
            :value="value"
            @input="onChange"
            class="weui-input"
          />
        </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Blank',
  props: {
    value: String,
    title: String
  },
  methods: {
    onChange (e) {
      this.$emit('input', e.target.value)
      this.$emit('change', e.target.value)
    }
  }
}
</script>

<style>
.content {
  padding: 0 30rpx;
}
</style>
