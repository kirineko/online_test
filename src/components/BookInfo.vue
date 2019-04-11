<template>
  <div class="bookinfo">
    <div class="thumb">
      <img class="back"
        :src="info.image" 
        mode="aspectFill"
      >
      <img class="img"
        :src="info.image"
        mode="aspectFit"
      >
      <div class="info">
        <div class="title">
          {{info.title}}
        </div>
        <div class="author">
          {{info.author}}
        </div>
      </div>
    </div>
    <div class="detail">
      <img :src="userinfo.image" class="avatar" mode="aspectFit">
      {{userinfo.name}}
      <div class="right text-primary">
        {{info.rate}}
        <Rate :value="info.rate"></Rate>
      </div>
    </div>
    <div class="detail">
      {{info.publisher}}
      <div class="right">
        {{info.price}}
      </div>
    </div>
    <div class="tags">
      <div class="badge" :key="tag" v-for="tag in info.tags">{{tag}}</div>
    </div>
    <div class="summary">
      <p v-for="(sum, i) in info.summary" :key="i">{{sum}}</p>
    </div>
  </div>
</template>

<script>
import Rate from '@/components/Rate'

export default {
  components: {
    Rate
  },
  props: ['info'],
  computed: {
    userinfo () {
      return this.info.user_info || {}
    }
  }
}
</script>

<style lang="scss">
.bookinfo {
  .badge {
    display: inline-block;
    margin: 5px;
    padding: 5px;
    border-radius: 10px;
    border: 1px #EA5A49 solid;
    color: #EA5A49;
  }
  .summary {
    margin-top: 10px;
    padding: 0 15px;
    p {
      text-indent: 2em;
      font-size: 14px;
    }
  }
  font-size: 14px;
  .right {
    float: right;
  }
  .detail {
    .avatar {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      vertical-align: middle;
    }
    padding: 5px 10px;
  }
  .thumb {
    width: 750rpx;
    height: 500rpx;
    overflow: hidden;
    position: relative;
    .back {
      filter: blur(15px);
      width: 100%;
    }
    .img {
      position: absolute;
      top: 30rpx;
      left: 0;
      height: 300rpx;
      width: 100%;
    }
    .info {
      color: white;
      position: absolute;
      top: 330rpx;
      left: 0;
      width: 100%;
      text-align: center;
      .title {
        font-size: 20px;
      }
      .author {
        font-size: 14px;
      }
    }
  }
}
</style>