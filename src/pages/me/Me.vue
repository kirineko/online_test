<template>
    <div class="container">
        <div class="userinfo">
            <img :src="userinfo.avatarUrl" alt="">
            <p>{{userinfo.username}}</p>
        </div>

        <div v-if="!userinfo.openId">
          <div class="weui-cells__title">用户登录</div>
          <div class="weui-cells weui-cells_after-title">
            <MpField
              placeholder="请输入学号"
              label="学号"
              type="number"
              v-model="usernumber"
            />
            <MpField
              placeholder="请输入密码"
              label="密码"
              type="number"
              password="true"
              v-model="password"
            />
          </div>
          <button class="weui-btn" type="primary" @click="login">点击登录</button>
        </div>

        <div v-if="userinfo.openId">
          <button class="weui-btn" type="primary" @click="gotoExam">开始考试</button>
          <div class="bottom">
            <button class="weui-btn" type="primary" @click="logout">退出登录</button>
          </div>
        </div>
    </div>
</template>

<script>
import qcloud from 'wafer2-client-sdk'
import {showSuccess, post} from '@/util'
import config from '@/config'
import MpField from '@/components/Field'

export default {

  components: {
    MpField
  },

  data () {
    return {
      userinfo: {
        username: '游客',
        avatarUrl: '../../static/img/unlogin.png'
      },
      usernumber: '',
      password: ''
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
        success: async res => {
          let user = await post('/weapp/auth', {
            openid: res.openId,
            usernumber: this.usernumber,
            password: this.password
          })
          res.username = user.username
          wx.setStorageSync('userinfo', res)
          showSuccess('登录成功')
          this.userinfo = res
        },
        fail: err => {
          console.log(err)
          this.showLoginModal()
        }
      })
    },
    async logout () {
      let result = await post('/weapp/unbind', {
        openid: this.userinfo.openId
      })
      wx.removeStorageSync('userinfo')
      this.userinfo = {
        username: '游客',
        avatarUrl: '../../static/img/unlogin.png'
      }
      this.usernumber = ''
      this.password = ''
      showSuccess(result.msg)
    },
    gotoExam () {
      const url = '/pages/list/main'
      wx.switchTab({
        url
      })
    },

    showLoginModal () {
      wx.showModal({
        title: '提示',
        content: '你还未登录，登录后可获得完整体验',
        confirmText: '一键登录',
        success (res) {
          // 点击一键登录，去授权页面
          if (res.confirm) {
            wx.navigateTo({
              url: '/pages/auth/main'
            })
          }
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
    .bottom {
      position: fixed;
      bottom: 30rpx;
      width: 92.5%;
    }
}
</style>
