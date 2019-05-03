<template>
  <div>
    <div v-if="userinfo.openId" class="container">
      <mp-cell-group title="试卷信息">
        <mp-cell content="用户名称" :label="userinfo.nickName" isLink=false></mp-cell>
        <mp-cell content="试卷名称" :label="gname" isLink=false></mp-cell>
        <mp-cell content="交卷时间" :label="submit_time" isLink=false></mp-cell>
        <mp-cell content="总分" :label="total" isLink=false></mp-cell>
      </mp-cell-group>
      <mp-cell-group title="答题信息">
        <Answer number="题号" answer="你的答案" stdanswer="标准答案" isLink=flase></Answer>
      </mp-cell-group>
      <div v-for="ans in answer_info" :key="ans.num">
        <Answer
          :number="ans.num" 
          :status="ans.right ? 'success' : 'cancel'" 
          :answer="ans.user_answer" 
          :stdanswer="ans.std_answer" 
          @click="getQuestion(ans.num)">
        </Answer>
      </div>
      <div class="submit">
        <button class="submit-btn" type="primary" @click="back_list()">返回试题列表</button>
      </div>
    </div>
  </div>
</template>

<script>
import MpCell from 'mp-weui/packages/cell'
import MpCellGroup from 'mp-weui/packages/cell-group'
import Answer from '@/components/Answer'
import { get } from '@/util'

export default {
  data () {
    return {
      userinfo: {},
      answer_info: [],
      total: 0,
      submit_time: '',
      gname: '',
      gid: 0
    }
  },

  components: {
    MpCell,
    MpCellGroup,
    Answer
  },

  methods: {
    async init () {
      wx.showNavigationBarLoading()
      let query = this.$root.$mp.query
      const gid = query.gid
      const params = {
        'gid': gid,
        'openid': this.userinfo.openId
      }
      const result = await get('/weapp/getanswer', params)

      this.gid = gid
      this.gname = result.gname
      this.submit_time = result.submit_time
      this.total = result.total
      this.answer_info = result.data
      wx.hideNavigationBarLoading()
    },

    getQuestion (num) {
      const url = '/pages/exam/main'
      const params = {
        gid: this.gid,
        num: num
      }
      this.$router.push({ path: url, query: params })
    },

    back_list () {
      const url = '/pages/list/main'
      wx.switchTab({
        url
      })
    }
  },

  onPullDownRefresh () {
    this.init()
    wx.stopPullDownRefresh()
  },

  onLoad () {
    if (!this.userinfo.openId) {
      let userinfo = wx.getStorageSync('userinfo')
      if (userinfo) {
        this.userinfo = userinfo
        this.init()
      }
    }
  },

  onShow () {
    if (!this.userinfo.openId) {
      let userinfo = wx.getStorageSync('userinfo')
      if (userinfo) {
        this.userinfo = userinfo
        this.init()
      }
    } else {
      this.init()
    }
  }
}
</script>

<style lang="scss">
.submit {
  margin-top: 40rpx;
  .submit-btn {
    width: 95%;
    font-size: 16px;
  }
}
</style>
