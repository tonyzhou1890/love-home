<template>
  <article class="case fl tac">
    <h1 v-text="case_info.title"></h1>
    <p>
      <span v-html="case_info.style"></span>
      <span v-html="case_info.house"></span>
    </p>
    <section 
      v-for="(item,index) in case_info.content"
      :key="index"
      v-if="1 !== item.detail.length"
    >
      <h2 v-html="item.name"></h2>
      <div class="detail" 
        v-for="(pic,pic_index) in item.detail"
        :key="pic_index"
        v-if="pic.path"
      >
        <img :src="pic.path" :alt="item.name">
        <p
          v-text="pic.description"
          class="description"
        ></p>
      </div>
    </section>
    <div class="collect">
      <p 
        class="cp"
        v-text="collect"
        :class="{collected: '取消' === collect}"
        @click="c_collect"
      ></p>
    </div>
  </article>
  
</template>

<script>

export default {
  props: {
    case_info: {
      type: Object
    },
    collect: {
      type: String
    }
  },
  methods: {
    c_collect(){
      this.$emit('collection');
    }
  }
}
</script>

<style lang="less" scoped>
@darkGray: #666;
@red: #db3939;
@white: white;
@pink: #edd9d9;
.case {
  width: 900px;
  box-sizing: border-box;
  padding: 10px;
  color: @darkGray;
  font-size: 18px;
  h1 {
    font-size: 40px;
  }
  section {
    h2 {
      font-size: 25px;
    }
    img {
      max-width: 880px;
    }
    .description {
      line-height: 30px;
      padding: 10px 0 20px 0;
    }
  }
  .collect {
    padding: 40px;
    p {
      width: 90px;
      height: 90px;
      background-color: @red;
      color: @white;
      font-size: 25px;
      border-radius: 50%;
      line-height: 90px;
      margin: auto;
    }
    .collected {
      background: @pink;
    }
  }
}
</style>