<template>
  <div class="login por">
    <form>
      <label for=""><span class="nickname">昵称：</span>
        <input type="text" v-model.trim="login_nickname" @blur="check_login_nickname"
        @focus="nickname_focus"
        :disabled = "login_nickname_disabled"
        /><span v-show="login_nickname_unique" class="right">√</span>
      </label>
      <p class="tip"><span v-show="login_nickname_err">昵称不存在</span></p>
      <label for=""><span class="pwd">密码：</span>
        <input type="password" v-model.trim="login_pwd"
          @focus="pwd_focus"
          :disabled = "login_pwd_disabled"
        />
      </label>
      <p class="tip"><span v-show="login_pwd_err">密码错误</span></p>
      <button @click="login"
        :disbaled = "login_button_disabled"
      >登录</button>
    </form>
    <div class="success poa tac" v-show="login_success">
      <p class="success-right">√</p>
      <p class="success-text">登录成功</p>
    </div>
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
      login_pwd_err: false,
      login_button_disabled: false,
      login_nickname_disabled: false,
      login_pwd_disabled: false,
      login_success: false,
      login_ing: false
    }
  },
  methods: {
    check_login_nickname(){
      // axios({
      //   method:'post',
      //   url:'./',
      //   headers:{
      //     'Content-type': 'application/x-www-form-urlencoded'
      //   },
      //   data: {
      //     login: 'query_user',
      //     username: this.login_nickname
      //   },
      //   transformRequest: [
      //     function(data){
      //       let newData = '';
      //       for (let i in data){
      //         newData += encodeURIComponent(i) + '=' + encodeURIComponent(data[i]) + '&';
      //       }
      //       console.log(newData);
      //       return newData;
      //     }
      //   ]
      // }).then(response => {
      //   console.log(response.data);
      // })
      let postData = {
        login: 'query_user',
        nickname: this.login_nickname
      };
      this._POST(postData,response => {
        console.log(response.data);
        if(response.data.exist === 'n'){
          this.login_nickname_err = true;
        }else if(response.data.exist === 'y'){
          this.login_nickname_err = false;
          this.login_nickname_unique = true;
        }
        
      })
    },
    nickname_focus(){
      this.login_nickname_err = false;
      this.login_nickname_unique = false;
    },
    pwd_focus(){
      this.login_pwd_err = false;
    },
    login(){
      if(!this.login_nickname || !this.login_pwd){
        this.login_nickname_err = true;
        this.login_pwd_err = true;
        return;
      }
      if(this.login_nickname_err){
        return;
      }
      //发送服务器验证
      //……
      let postData = {
        login: 'login',
        nickname: this.login_nickname,
        pwd: this.login_pwd
      };

      this.all_disable();

      this._POST(postData,response => {
        console.log(response.data);
        
        if(response.data.response === 'error'){
          this.login_pwd_err = true;
        }else if(response.data.errorMsg){
          alert('抱歉，网站发送错误！');
          return;
        }else{
          this.login_success = true;
        }
        this.all_enable();
      })
      
    },
    all_disable(){
      this.login_nickname_disabled = true;
      this.login_pwd_disabled = true;
      this.login_button_disabled = true;
      this.login_ing = true;
    },
    all_enable(){
      this.login_nickname_disabled = false;
      this.login_pwd_disabled = false;
      this.login_button_disabled = false;
      this.login_ing = false;
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