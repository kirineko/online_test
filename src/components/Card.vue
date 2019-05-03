<template>
  <a :href="detailUrl">
    <div class="exam-card">
      <div class="thumb">
        <img :src="imageurl"  class="img" mode="aspectFit">
      </div>
      <div class="detail">
        <div class="weui-cell">
          <div class="weui-cell__bd">
            试卷编号
          </div>
          <div class="weui-cell__ft">
            {{exam.gid}}
          </div>
        </div>
        <div class="weui-cell">
          <div class="weui-cell__bd">
            课程名称
          </div>
          <div class="weui-cell__ft">
            {{exam.lname}}
          </div>
        </div>
        <div class="weui-cell">
          <div class="weui-cell__bd">
            试卷名称
          </div>
          <div class="weui-cell__ft">
            {{exam.gname}}
          </div>
        </div>
        <div v-if="exam.score === undefined" class="weui-cell">
          <div class="weui-cell__bd">
            测试时间
          </div>
          <div class="weui-cell__ft">
            {{starttime}}~{{endtime}}
          </div>
        </div>
        <div v-if="exam.score !== undefined" class="weui-cell">
          <div class="weui-cell__bd">
            测试分数
          </div>
          <div class="weui-cell__ft">
            {{exam.score}}
          </div>
        </div>
      </div>
    </div>
  </a>
</template>

<script>
export default {
  computed: {
    detailUrl () {
      return '/pages/exam/main?gid=' + this.exam.gid
    },

    starttime () {
      return this.exam.start_time.substring(0, 10)
    },

    endtime () {
      return this.exam.end_time.substring(0, 10)
    }
  },
  props: ['exam', 'imageurl']
}
</script>

<style lang="scss" scoped>
.exam-card {
  padding: 5px;
  overflow: hidden;
  margin-top: 5px;
  margin-bottom: 5px;
  font-size: 14px;
  .thumb {
    width: 180rpx;
    height: 300rpx;
    float: left;
    margin: auto auto;
    overflow: hidden;
    img {
      max-width: 100%;
      max-height: 100%;
    }
  }
  .detail {
    margin-left: 100px;
  }
}
</style>
