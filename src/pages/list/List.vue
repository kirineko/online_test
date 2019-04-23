<template>
  <div>
    <BookInfo :info="info"></BookInfo>
    <CommentList :comments="comments"></CommentList>
    <div class="comment" v-if="showAdd">
      <textarea v-model="comment"
        class="textarea"
        :maxlength="100"
        placeholder="请输入图书短评"
      >
      </textarea>
      <div class="location">
        地理位置:
        <switch color="#EA5A49" :checked="location" @change="getGeo"></switch>
        <span class="text-primary">{{location}}</span>
      </div>
      <div class="phone">
        手机型号:
        <switch color="#EA5A49" :checked="phone" @change="getPhone"></switch>
        <span class="text-primary">{{phone}}</span>
      </div>
      <button class="btn" @click="addComment">
        评论
      </button>
    </div>
    <div v-else class="text-footer">
      未登录或已经评论过啦
    </div>
    <button open-type="share" class="btn">转发给好友</button>
  </div>
</template>

<script>
import {get, post, showModal} from '@/util'
import BookInfo from '@/components/BookInfo'
import CommentList from '@/components/CommentList'

export default {
  data () {
    return {
      userinfo: {},
      bookid: '',
      info: {},
      comment: '',
      location: '',
      phone: '',
      comments: []
    }
  },

  components: {
    BookInfo,
    CommentList
  },

  computed: {
    showAdd () {
      if (!this.userinfo.openId) {
        return false
      }
      if (this.comments.filter(v => v.openid === this.userinfo.openId).length) {
        return false
      }
      return true
    }
  },

  methods: {
    async addComment () {
      if (!this.comment) {
        return
      }
      const data = {
        openid: this.userinfo.openId,
        bookid: this.bookid,
        comment: this.comment,
        location: this.location,
        phone: this.phone
      }
      try {
        await post('/weapp/addcomment', data)
        this.comment = ''
        this.getComments()
      } catch (e) {
        showModal('失败', e.msg)
      }
    },

    async getComments () {
      const comments = await get('/weapp/commentlist', {bookid: this.bookid})
      this.comments = comments.list
    },

    async getDetail () {
      const info = await get('/weapp/bookdetail', {id: this.bookid})
      wx.setNavigationBarTitle({
        title: info.title
      })
      this.info = info
    },

    getPhone (e) {
      if (e.target.value) {
        const phoneInfo = wx.getSystemInfoSync()
        this.phone = phoneInfo.model
      } else {
        this.phone = ''
      }
    },

    getGeo (e) {
      const ak = 'VbzwlzrgD8iIyRdWb0aXog6it3L5xkTA'
      let url = 'http://api.map.baidu.com/geocoder/v2/'
      if (e.target.value) {
        wx.getLocation({
          success: geo => {
            wx.request({
              url,
              data: {
                ak,
                location: `${geo.latitude},${geo.longitude}`,
                output: 'json'
              },
              success: res => {
                if (res.data.status === 0) {
                  this.location = res.data.result.addressComponent.city
                } else {
                  this.location = '未知地点'
                }
              }
            })
          }
        })
      } else {
        this.location = ''
      }
    }
  },

  mounted () {
    this.bookid = this.$root.$mp.query.id
    this.getDetail()
    this.getComments()
    const userinfo = wx.getStorageSync('userinfo')
    if (userinfo) {
      this.userinfo = userinfo
    }
    wx.showShareMenu()
  }
}
</script>

<style lang="scss">
.comment {
  margin-top: 10px;
  .textarea {
    width: 730rpx;
    height: 200rpx;
    background: #EEE;
    padding: 10rpx;
  }
  .location {
    padding: 5px 10px;
    margin-top: 10px;
  }
  .phone {
    padding: 5px 10px;
    margin-top: 10px;
  }
}
</style>>
