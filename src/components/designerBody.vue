<template>
  <div class="db">
    <designer
      :designer="designer"
      :user="user"
      v-if="'no_result' !== response && 'error' !== response"
    ></designer>
    <div class="works" 
      v-if="'no_result' !== response && 'error' !== response"
    >
      <h2 class="tac">作品</h2>
      <p 
        v-if="'no_case' === response"
        class="tac"
      >暂无作品</p>
      <worklist
        :cases="cases"
      ></worklist>
    </div>
    <h1
      v-if="'no_result' === response"
      class="tac"
    >无记录</h1>
    <h1
      v-if="'error' === response"
      class="tac"
    >发生错误</h1>
  </div>
  
</template>

<script>
import designer from "./designerInfo.vue";
import worklist from "./case4list.vue";

export default {
  props: {
    designer: {
      type: Object
    },
    user: {
      type: Object
    },
    cases: {
      type: Array
    },
    response: {
      type: String
    }
  },
  components: {
    designer,
    worklist
  }
}
</script>

<style lang="less">
.case-list {
  .case {
    margin-bottom: 30px;
  }
}
</style>

<style lang="less" scoped>
@darkGray: #666;
.db {
  width: 1200px;
  min-height: 1000px;
  margin: auto;
  color: @darkGray;
  .works {
    > h2 {
      clear: both;
      padding: 40px 0 20px 0;
    }
  }
}
</style>

<style lang="less">
.case-list {
  .case {
    margin-bottom: 30px;
  }
}
</style>