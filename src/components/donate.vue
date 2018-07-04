<template>
  <div class="donate tac">
    <p class="cp" @click="thanks">捐赠</p>
  </div>
</template>

<script>
export default {
  methods: {
    thanks(){
      alert('谢谢，不过捐赠功能尚未上线！');
    }
  }
}
</script>

<style lang="less" scoped>
@red: #db3939;
@pink: #edd9d9;
.donate {
  padding: 80px 0 70px 0;
  > p {
    display: inline-block;
    width: 250px;
    height: 80px;
    line-height: 80px;
    background-color: @red;
    color: @pink;
    font-size: 55px;
    border-radius: 15px;
  }
}
</style>