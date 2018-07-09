<template>
  <header>
    <div class="logo">
      <a href="./">
        <img :src="logoPath" alt="logo" class="cp">
      </a>
    </div>
    <div class="city">
      <div class="city-selected" 
        v-html="citySelected"
        @mouseenter = "enterCity"
        @mouseleave = "leaveCity"
      ></div><br />
      <div class="all-city hidden" 
        v-show = "showAllCity" 
        @mouseenter = "enterAreas"
        @mouseleave = "leaveAreas"
      >
        <ul>
          <li 
            v-for="area in areas" 
            :key = "area[0]"
            class = "area"
            :class = "{'area-selected-style':area[0] === areaSelected}"
            @click = "areaSelected = area[0]"
            v-html = "area[0]"
          ></li>
        </ul>
        <ul class = "cities-ul">
          <li
            v-for = "area in areas"
            :key = "area[0]"
            v-show = "area[0] === areaSelected"
          >
            <span 
              v-for="city in area[1]"
              :key = "city"
              class = "w_city"
              :class = "{'city-selected-style':city === citySelected}"
              @click = "citySelected = city"
              v-html = "city"
            ></span>
          </li>
        </ul>
      </div>
    </div>
    <div class="search">
      <input type="text" v-model = "searchKey">
      <select name="search-type" id="search-type" v-model="searchType">
        <option value="case">案例</option>
        <option value="designer">设计师</option>
      </select>
      <p
        @click="sorry"
      >搜索</p>
    </div>
    <div class="user">
      <p v-show = "!login" class="no-login">
        <a href="./login.html#/login" target="_blank"><span>登录</span></a>
        <span>/</span>
        <a href="./login.html#/register" target="_blank"><span>注册</span></a>
      </p>
      <div class="has-login hidden" v-show = "login">
        <img :src="profile" alt="头像" class="profile">
        <div class="info">
          <span class="nickname" v-html="nickname"></span>
          <hr>
          <a href="./panel.html" target="_blank">
            <i class="iconfont cp" alt = "设置">&#xe606;</i>
          </a>
          <a @click="sorry">
            <i class="iconfont cp" alt = "收藏">&#xe609;</i>
          </a>
          <a @click="logout">
            <i class="iconfont cp" alt = "退出">&#xe659;</i>
          </a>
          
        </div>
      </div>
    </div>
  </header>
</template>


<script>
import axios from "axios";

export default {
  props: {
    info: {
      type: Array,
      required: true
    }
  },
  data(){
    return {
      citySelected: '全国',
      hasEnter: false,
      logoPath: this.info[0].value,
      login: false,
      profile: this.info[2].value,
      searchKey: null,
      searchType: 'case',
      showAllCity: false,
      areaSelected: '东北',
      nickname:'',
      areas: [
        [
          "东北",
          [
            '全国','长春','大连','哈尔滨','沈阳'
          ]
        ],
        [
          '华北',
          [
            '全国','北京','济南','青岛','石家庄','天津'
          ]
        ],
        [
          '华东',
          [
            '全国','常州','杭州','淮安','合肥','连云港','马鞍山','南京','南通','宁波','上海','绍兴','宿迁','苏州','泰州','无锡','徐州','盐城','扬州','镇江'
          ]
        ],
        [
          '华中',
          [
            '全国','长沙','开封','武汉','郑州'
          ]
        ],
        [
          '华南',
          [
            '全国','澳门','东莞','海口','广州','深圳','香港'
          ]
        ],
        [
          '西南',
          [
            '全国','成都','重庆','昆明','柳州'
          ]
        ],
        [
          '西北',
          [
            '全国','兰州','西安','西宁','乌鲁木齐'
          ]
        ]
      ]
    }
  },
  methods:{
    enterCity(){
      this.showAllCity = true;
    },
    leaveCity(){
      setTimeout(
          () => {
            if(!this.hasEnter){
              this.showAllCity = false;
            }
          },100
        );
    },
    enterAreas(){
      this.hasEnter = true;
    },
    leaveAreas(){
      this.hasEnter = false;
      this.showAllCity = false;
    },
    search(){
      
    },
    sorry(){
      alert('此功能暂不可用！');
    },
    logout(){
      window.localStorage.removeItem('lh_token');
      this.login = false;
    }
  },
  created(){
    if(!window.localStorage.lh_token){
      return;
    }
    let token = encodeURIComponent(window.localStorage.lh_token);
    axios.get('./?token='+token)
      .then(response => {
        if(response.data.response === 'error'){
          localStorage.removeItem('lh_token');
          return;
        }else if(response.data.response === 'success'){
          this.nickname = response.data.nickname;
          this.profile = response.data.profile;
          this.login = true;
          if(response.data.token){
            localStorage.setItem('lh_token',response.data.token);
          }
        }
        
      });
  },
  mounted(){
    document.getElementsByClassName('all-city')[0].classList.remove('hidden');
    document.getElementsByClassName('has-login')[0].classList.remove('hidden');
  }
}
</script>


<style lang="less" scoped>
@white: white;
@darkGray: #666;
@pink: #edd9d9;
@red: #db3939;

header,
div,
ul,
li,
p {
  padding: 0;
  margin: 0;
}

.hidden {
  display: none;
}
  
header {
  width: 1200px;
  height: 170px;
  position: relative;
  overflow: hidden;
  margin: auto;
  > div {
    float: left;
  }
  li {
    list-style: none;
    float: left;
  }
  .logo {
    margin: 20px 0 0 30px;
    img {
      width: 180px;
      height: 112px;
    }
  }
  .city {
    margin: 80px 0 0 28px;
    position: relative;
    .city-selected {
      background-color: @white;
      width: 60px;
      height: 20px;
      box-sizing: border-box;
      border: 2px solid @darkGray;
      color: @darkGray;
      line-height: 16px;
      cursor: pointer;
      text-align: center;
      overflow: hidden;
    }
    .all-city {
      position: absolute;
      top: 20px;
      border: 1px solid @darkGray;
      background: @white;
      .area {
        background-color: @pink;
        width: 90px;
        height: 19px;
        line-height: 18px;
        color: @darkGray;
        border: 1px solid @darkGray;
        border-top: 0px;
        border-left: 0px;
        text-align: center;
        box-sizing: border-box;
        cursor: pointer;
        &:last-child {
          border-right: 0px;
        }
        &.area-selected-style {
          background-color: @white;
          border-bottom: 1px solid @white;
        }
      }
      .cities-ul {
        width: 630px;
        height: 49px;
        overflow: auto;
        span {
          display: inline-block;
          margin: 5px;
          padding: 5px;
          cursor: pointer;
          &.city-selected-style {
            background-color: @pink;
          }
        }
      }
    }
  }
  .search {
    margin: 60px 0 0 20px;
    // width: 500px;
    height: 40px;
    box-sizing: border-box;
    border: 3px solid @red;
    > input, > select, > p{
      float: left;
    }
    input {
      width: 355px;
      height: 34px;
      border: 0px;
      outline: 0px;
      box-sizing: border-box;
      padding: 3px;
      color: @darkGray;
      line-height: 28px;
    }
    select {
      background-color: @white;
      border: 2px solid @pink;
      outline: 0px;
      margin: 2px;
      width: 77px;
      height: 30px;
    }
    p {
      width: 57px;
      height: 34px;
      background-color: @red;
      color: @white;
      line-height: 34px;
      font-size: 18px;
      text-align: center;
      cursor: pointer;
    }
  }
  .user {
    margin: 30px 0 0 80px;
    width: 250px;
    height: 100px;
    background-color: @pink;
    .no-login {
      text-align: center;
      margin-top: 30px;
      font-size: 30px;
      line-height: 40px;
      span {
        color: @red;
        cursor: pointer;
      }
    }
    .profile {
      margin: 10px 0 0 10px;
      width: 80px;
      height: 80px;
      box-sizing: border-box;
      border: 5px;
      border-radius: 50%;
    }
    .info {
      margin: 18px 20px 0 0;
      width: 120px;
      color: @darkGray;
      float: right;
      .nickname {
        display: block;
        text-align: center;
        font-size: 22px;
        line-height: 25px;
      }
      hr {
        background-color: @white;
        height: 2px;
        border: 0px;
        outline: 0px;
      }
      i {
        font-size: 25px;
        padding: 6px;
        &:hover {
          color: @red;
        }
      }
    }
  }
}

</style>