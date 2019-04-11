<template>
    <div>
        <TopSwiper :tops='tops'></TopSwiper>
        <Card :key="book.id" v-for="book in books" :book="book"></Card>
        <p class="text-footer" v-if="!more">
          ———————— 喵是有底线的 ————————
        </p>
    </div>
</template>

<script>
import { get } from '@/util'
import Card from '@/components/Card'
import TopSwiper from '@/components/TopSwiper'

export default {
  components: {
    Card,
    TopSwiper
  },

  data () {
    return {
      books: [],
      page: 0,
      more: true,
      tops: []
    }
  },

  methods: {
    async getList (init) {
      if (init) {
        this.page = 0
        this.more = true
      }
      wx.showNavigationBarLoading()
      const books = await get('/weapp/booklist', {'page': this.page})
      if (books.list.length < 10 && this.page > 0) {
        this.more = false
      }

      if (init) {
        this.books = books.list
        wx.stopPullDownRefresh()
      } else {
        this.books = this.books.concat(books.list)
      }
      wx.hideNavigationBarLoading()
    },

    async top () {
      const top = await get('/weapp/top')
      this.tops = top.list
    }
  },

  onReachBottom () {
    if (!this.more) {
      return false
    }
    this.page = this.page + 1
    this.getList()
  },

  onPullDownRefresh () {
    this.getList(true)
    this.top()
  },

  mounted () {
    this.getList(true)
    this.top()
  }
}
</script>

<style>

</style>
