<template>
  <div class="designer-list">
    <div class="designer tac fl" 
      v-for="(item,index) in designers"
      :key = "index"
    >
      <a :href="'#' === item.id ? 'javascript:;' : './?d_id=' + item.id" :target="'#' !== item.id ? '_blank' : ''"><img :src="item.thumb ? item.thumb : item.photo" :alt="item.name" class="bgc"></a><br />
      <a :href="'#' === item.id ? 'javascript:;' : './?d_id=' + item.id" :target="'#' !== item.id ? '_blank' : ''"><span v-html="item.name" class="ilb"></span></a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    designers: {
      type: Array,
      required: true
    }
  }
}
</script>

<style lang="less" scoped>
@darkGray: #666;

.designer-list {
  overflow: hidden;
  .designer {
    box-sizing: border-box;
    padding: 0 20px;
    color: @darkGray;
    width: 300px;
    img {
      width: 260px;
      height: 350px;
    }
    span {
      font-family: simsun;
      font-size: 16px;
      margin-top: 20px;
    }
  }
}
</style>