<template>
    <div class="container">
        <div class="userinfo" @click="login">
            <img :src="userinfo.avatarUrl" alt="">
            <p>{{userinfo.nickName}}</p>
        </div>

        <YearProgress></YearProgress>
        <button v-if="userinfo.openId" @click="scanBook" class="btn">添加图书</button>
        <button v-if="!userinfo.openId" open-type="getUserInfo" class="btn">授权登录</button>
    </div>
</template>

<script>
import YearProgress from '@/components/YearProgress'
import qcloud from 'wafer2-client-sdk'
import {showSuccess, post, showModal} from '@/util'
import config from '@/config'

export default {

  components: {
    YearProgress
  },

  data () {
    return {
      userinfo: {
        avatarUrl: '../../static/img/unlogin.png',
        nickName: '点击登录'
      }
    }
  },

  onShow () {
    const info = wx.getStorageSync('userinfo')
    if (info) {
      this.userinfo = info
    }
  },

  methods: {

    async addBook (isbn) {
      const res = await post('/weapp/addbook', {
        isbn,
        openId: this.userinfo.openId
      })
      showModal('添加成功', `${res.title}添加成功`)
    },

    scanBook () {
      wx.scanCode({
        success: (res) => {
          if (res.result) {
            this.addBook(res.result)
          }
        }
      })
    },
    login () {
      qcloud.setLoginUrl(config.loginUrl)

      qcloud.login({
        success: res => {
          wx.setStorageSync('userinfo', res)
          showSuccess('登录成功')
          this.userinfo = res
        },
        fail: err => {
          console.error(err)
        }
      })
    }
  }
}
</script>

<style lang="scss">
.container {
    padding: 0 30rpx;
    .userinfo {
        margin-top: 100rpx;
        text-align: center;
        img {
            width: 150rpx;
            height: 150rpx;
            margin: 20rpx;
            border-radius: 50%;
        }
    }
}

</style>
