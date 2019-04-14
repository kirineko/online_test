<template>
    <div>
      <div class="title">单项选择题</div>
      <mp-radio
        v-model="choiced"
        :options="question.options"
        :title="question.content"
      />
      <div class="group-btn">
        <button class="mini-btn left" type="primary">上一题</button>
        <button class="mini-btn right" type="primary" @click="nextpage">下一题</button>
      </div>
    </div>
</template>

<script>
import { get } from '@/util'
import MpRadio from 'mp-weui/packages/radio'

export default {

  components: {
    MpRadio
  },

  data () {
    return {
      num: 1,
      question: {},
      choiced: ''
    }
  },

  methods: {
    async getList () {
      wx.showNavigationBarLoading()
      console.log(this.num)
      const question = await get('/weapp/question', {'num': this.num})
      this.question = question

      const qvalues = ['A', 'B', 'C', 'D']
      const qlabels = ['result_a', 'result_b', 'result_c', 'result_d']
      this.question.options = []
      for (let i = 0; i < 4; i++) {
        let option = {
          label: `${qvalues[i]}. ${question[qlabels[i]]}`,
          value: qvalues[i]
        }
        this.question.options.push(option)
      }
      console.log(this.question)
      wx.hideNavigationBarLoading()
    }
  },

  nextpage () {
    this.num = this.num + 1
    console.log(this.num)
    this.getList()
  },

  onPullDownRefresh () {
    this.getList()
  },

  onShow () {
    this.getList()
  }
}
</script>

<style lang="scss">
.weui-cells__title {
  font-size: 40rpx;
}

.group-btn {
  display: block;
  margin: 40rpx 20rpx;
  .mini-btn {
    width: 40% 
  }
  .left {
    float: left;
  }
  .right {
    float: right;
  }
}
</style>
