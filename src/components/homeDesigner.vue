<template>
  <div class="home-designer">
    <p class="tac">入驻设计师</p>
    <div class="random">
      <p>
        <span class="random-text">随机推荐</span>
        <span class="change">换一批<i class="iconfont ilb icon" @click="getDesigner()" :class="{rotate:rotate}">&#xf004c;</i></span>
      </p>
      <designer :designers="randomDesigner" :key="'randomDesigner'"></designer>
    </div>
    <div class="new">
      <p>
        <span class="new-text">最新入驻</span>
      </p>
      <designer :designers="newDesigner" :key="'newDesigner'"></designer>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import designer from "./designer4list.vue";
export default {
  props: {
    designer: {
      type: Object,
      required: true
    }
  },
  components: {
    designer
  },
  data(){
    return {
      randomDesigner: this.designer.random_designer,
      newDesigner: this.designer.new_designer,
      rotate: false
    }
  },
  methods: {
    getDesigner(){
      this.rotate = true;
      axios.get('./?random=true').
      then(function(response){
        if(response.data){
          this.randomDesigner = response.data;
        }
        this.rotate = false;
      })
    }
  }
}
</script>

<style lang="less" scoped>
@red: #db3939;
@darkGray: #666;
@keyframes l-rotate {
  0% {
    transform: rotate(0deg);
  }
  50% {
    transform: rotate(180deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.home-designer {
  padding: 94px 0 0 0;
  > p:first-child {
    color: @red;
    font-size: 60px;
  }
  .random {
    padding: 60px 0 0 0;
    > p:first-child {
      padding: 0 0 40px 0;
      .random-text {
        padding: 0 5px 0 10px;
        font-size: 40px;
      }
      .change {
        font-family: simsun;
        font-size: 18px;
        .icon {
          margin-left: 10px;
        }
        .rotate {
          animation: l-rotate 2s linear infinite;
        }
      }
    }
  }
  .new {
    padding: 43px 0 0 0;
    > p:first-child {
      padding: 0 0 40px 0;
      .new-text {
        padding: 0 5px 0 10px;
        font-size: 40px;
      }
    }
  }
}
</style>