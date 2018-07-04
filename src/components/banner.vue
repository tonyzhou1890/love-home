<template>
  <div id="banner"
    @mouseenter = "stop"
    @mouseleave = "start"
  >
    <div class="pic">
      <ul>
        <li 
          v-for="(img,index) in imgArray"
          :key = "index"
          class = "fl"
        >
          <a>
            <img :src="img" alt="" class="bgc">
          </a>
        </li>
      </ul>
    </div>
    <div class="dot">
      <span
        v-for="index in dotArray"
        :key = "index"
        @click = "slide(index)"
        :class="{active:index === curIndex}"
      ></span>
    </div>
    <div class="left" @click="slide('l')"><</div>
    <div class="right" @click="slide('r')">></div>
  </div>
</template>

<script>
import $ from "jquery";
export default {
  props: {
    banner: {
      type: Object,
      required: true
    }
  },
  data(){
    return {
      imgArray: [
        this.banner['2'].src,
        this.banner['0'].src,
        this.banner['1'].src,
        this.banner['2'].src,
        this.banner['0'].src
      ],
      dotArray: [
        1,
        2,
        3
      ],
      curIndex: 1
    }
  },
  methods: {
    slide:function(index){
      var $pic = $('.pic').first();
      var self = this;
      
      if($pic.is(":animated")){
        return
      }
      index = 'l' === index ? this.curIndex - 1 : index;
      index = 'r' === index ? this.curIndex + 1 : index;

      this.curIndex = index;

      var left = this.curIndex;
      if(this.curIndex >= this.imgArray.length - 1){
        this.curIndex = 1;
      }
      if(this.curIndex <= 0){
        this.curIndex = this.imgArray.length - 2;
      }

      $pic.animate({
        'margin-left': 0 - 1200 * left + 'px'
      },1000,function(){
        if(index >= self.imgArray.length - 1){
          $pic.css({
            'margin-left': '-1200px'
          })
        };
        if(index <= 0){
          $pic.css({
            'margin-left': '-3600px'
          })
        }
      });
    },
    start: function(){
      var self = this;
      // console.log(self);
      self.interval = setInterval(function(){
        // console.log(self);
        self.curIndex++;
        // if(self.curIndex === self.imgArray.length){
        //   self.curIndex = 0;
        // }
        self.slide(self.curIndex);
      },2000)
    },
    stop: function(){
      clearInterval(this.interval);
    }
  },
  mounted: function(){
    this.start();
  }
}
</script>

<style scoped>
#banner {
  position: relative;
  width: 1200px;
  height: 430px;
  overflow: hidden;
  margin: auto;
}

.pic {
  overflow: hidden;
  width: 6000px;
  margin-left: -1200px;
}

.pic img {
  width: 1200px;
  height: 430px;
}

.dot {
  text-align: center;
  margin-top: -50px;
}

.dot span {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  margin: 0 10px;
  display: inline-block;
  background-color: #fff;
  border: 2px solid #fff;
  cursor: pointer;
}

.dot span.active {
  background-color: #333;
  border: 2px solid linen;
}

.left, .right {
  position: absolute;
  top:50%;
  margin-top: -50px;
  height: 100px;
  width: 30px;
  line-height: 100px;
  text-align: center;
  background-color: #333;
  color: #fff;
  cursor: pointer;
}

.right {
  right: 0px;
}
</style>