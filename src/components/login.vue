<template>
  <div class="login por">
    <form>
      <label for=""><span class="nickname">昵称：</span>
        <input type="text" v-model="login_nickname" @blur="check_login_nickname"/><span v-show="login_nickname_unique" class="right">√</span>
      </label>
      <p class="tip"><span v-show="login_nickname_err">昵称不存在</span></p>
      <label for=""><span class="pwd">密码：</span>
        <input type="password" v-model="login_pwd"/>
      </label>
      <p class="tip"><span v-show="login_pwd_err">密码错误</span></p>
      <button @click="login">登录</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data(){
    return {
      login_nickname: '',
      login_nickname_unique: false,
      login_nickname_err: false,
      login_pwd: '',
      login_pwd_err: false
    }
  },
  methods: {
    check_login_nickname(){
      axios({
        method:'post',
        url:'./',
        headers:{
          'Content-type': 'aplication/x-www-form-urlencoded'
        },
        data: {
          login: 'query_user',
          username: this.login_nickname
        }
      }).then(response => {
        console.log(response.data);
      })
    },
    login(){
      if(!this.login_nickname || !this.login_pwd){
        this.login_nickname_err = true;
        this.login_pwd_err = true;
        return;
      }
      this.login_nickname_err = false;
      this.login_pwd_err = false;
    }
  }
}
</script>

<style lang="less" scoped >
@white: white;
@pink: #edd9d9;
@red: #db3939;
@darkGray: #666;

.login {
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