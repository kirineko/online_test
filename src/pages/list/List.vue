<template>
  <div>
    <div v-if="userinfo.openId" class="container">
      <mp-cell-group title="进行中的测试">
        <Card v-for="exam in examlist_ing"
          :key = "exam.gid"
          :exam = "exam"
          imageurl = "../../static/img/room-active.png"
        >
        </Card>
      </mp-cell-group>      

      <mp-cell-group title="完成的测试">
        <Card v-for="exam in examlist_ed"
          :key = "exam.gid"
          :exam = "exam"
          imageurl = "../../static/img/todo-active.png"
        >
        </Card>
      </mp-cell-group>   
    </div>
    <div v-else>
      <div @click="backlogin">
        <mp-panel
          :data-source="datasource"
          title="需要登录"
          type="text"
        />
      </div>  
    </div>
  </div>
</template>

<script>
import {get} from '@/util'
import MpCellGroup from 'mp-weui/packages/cell-group'
import MpPanel from 'mp-weui/packages/panel'
import Card from '@/components/Card'

export default {
  data () {
    return {
      userinfo: {},
      examlist_ing: [],
      examlist_ed: [],
      datasource: [
        {
          content: '需要登录才能进行测试',
          title: '请先登录'
        }
      ]
    }
  },

  components: {
    MpCellGroup,
    MpPanel,
    Card
  },

  methods: {
    init () {
      const userinfo = wx.getStorageSync('userinfo')
      if (userinfo) {
        this.userinfo = userinfo
      }
      this.getExams()
    },

    async getExams () {
      const exams = await get('/weapp/exam', {openid: this.userinfo.openId})
      this.examlist_ing = exams.exams_ing
      this.examlist_ed = exams.exams_ed
    },

    backlogin () {
      const url = '/pages/me/main'
      wx.switchTab({
        url
      })
    }
  },

  onLoad () {
    this.init()
  },

  onShow () {
    this.init()
  }
}
</script>

<style lang="scss">

</style>>
