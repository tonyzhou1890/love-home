<template>
  <div class="body">
    <banner :banner="homedata.banner"></banner>
    <advantage></advantage>
    <homedesigner :designer="homedata.designer"></homedesigner>
    <chosencase :caseList="homedata.cases"></chosencase>
    <donate></donate>
  </div>
</template>


<script>
import banner from "./banner.vue";
import advantage from "./advantage.vue";
import homedesigner from "./homeDesigner.vue";
import chosencase from "./chosenCase.vue";
import donate from "./donate.vue";

export default {
  props: {
    homedata: {
      type: Object,
      required: true
    }
  },
  data(){
    return {

    }
  },
  components: {
    banner,
    advantage,
    homedesigner,
    chosencase,
    donate
  }
}
</script>

<style lang="less" scoped>
.body {
  width: 1200px;
  margin: auto;
}
</style>
