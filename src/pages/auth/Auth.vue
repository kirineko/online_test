<template>
  <div class="container">
    <div class="user-protocal">
      <h2 class="center">用户协议</h2>
      <p>1. 此小程序的用途仅限于在线测试</p>
      <p>2. 使用此小程序需要获取用户的微信昵称、头像数据</p>
      <p>3. 用户点击授权即表示同意小程序获取用户的微信数据</p>
      <p>4. 此小程序不会将用户微信数据使用在测试以外用途</p>
    </div>
    <button open-type="getUserInfo" class="weui-btn" type="warn" @click="getUserInfo">点击授权</button>
  </div>  
</template>


<script>
export default {
  methods: {
    getUserInfo (e) {
      this.$router.back()
    }
  }
}
</script>


<style lang='scss'>
.container {
    padding: 0 30rpx;
    .user-protocal {
      font-size: 18px;
      .center {
        font-size: 24px;
        text-align: center;
      }
    }
}
</style>

