<template>
  <div
    :class="['weui-cell', {'weui-cell_access': !!href, 'weui-cell_link': isLink}]"
    :hover-class="(!isLink && !!href) ? 'weui-cell_active' : 'none'"
    @click="$emit('click', $event)"
  >
    <div class="weui-cell__bd">
      <!-- <slot name="number"> -->
      <span v-text="number" />
      <!-- </slot> -->
    </div>
    <div class="weui-cell__bd">
      <!-- <slot> -->
      <span v-text="answer" />
      <!-- </slot> -->
    </div>
    <div class="weui-cell__bd">
      <!-- <slot> -->
      <span v-text="stdanswer" />
      <!-- </slot> -->
    </div>
    <div class="weui-cell__ft">
      <!-- <slot name="status"> -->
      <icon :type="status" class="weui-cell_icon" />
      <!-- </slot> -->
    </div>
  </div>
</template>

<script>
export default {
  name: 'Answer',
  props: {
    number: String,
    status: String,
    answer: String,
    stdanswer: String,
    isLink: Boolean
  }
}
</script>

<style>
.weui-cell_icon {
  display: block;
  width: 20px;
  height: 20px;
  margin-right: 5px;
}
</style>


