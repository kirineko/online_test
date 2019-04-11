<template>
  <div class="swiper">
    <swiper
      indicator-dots = 'true' 
      indicator-color = 'EA5A49'
      autoplay = 'true'
      interval = '6000'
      duration = '1000'
      circular = 'true'
    >
      <div v-for="(top, imgindex) in imgUrls" :key="imgindex">
        <swiper-item>
          <img
            v-for="img in top"
            class='slide-image' 
            :src="img.image"
            :key = "img.id" 
            @click='bookDetail(img)'
            alt="" 
            mode="aspectFit"
          >
        </swiper-item>
      </div>
    </swiper>
  </div>  
</template>

<script>
export default {
  props: ['tops'],
  computed: {
    imgUrls () {
      let res = this.tops
      return [res.slice(0, 3), res.slice(3, 6), res.slice(6)]
    }
  },
  mounted () {
    console.log(this.imgUrls)
  },
  methods: {
    bookDetail (item) {
      wx.navigateTo({
        url: '/pages/detail/main?id=' + item.id
      })
    }
  }
}
</script>

<style lang="scss">
.swiper {
  margin-top: 5px;
  .slide-image {
    width: 33%;
    height: 250rpx;
  }
}
</style>
