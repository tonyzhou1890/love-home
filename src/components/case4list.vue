<template>
  <div class="case-list">
    <div class="case tac fl"
      v-for="(a_case,index) in cases"
      :key="index"
    >
      <a :href="'#' === a_case.id ? 'javascript:void(0)' : './?c_id=' + a_case.id" target="_blank"><img :src="a_case.cover" :alt="a_case.title" class="cover"></a><br />
      <a :href="'#' === a_case.id ? 'javascript:void(0)' : './?c_id=' + a_case.id" target="_blank"><span v-html="a_case.title" class="ellipse ilb"></span></a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    cases: {
      type:Array,
      required: true
    }
  }
}
</script>

<style lang="less" scoped>
.case-list {
  overflow: hidden;
  .case {
    padding: 0 20px;
    box-sizing: border-box;
    width: 300px;
    .cover {
      width: 260px;
      height: 170px;
    }
    span {
      font-family: simsun;
      font-size: 16px;
      padding-top: 20px;
    }
  }
}


</style>