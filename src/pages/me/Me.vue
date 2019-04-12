<template>
    <div class="container">
        <div class="userinfo" @click="login">
            <img :src="userinfo.avatarUrl" alt="">
            <p>{{userinfo.nickName}}</p>
        </div>

        <button v-if="!userinfo.openId" open-type="getUserInfo" class="weui-btn" type="warn">授权登录</button>
        <div v-if="userinfo.openId">
          <button class="weui-btn" type="primary" @click="gotoExam" >开始考试</button>
          <button class="weui-btn" type="primary" >查看成绩</button>
          <div class="bottom">
            <button class="weui-btn" type="primary" @click="logout">退出登录</button>
          </div>
        </div>
    </div>
</template>

<script>
import qcloud from 'wafer2-client-sdk'
import {showSuccess} from '@/util'
import config from '@/config'

export default {

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
    login () {
      if (this.userinfo.openId) {
        return
      }
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
    },
    logout () {
      wx.removeStorageSync('userinfo')
      this.userinfo = {
        avatarUrl: '../../static/img/unlogin.png',
        nickName: '点击登录'
      }
    },
    gotoExam () {
      const url = '/pages/exam/main'
      wx.switchTab({
        url
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
    .bottom {
      position: fixed;
      bottom: 30rpx;
      width: 92.5%;
    }
}
</style>
