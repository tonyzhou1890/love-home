<template>
  <div class="chosen-case">
    <p class="tac">精选案例</p>
    <div v-for="cases in caseList" :key="cases.type" class="cases">
      <p>
        <span v-html="cases[0]"></span>
        <span class="fr"><a target="_blank" :href="cases.href">查看更多</a></span>
      </p>
      <cases :cases="cases[1]"></cases>
    </div>
  </div>
</template>

<script>
import cases from "./case4list.vue";
export default {
  props: {
    caseList: {
      type: Object,
      required: true
    }
  },
  components: {
    cases
  }
}
</script>

<style lang="less" scoped>
@red: #db3939;
.chosen-case {
  padding: 94px 0 0 0;
  > p:first-child {
    color: @red;
    font-size: 60px;
    padding: 0 0 40px 0;
  }
  .cases {
    > p:first-child {
      padding: 40px 0;
      > span:first-child {
        font-size: 40px;
        padding-left: 10px;
      }
      > span:last-child {
        font-size: 18px;
        padding-top: 20px;
      }
    }
  }
}
</style>