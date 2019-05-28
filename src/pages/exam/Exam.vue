<template>
    <div>
      <div v-if="question.type==='选择题'">
        <div class="title">单项选择题</div>
        <mp-radio 
          v-model="choiced"
          :options="question.options"
          :title="question.num + '. ' + question.content"
        />
      </div>
      <div v-if="question.type==='填空题'">
        <div class="title">填空题</div>
        <Blank
          v-model="choiced"
          :title="question.num + '. ' + question.content"
        >
        </Blank>
      </div>
      
      <div class="group-btn">
        <button class="mini-btn left" type="primary" @click="prepage()">上一题</button>
        <button class="mini-btn right" type="primary" @click="nextpage()">下一题</button>
      </div>
      <div v-if="submit && first" class="submit">
        <button class="submit-btn" type="primary" @click="submit_answers()">交卷</button>
      </div>
      <div v-if="!first" class="submit">
        <button class="submit-btn" type="primary" @click="back_answers()">返回成绩列表</button>
      </div>
    </div>
</template>

<script>
import { get, post } from '@/util'
import MpRadio from 'mp-weui/packages/radio'
import Blank from '@/components/Blank'

export default {

  components: {
    MpRadio,
    Blank
  },

  data () {
    return {
      num: 1,
      gid: 1,
      question: {
        title: '',
        options: []
      },
      choiced: '',
      userinfo: {},
      answers: {},
      submit: false,
      first: true
    }
  },

  methods: {
    async init () {
      let query = this.$root.$mp.query
      this.num = parseInt(query.num) || 1
      this.gid = query.gid
      await this.getList()
      const userinfo = wx.getStorageSync('userinfo')
      if (userinfo) {
        this.userinfo = userinfo
      }
      this.isfirst()
      this.getAnswer()
    },

    async isfirst () {
      const params = {
        'openid': this.userinfo.openId,
        'gid': this.gid
      }
      const answers = await get('/weapp/getuseranswer', params)
      if (!answers) {
        this.first = true
      } else {
        this.first = false
      }
    },

    async getList () {
      wx.showNavigationBarLoading()
      const question = await get('/weapp/question', {'gid': this.gid, 'num': this.num})
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
      wx.hideNavigationBarLoading()
    },

    /**
     * 1. 当前选择保存本地存储
     * 2. 从数据库读取题目
     * 3. 从本地存储中读取题目选择
     */
    async nextpage () {
      this.saveAnswer()
      this.num = this.num + 1
      try {
        await this.getList()
        this.getAnswer()
      } catch (e) {
        this.num = this.num - 1
        wx.hideNavigationBarLoading()
        this.submit = true
      }
    },
    async prepage () {
      this.saveAnswer()
      this.num = this.num - 1
      try {
        await this.getList()
        this.getAnswer()
      } catch (e) {
        this.num = this.num + 1
        wx.hideNavigationBarLoading()
      }
    },
    saveAnswer () {
      let answer = {
        [this.num]: this.choiced
      }
      let answers = Object.assign(this.answers, answer)
      wx.setStorageSync('answers', answers)
    },
    async getAnswer () {
      let answers = wx.getStorageSync('answers')
      if (!answers) {
        const params = {
          'openid': this.userinfo.openId,
          'gid': this.gid
        }
        answers = await get('/weapp/getuseranswer', params)
      }
      this.answers = answers || {}
      this.choiced = answers[this.num] ? answers[this.num] : ''
    },

    async submit_answers () {
      this.saveAnswer()
      const answers = wx.getStorageSync('answers')
      await post('/weapp/postanswer', {
        openid: this.userinfo.openId,
        gid: this.question.gid,
        gname: this.question.gname,
        answers: answers
      })
      wx.removeStorageSync('answers')
      const url = '/pages/score/main'
      const params = {
        gid: this.question.gid
      }
      this.$router.push({ path: url, query: params })
    },

    async back_answers () {
      wx.removeStorageSync('answers')
      const url = '/pages/score/main'
      const params = {
        gid: this.question.gid
      }
      this.$router.push({ path: url, query: params })
    }
  },

  onPullDownRefresh () {
    this.getList()
  },

  async onLoad () {
    this.init()
  },

  onShow () {
    this.init()
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

.submit {
  margin-top: 40rpx;
  .submit-btn {
    width: 95%;
    font-size: 16px;
  }
}

.group-btn {
  display: block;
  margin: 70rpx 20rpx;
  height: 100rpx;
  .mini-btn {
    width: 40%;
    font-size: 16px;
  }
  .left {
    float: left
  }
  .right {
    right: right;
  }
}
</style>
