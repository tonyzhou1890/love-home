<template>
  <div class="case-sidebar tac">
    <p class="title" v-text="title"></p>
    <div class="case tac"
      v-for="(a_case,index) in cases"
      :key="index"
    >
      <a :href="a_case.id" target="_blank"><img :src="a_case.cover" :alt="a_case.title" class="cover"></a><br />
      <a :href="a_case.id" target="_blank"><span v-html="a_case.title" class="ellipse ilb"></span></a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String
    },
    cases: {
      type: Array
    }
  }
}
</script>

<style lang="less" scoped>
@darkGray: #666;

.case-sidebar {
  width: 300px;
  box-sizing: border-box;
  border: 2px solid @darkGray;
  padding: 8px;
  overflow: hidden;
  color: @darkGray;
  .title {
    font-size: 25px;
    padding-top: 18px;
  }
  .case {
    padding-top: 25px;
    img {
      width: 280px;
      height: 180px;
    }
    span {
      padding: 8px 0;
    }
  }
}
</style>