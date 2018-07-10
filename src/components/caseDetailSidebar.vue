<template>
  <div class="sidebar fl">
    <div class="the-designer">
      <p class="title tac">本案例设计师</p>
      <img :src="designer.photo" :alt="designer.name" class="photo">
      <p class="item">
        <span class="item">姓名：</span>
        <span v-text="designer.name"></span>
      </p>
      <p class="item">
        <span>年龄：</span>
        <span v-text="age"></span>
      </p>
      <p class="item">
        <span>性别：</span>
        <span v-text="gender"></span>
      </p>
      <p class="item">
        <span>毕业院校：</span>
        <span v-text="designer.education"></span>
      </p>
      <p class="item">
        <span>从业时间：</span>
        <span v-text="working"></span>
      </p>
      <p class="item">
        <span>擅长风格：</span>
        <span 
          v-for="style in designer.style"
          v-text="style.name"
          :key="style.name"
          v-if="style.selected"
          class="style ilb"
        ></span>
      </p>
      <p class="item">
        <span>简介：</span>
        <span v-text="designer.introduction"></span>
      </p>
      <p class="item">
        <span>咨询费：</span>
        <span v-text="designer.counseling"></span>
        <span>/小时</span>
      </p>
      <p class="item">
        <span>设计费：</span>
        <span v-text="designer.design"></span>
        <span>/平米</span>
      </p>
      <div
        class="contact-button cp tac"
        @click="contact"
      >联系设计师</div>
      <div class="contact-area tac"
        v-show="show_contact"
      >
        <div class="contact-cover">
          <div class="contact-board poa">
            <div>
              <p
                v-if="'取消' === collect && d_contact.tel"
              >座机：
                <span
                  v-text="d_contact.tel"
                ></span>
              </p>
              <p
                v-if="'取消' === collect && d_contact.phone"
              >手机：
                <span
                  v-text="d_contact.phone"
                ></span></p>
              <p
                v-if="'取消' === collect && d_contact.qq"
              >QQ：
                <span
                  v-text="d_contact.qq"
                ></span></p>
              <p
                v-if="'取消' === collect && d_contact.wechat"
              >微信：
                <span
                  v-text="d_contact.wechat"
                ></span></p>
              <p
                v-if="!has_login"
              >请<a href="./login.html" target="_blank">登录</a></p>
              <p
                v-if="('收藏' === collect || '操作中' === collect) && has_login"
                >请先收藏此案例</p>
              <p
                v-if="'错误' === collect && has_login"
                >发生错误</p>
            </div>
            
            <div class="close cp poa"
              @click="close"
            >X</div>
          </div>
        </div>
      </div>
    </div>
    <casesidebar
       :cases="cases"
       :title="title"
       class="cases"
    ></casesidebar>
  </div>
</template>

<script>
import casesidebar from "./caseSidebar.vue";

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
    collect: {
      type: String
    }
  },
  components: {
    casesidebar
  },
  data(){
    return {
      title: '此设计师的案例',
      has_login: true,
      show_contact: false,
      d_contact: {
        tel: '',
        phone: '',
        qq: '',
        wechat: ''
      },
      has_contact: false
    }
  },
  computed: {
    age(){
      return Math.floor(new Date().getFullYear() - this.user.birth.split('_')[0] + (new Date().getMonth() - this.user.birth.split('_')[1])/12) + '岁';
    },
    gender(){
      return this.user.gender === 'man' ? '男' : '女';
    },
    working(){
      return Math.floor(new Date().getFullYear() - this.designer.working_years.split('_')[0] + ( new Date().getMonth() - this.designer.working_years.split('_')[1] ) /12 ) + '年' + (new Date().getMonth() - this.designer.working_years.split('_')[1] + 12)%12 + '月';
    }
  },
  methods: {
    contact(){
      if(!this.has_contact && '取消' === this.collect){
        let post_data = {
          d_contact: this.designer.id
        };
        this._POST(post_data,response => {
          console.log(response.data);
          if(response.data.contact){
            let c = response.data.contact;
            this.d_contact.tel = c.tel.value;
            this.d_contact.phone = c.phone.value;
            this.d_contact.qq = c.qq.value;
            this.d_contact.wechat = c.wechat.value;
          }
          this.has_contact = true;
          this.show_contact = true;
        });

      }else{
        this.show_contact = true;
      }
      
      if(!window.localStorage.lh_token){
        this.has_login = false;
        return;
      }
      if('收藏' === this.collect){
        return;
      }
    },
    close(){
      this.show_contact = false;
    }
  }
}
</script>

<style lang="less" scoped>
@red: #db3939;
@darkGray: #666;
@white: white;
@pink: #edd9d9;

.sidebar {
  .the-designer {
    width: 300px;
    box-sizing: border-box;
    border: 2px solid @darkGray;
    padding: 8px;
    margin-bottom: 20px;
    .title {
      font-size: 25px;
      padding: 8px 0 25px 0;
    }
    .photo {
      width: 280px;
      height: 380px;
      margin-bottom: 20px;
    }
    .item {
      font-size: 18px;
      line-height: 28px;
      .style {
        padding: 3px;
        background: @pink;
      }
    }
    .contact-button {
      width: 220px;
      height: 60px;
      background-color: @red;
      color: @white;
      font-size: 40px;
      line-height: 60px;
      margin: 30px;
    }
    .contact-area {
      .contact-cover {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        .contact-board {
          width: 240px;
          height: 200px;
          line-height: 200px;
          background: @white;
          border: 5px solid @pink;
          left: 50%;
          top: 50%;
          margin-left: -120px;
          margin-top: -100px;
          vertical-align: middle;
          display: table;
          > div:first-child {
            display: table-cell;
            vertical-align: middle;
          }
          p {
            line-height: 35px;
          }
          a {
            color: @red;
          }
          .close {
            width: 16px;
            height: 16px;
            font-size: 12px;
            line-height: 16px;
            border-radius: 50%;
            top: -8px;
            right: -8px;
            color: @white;
            background: @darkGray;
            &:hover {
              background: @red;
            }
          }
        }
      }
    }
  }
  .cases {
    margin-bottom: 20px;
  }
}
</style>