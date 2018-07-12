<template>
  <div class="register por">
    <form>
      <label for=""><span class="nickname">昵称：</span>
        <input type="text" v-model.trim="register_nickname" @blur="check_register_nickname"
        @focus="nickname_focus"
        :disabled = "register_nickname_disabled"
        /><span v-show="register_nickname_unique" class="right">√</span>
      </label>
      <p class="tip"><span v-show="register_nickname_err">昵称已经存在</span></p>
      <label for=""><span class="pwd">密码：</span>
        <input type="password" v-model.trim="register_pwd"
        @focus = "pwd_focus"
        :disabled = "register_pwd_disabled"
        />
      </label>
      <p class="tip"><span v-show="register_pwd_err">密码错误<br />密码长度必须在6-20字符之间，可以使用数字、大小写字母， 但必须同时包含数字和字母.</span></p>
      <button @click="register"
        :disbaled = "register_button_disabled"
        class="cp"
        :class="{'light-gray':register_ing}"
        type="button"
      >注册</button>
    </form>
    <div class="success poa tac" v-show="register_success">
      <p class="success-right">√</p>
      <p class="success-text">注册成功</p>
    </div>
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
      register_pwd_err: false,
      register_button_disabled: false,
      register_nickname_disabled: false,
      register_pwd_disabled: false,
      register_success: false,
      register_ing: false
    }
  },
  methods: {
    check_register_nickname(){
      if(!this.register_nickname){
        this.register_nickname_err = true;
        return;
      }
      let postData = {
        login: 'query_user',
        nickname: this.register_nickname
      };
      this._POST(postData,response => {
        console.log(response.data);
        if(response.data.exist === 'n'){
          this.register_nickname_unique = true;
        }else if(response.data.exist === 'y'){
          this.register_nickname_err = true;
        }
      })
    },
    nickname_focus(){
      this.register_nickname_err = false;
      this.register_nickname_unique = false;
    },
    pwd_focus(){
      this.register_pwd_err = false;
    },
    register(){
      if(!this.register_nickname || !this.register_pwd){
        this.register_nickname_err = true;
        this.register_pwd_err = true;
        return;
      }
      if(this.register_nickname_err){
        return;
      }
      let reg = /^[0-9A-Za-z]{6,20}$/;
      if(!reg.test(this.register_pwd)){
        console.log('pwd_err');
        this.register_pwd_err = true;
        return;
      }
      this.register_nickname_err = false;
      this.register_pwd_err = false;

      let postData = {
        login: 'register',
        nickname: this.register_nickname,
        pwd: this.register_pwd
      };

      this.all_disable();

      this._POST(postData,response => {
        console.log(response.data);
        if(response.data.exist === 'y'){
          this.register_nickname_err = true;
        }else 
        if(response.data.response === 'error'){
          this.register_nickname_err = true;
          this.register_pwd_err = true;
          this.register_nickname_unique = false;
        }else if(response.data.response === 'success'){
          this.register_success = true;
        }else{
          alert('抱歉，网站发生错误！');
          return;
        }
        this.all_enable();
      })
    },
    all_disable(){
      this.register_nickname_disabled = true;
      this.register_pwd_disabled = true;
      this.register_button_disabled = true;
      this.register_ing = true;
    },
    all_enable(){
      this.register_nickname_disabled = false;
      this.register_pwd_disabled = false;
      this.register_button_disabled = false;
      this.register_ing = false;
    }
  }
}
</script>

<style lang="less" scoped >
@white: white;
@pink: #edd9d9;
@red: #db3939;
@darkGray: #666;
@lightGray: lightGray;

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
      &.light-gray {
        background-color: @lightGray;
      }
    }
  }
  .success {
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: @white;
    .success-right {
      width: 100px;
      height: 100px;
      line-height: 100px;
      font-size: 50px;
      margin: auto;
      margin-top: 100px;
      border-radius: 50%;
      background-color: @pink;
      color: @darkGray;
    }
    .success-text {
      margin: 50px 30px 10px 30px;
      font-size: 20px;
      color: @darkGray;
    }
  }
}
</style>