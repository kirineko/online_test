<template>
    <div>
      <div class="title">单项选择题</div>
      <mp-radio
        v-model="choiced"
        :options="question.options"
        :title="question.title"
      />
      <div class="group-btn">
        <button class="mini-btn left" type="primary">上一题</button>
        <button class="mini-btn right" type="primary">下一题</button>
      </div>
    </div>
</template>

<script>
// import { get } from '@/util'
import MpRadio from 'mp-weui/packages/radio'

export default {

  components: {
    MpRadio
  },

  data () {
    return {
      question: {},
      choiced: ''
    }
  },

  methods: {
    getList () {
      wx.showNavigationBarLoading()
      // const books = await get('/weapp/booklist', {'page': this.page})
      this.question = {
        title: '下列哪个不是Python中的数据类型？',
        options: [
          {
            label: 'A. 字符串',
            value: 'A'
          },
          {
            label: 'B. 整数',
            value: 'B'
          },
          {
            label: 'C. 列表',
            value: 'C'
          },
          {
            label: 'D. 资源',
            value: 'D'
          }
        ]
      }
      wx.hideNavigationBarLoading()
    }
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

.title {
  font-size: 50rpx;
  text-align: center;
  font-family: Verdana, Geneva, Tahoma, sans-serif
}

.weui-cells__title {
  font-size: 40rpx;
  margin-top: 1em;
  margin-bottom: 1.2em;
}

.weui-cell {
  padding: 30rpx 30rpx;
}

.group-btn {
  display: block;
  margin: 70rpx 20rpx;
  .mini-btn {
    width: 40%;
    font-size: 16px;
  }
  .left {
    float: left;
  }
  .right {
    float: right;
  }
}
</style>
