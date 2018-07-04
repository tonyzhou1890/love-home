<template>
  <div class="register por">
    <form>
      <label for=""><span class="nickname">昵称：</span>
        <input type="text" v-model="register_nickname" @blur="check_register_nickname"/><span v-show="register_nickname_unique" class="right">√</span>
      </label>
      <p class="tip"><span v-show="register_nickname_err">昵称已经存在</span></p>
      <label for=""><span class="pwd">密码：</span>
        <input type="password" v-model="register_pwd"/>
      </label>
      <p class="tip"><span v-show="register_pwd_err">密码错误<br />密码长度必须在6-20字符之间，可以使用数字、大小写字母， 但必须同时包含数字和字母.</span></p>
      <button @click="register">注册</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data(){
    return {
      register_nickname: '',
      register_nickname_unique: false,
      register_nickname_err: false,
      register_pwd: '',
      register_pwd_err: false
    }
  },
  methods: {
    check_register_nickname(){

    },
    register(){
      if(!this.register_nickname || !this.register_pwd){
        this.register_nickname_err = true;
        this.register_pwd_err = true;
        return;
      }
      this.register_nickname_err = false;
      this.register_pwd_err = false;
    }
  }
}
</script>

<style lang="less" scoped >
@white: white;
@pink: #edd9d9;
@red: #db3939;
@darkGray: #666;

.register {
  background-color: @white;
  width: 500px;
  height: 480px;
  form {
    padding: 70px 0 0 50px;
    label {
      line-height: 40px;
      .nickname, .pwd {
        color: @darkGray;
        font-size: 20px;
      }
      input {
        width: 320px;
        height: 40px;
        box-sizing: border-box;
        border: 3px solid @pink;
        margin-left: 20px;
      }
      .right {
        margin-left: 10px;
        font-size: 12px;
      }
    }
    .tip {
      color: @red;
      font-size: 14px;
      line-height: 18px;
      height: 18px;
      padding: 30px 30px 30px 40px;
    }
    button {
      width: 180px;
      height: 50px;
      line-height: 50px;
      background: @pink;
      border: 0;
      outline: 0;
      color: @darkGray;
      font-size: 30px;
      margin: 80px 0 0 110px;
    }
  }
}
</style>