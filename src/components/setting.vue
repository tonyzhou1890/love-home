<template>
  <div class="setting">
    <ul>
      <li>
        <span>昵称：</span><input type="text" v-model="info.nickname" @blur="check_nickname"/>
        <span class="error" v-show="judge_mark.nickname_error">!</span>
        <span class="explain" v-show="judge_mark.nickname_error">必填(或昵称已经被注册)</span>
      </li>
      <li><span>出生年月：</span>
        <select name="birth_year" id="birth_year" v-model="info.birth.year">
          <option 
            v-for="i in birth_year_list" 
            :value="i"
            :key = "i"
            v-html = "i">
          </option>
        </select>
        <select name="birth_month" id="birth_month" v-model="info.birth.month">
          <option 
            v-for="i in birth_month_list" 
            :value="i"
            :key = "i"
            v-html = "i">
          </option>
        </select>
        <span class="error" v-show="judge_mark.birth_error">!</span>
        <span class="explain" v-show="judge_mark.birth_error">必填</span>
      </li>
      <li class="gender"><span>性别：</span>
        <label><span>男：</span>
          <input type="radio" v-model="info.gender" value="man">
        </label>
        <label><span>女：</span>
          <input type="radio" v-model="info.gender" value="woman">
        </label>
      </li>
      <li class="profile por"><span>头像：</span>
        <img  :src="info.profile"/>
        <input type="file" class="poa cp" @change="set_pic($event,'profile')">
        <span class="error" v-show="judge_mark.profile_error">!</span>
        <span class="explain">宽高比1:1,最小宽度50像素，最大宽度100像素，文件大小不得超过20K</span>
      </li>
    </ul>
    <div class="to-be-designer" v-show="!is_designer"><input type="checkbox" v-model="to_be_designer"><span>我要成为设计师</span></div>
    <ul v-show="to_be_designer">
      <li><span>姓名：</span><input v-model="info.name" @blur="check_name"/>
        <span class="error" v-show="judge_mark.name_error">!</span>
        <span class="explain" v-show="judge_mark.name_error">必填</span>
      </li>
      <li class="photo por"><span>照片：</span>
        <img :src="info.thumb ? info.thumb : info.photo"/>
        <input type="file" class="poa cp" @change="set_pic($event,'photo')">
        <span class="error" v-show="judge_mark.photo_error">!</span>
        <span class="explain">宽高比73:100,最小宽度560像素，最大宽度840像素，文件大小不得超过200K</span>
      </li>
      <li><span>毕业院校：</span><input v-model="info.education" @blur="check_education"/>
        <span class="error" v-show="judge_mark.education_error">!</span>
        <span class="explain" v-show="judge_mark.education_error">必填</span>
      </li>
      <li><span>从业时间：</span>
        <select name="working_year" id="working_year" v-model="info.working.year">
          <option 
            v-for="i in working_year_list" 
            :value="i"
            :key = "i"
            v-html = "i">
          </option>
        </select>
        <select name="working_month" id="working_month" v-model="info.working.month">
          <option 
            v-for="i in birth_month_list" 
            :value="i"
            :key = "i"
            v-html = "i">
          </option>
        </select>
        <span class="error" v-show="judge_mark.working_error">!</span>
        <span class="explain" v-show="judge_mark.working_error">必填</span>
      </li>
      <li><span>城市：</span>
        <select name="area" id="area" v-model="info.city.area">
          <option 
          v-for="item in areas"
          :value="item[0]"
          :key="item[0]"
          v-html="item[0]">
          </option>
        </select>
        <select name="city" id="city" v-model="info.city.city">
          <option
          v-for="item in cur_area[0][1]"
          :value="item"
          v-html="item">
          </option>
        </select>
        <span class="error" v-show="judge_mark.city_error">!</span>
        <span class="explain" v-show="judge_mark.city_error">必填</span>
      </li>
      <li class="fee"><span>咨询费：</span><input v-model="info.counseling" @blur="check_counseling"/>/小时
        <span class="error" v-show="judge_mark.counseling_error">!</span>
        <span class="explain" v-show="judge_mark.counseling_error">必填（数字）</span>
      </li>
      <li class="fee"><span>设计费：</span><input v-model="info.design" @blur="check_design"/>/平方
        <span class="error" v-show="judge_mark.design_error">!</span>
        <span class="explain" v-show="judge_mark.design_error">必填（数字）</span>
      </li>
      <li class="expert-style"><span>擅长风格：</span>
        <div class="ilb">
          <label v-for="item in info.style"><input type="checkbox" v-model="item.selected" @change="check_style"><span v-html="item.name"></span></label>
        </div>
        <span class="error" v-show="judge_mark.style_error">!</span>
        <span class="explain">最少一项，最多三项</span>
      </li>
      <li class="introduction"><span>介绍：</span><textarea v-model="info.introduction" @change="check_introduction"/>
        <p>还剩<span :class="{intro_error:judge_mark.introduction_error}" v-html="200 - info.introduction.length"></span>字<span class="error" v-show="judge_mark.introduction_error">!</span></p>
      </li>
      <li v-for="item in info.contact"><span v-html="item.name + ':'"></span><input v-model="item.value" @blur="check_contact"/>
        <span class="error" v-show="judge_mark.contact_error">!</span>
        <span class="explain" v-show="judge_mark.contact_error">正确联系方式,最少一个</span>
      </li>
    </ul>
    <button class="sure cp" @click="sure" v-html="button_text"></button>
  </div>
</template>

<script>
import axios from "axios";

export default {
  // props: {
  //   origin_info: {
  //     type: Object
  //   }
  // },
  data(){
    return {
      info: {
        nickname: '',
        birth: {
          year: new Date().getFullYear() - 1,
          month: 1
        },
        gender: '',
        profile: '',
        name: '',
        photo: '',
        thumb: '',
        education: '',
        working: {
          year: new Date().getFullYear(),
          month: 1
        },
        city: {
          area: '华东',
          city: '上海'
        },
        counseling: '',
        design: '',
        style: {
          modern: {
            name: '现代简约',
            selected: false
          },
          amercia: {
            name: '美式',
            selected: false
          },
          europe: {
            name: '欧式',
            selected: false,
          },
          north_europe: {
            name: '北欧',
            selected: false
          },
          mediterranean: {
            name: '地中海',
            selected: false,
          },
          china: {
            name: '中式',
            selected: false
          },
          new_china: {
            name: '新中式',
            selected: false
          },
          japan: {
            name: '和式',
            selected: false
          },
          mix: {
            name: '混搭',
            selected: false
          }
        },
        introduction: '',
        contact: {
          tel: {
            name: '电话',
            value: ''
          },
          phone: {
            name: '手机',
            value: ''
          },
          qq: {
            name: 'QQ',
            value: ''
          },
          wechat: {
            name: '微信',
            value: ''
          }
        }
      },
      areas: [
        [
          "东北",
          [
            '长春','大连','哈尔滨','沈阳'
          ]
        ],
        [
          '华北',
          [
            '北京','济南','青岛','石家庄','天津'
          ]
        ],
        [
          '华东',
          [
            '常州','杭州','淮安','合肥','连云港','马鞍山','南京','南通','宁波','上海','绍兴','宿迁','苏州','泰州','无锡','徐州','盐城','扬州','镇江'
          ]
        ],
        [
          '华中',
          [
            '长沙','开封','武汉','郑州'
          ]
        ],
        [
          '华南',
          [
            '澳门','东莞','海口','广州','深圳','香港'
          ]
        ],
        [
          '西南',
          [
            '成都','重庆','昆明','柳州'
          ]
        ],
        [
          '西北',
          [
            '兰州','西安','西宁','乌鲁木齐'
          ]
        ]
      ],
      judge_mark: {
        nickname_error: false,
        birth_error: false,
        profile_error: false,
        photo_error: false,
        education_error: false,
        working_error: false,
        city_error: false,
        counseling_error: false,
        design_error: false,
        style_error: false,
        introduction_error: false,
        contact_error: false,
        name_error: false
      },
      birth_year_list: Array(100).fill(new Date().getFullYear() - 100).map((v,i) => v + i + 1),
      birth_month_list: Array(12).fill(1).map((v,i) => v + i),
      origin_info: null,
      is_designer: false,
      to_be_designer: false,
      button_text: '确定',
    };
   
  },
  computed: {
    working_year_list: function(){
      // console.log(this);
      // console.log(this.info.birth.year);
      let len = new Date().getFullYear() - this.info.birth.year;
      // console.log(len);
      return Array(len).fill(this.info.birth.year).map((v,i) => + v + i + 1);
    },
    // origin_info: function(){

    //   return this.$store.state.origin_info;
    // }
    cur_area: function(){
      return this.areas.filter(val => {
        return val[0] === this.info.city.area;
      });
    }
  },
  methods: {
    //初始化信息
    init_info(){  
      // console.log(this);
      // console.log(this.info);
      // console.log(this.origin_info);

      //初始化通用信息
      let u_info = this.origin_info.user
      //昵称
      this.info.nickname = u_info.nickname;
      //出生年月
      if(u_info.birth){
        this.info.birth.year = u_info.birth.split('_')[0];
        this.info.birth.month = u_info.birth.split('_')[1];
      }
      //性别
      if(u_info.gender){
        this.info.gender = u_info.gender === '男' ? 'man' : 'woman';
      }
      //头像
      this.info.profile = u_info.profile;

      //是否是设计师
      if(this.origin_info.designer){
        console.log('is designer');
        this.is_designer = true;
        this.to_be_designer = true;
        //初始化设计师信息
        let d_info = this.origin_info.designer;
        this.info.name = d_info.name;
        this.info.photo = d_info.photo;
        this.info.thumb = d_info.thumb;
        this.info.counseling = d_info.counseling;
        this.info.design = d_info.design;
        //工作时间
        if(d_info.working_years){
          this.info.working.year = d_info.working_years.split('_')[0];
          this.info.working.month = d_info.working_years.split('_')[1];
        }
        this.info.education = d_info.education;
        //城市
        if(d_info.city){
          this.info.city.area = d_info.city.split('_')[0];
          this.info.city.city = d_info.city.split('_')[1];
        }
        
        this.info.introduction = d_info.introduction ? d_info.introduction : '';
        //擅长风格
        if(d_info.style){
          for(let key in d_info.style){
            this.info.style[key].selected = d_info.style[key].selected;
          }
        }
        //联系方式
        if(d_info.contact){
          for(let key in d_info.contact){
            this.info.contact[key].value = d_info.contact[key].value;
          }
        }
      }
      // console.log(this.info);
    },
    // con(d){
    //   console.log(d);
    // },
    sure(){
      if('上传中' === this.button_text || '成功' === this.button_text || '后台错误' === this.button_text){
        return;
      }
      let to_check = null;
      if(this.to_be_designer){  //想要成为或者已经是设计师
        this.check_nickname();
        this.check_name();
        this.check_education();
        this.check_counseling();
        this.check_design();
        this.check_style();
        this.check_introduction();
        this.check_contact();
        to_check = [
          this.judge_mark.nickname_error,
          this.judge_mark.name_error,
          this.judge_mark.education_error,
          this.judge_mark.counseling_error,
          this.judge_mark.design_error,
          this.judge_mark.style_error,
          this.judge_mark.introduction_error,
          this.judge_mark.contact_error
        ];
      }else{
        this.check_nickname();
        to_check = [
          this.judge_mark.nickname_error
        ];
      }
      if(to_check.filter(val => val).length){
        alert('信息有错误');
        return;
      }
      //聚合信息
      let send_obj = {
        user: {
          nickname: this.info.nickname,
          birth: this.info.birth.year + '_' + this.info.birth.month,
          gender: this.info.gender,
          profile: this.info.profile,
          token: this.origin_info.user.token
        }
      };
      if(this.to_be_designer){
        send_obj.designer = {
          name: this.info.name,
          photo: this.info.photo,
          thumb: this.info.thumb,
          education: this.info.education,
          working_years: this.info.working.year + '_' + this.info.working.month,
          city: this.info.city.area + '_' + this.info.city.city,
          counseling: this.info.counseling,
          design: this.info.design,
          style: this.info.style,
          introduction: this.info.introduction,
          contact: this.info.contact
        }
      }

      //ie9阻止上传
      if(!window.FileReader){
        alert("您的浏览器版本过低，不支持上传。");
        return;
      }

      console.log(send_obj);
      let postData = {
        setting: this.to_be_designer,
        data: JSON.stringify(send_obj)
      }
      this.button_text = '上传中';
      this._POST(postData,response => {
          console.log(response.data);
          if('success' === response.data.response){
            this.button_text = '成功';
          }else{
            this.button_text = '后台错误';
          }
        });
      console.log(send_obj);
      console.log(this.info);
    },
    set_pic(e,which){
      e = e || event;
      let t = e.target;
      // console.log(e);
      // console.log(t);
      if(!t.value){
        return;
      }
      let pic = {};
      
      if(window.FileReader){  //  如果支持FileReader
        pic = t.files[0];
      }else{  //低版本IE兼容
        try{
          t.select();
          t.blur();
          let path = document.selection.createRange().text;
          let fso = new ActiveXObject("Scripting.FileSystemObject");
          pic.path = path;
          pic.size = fso.GetFile(path).size;
          pic.type = fso.GetFile(path).type;
        }catch(e){
          alert(e+"\n"+"如果错误为：Error:Automation 服务器不能创建对象；"+"\n"+"请按以下方法配置浏览器："+"\n"+"请打开【Internet选项-安全-Internet-自定义级别-ActiveX控件和插件-对未标记为可安全执行脚本的ActiveX控件初始化并执行脚本（不安全）-点击启用-确定】");
        }
      }
      // console.log(pic);

      // let p = new Promise((resolve,reject) => {
        
      //   resolve(this.judge_pic(pic,which));
      // });
      let rule = {};
      if('profile' === which){
        rule = {
          type: [
            'jpeg',
            'gif',
            'png'
          ],
          size: 20* 1024,
          min_width: 50,
          max_width: 100,
          min_height: 50,
          max_height: 100,
          min_ratio: 1,
          max_ratio: 1
        };
      }else{
        rule = {
          type: [
            'jpeg',
            'gif',
            'png'
          ],
          size: 200* 1024,
          min_width: 560,
          max_width: 560 * 1.5,
          min_height: 760,
          max_height: 760 * 1.5,
          min_ratio: 0.73,
          max_ratio: 0.73
        };
      }
      this._CHECK_PIC(rule,pic)
        .then(result => {
          console.log(result);
          if(result.right){
          
            if('profile' === which){
              this.judge_mark.profile_error = false;
              this.info.profile = result.data;
            }else{
              this.judge_mark.photo_error = false;
              this.info.photo = result.data;
              this.info.thumb = '';
            }
          }else{
            if('profile' === which){
              this.judge_mark.profile_error = true;
            }else{
              this.judge_mark.photo_error = true;
            }
          }
      });
      // p.then(result => {
      //   console.log(result);
      //   if(result.right){
      //   this.info.profile = result.data;
      //   if('profile' === which){
      //     this.judge_mark.profile_error = false;
      //   }else{
      //     this.judge_mark.photo_error = false;
      //   }
      //   }else{
      //     if('profile' === which){
      //       this.judge_mark.profile_error = true;
      //     }else{
      //       this.judge_mark.photo_error = true;
      //     }
      //   }
      // });

      // let result = this.judge_pic(pic,which);
      // if(result.right){
      //   this.info.profile = result.data;
      //   if('profile' === which){
      //     this.judge_mark.profile_error = false;
      //   }else{
      //     this.judge_mark.photo_error = false;
      //   }
      // }else{
      //   if('profile' === which){
      //     this.judge_mark.profile_error = true;
      //   }else{
      //     this.judge_mark.photo_error = true;
      //   }
      // }
    },
    // check_pic(pic,which){
    //   //预设变量
    //   let variable = {};
    //   if('profile' === which){
    //     variable = {
    //       type: [
    //         'jpeg',
    //         'gif',
    //         'png'
    //       ],
    //       size: 20* 1024,
    //       min_width: 50,
    //       max_width: 100,
    //       min_height: 50,
    //       max_height: 100,
    //       min_ratio: 1,
    //       max_ratio: 1
    //     };
    //   }else {
    //     variable = {
    //       type: [
    //         'jpeg',
    //         'gif',
    //         'png'
    //       ],
    //       size: 200* 1024,
    //       min_width: 560,
    //       max_width: 560 * 1.5,
    //       min_height: 760,
    //       max_height: 760 * 1.5,
    //       min_ratio: 0.73,
    //       max_ratio: 0.73
    //     }
    //   }
    //   let result = {
    //     right: true,
    //     data: null
    //   };
    //   return new Promise(function(resolve,reject){
    //     //检查图片格式
    //     if(pic.type !== 'image/jpeg' && pic.type !== 'image/gif' && pic.type !== 'image/png'){
          
    //       result.right = false;
    //       resolve(result);
    //     }
    //     //检查图片大小
    //     if(pic.size > variable.size){
    //       result.right = false;
    //       resolve(result);
    //     }
    //     //检查图片宽高
    //     let reader = new FileReader();
    //     //读取图片
    //     reader.readAsDataURL(pic);
    //     reader.onload = function(e){
    //       let temp_pic = new Image();
    //       temp_pic.src = e.target.result;
    //       temp_pic.onload = function(){ //检查宽高

    //         console.log(temp_pic.width);
    //         console.log(temp_pic.height);
    //         console.log(variable);
    //         if(temp_pic.width > variable.max_width || temp_pic.width < variable.min_width || temp_pic.height > variable.max_height || temp_pic.height < variable.min_height || temp_pic.width / temp_pic.height > variable.min_width / variable.min_height * 1.1 || temp_pic.width / temp_pic.height < variable.min_width / variable.min_height / 1.1){
    //           result.right = false;
    //         }else{
    //           result.data = temp_pic.src;
    //         }
    //         console.log(result);
    //         resolve(result);
    //       }
    //     }
    //   })
    // },
    check_nickname(){
      if(this.info.nickname === this.origin_info.user.nickname){
        this.judge_mark.nickname_error = false;
        return;
      }
      if(!this.info.nickname){
        this.judge_mark.nickname_error = true;
        return;
      }
      let postData = {
        login: 'query_user',
        nickname: this.info.nickname
      };
      this._POST(postData,response => {
        console.log(response.data);
        if(response.data.exist === 'n'){
          this.judge_mark.nickname_error = false;
        }else if(response.data.exist === 'y'){
          this.judge_mark.nickname_error = true;
        }
      })
    },
    check_name(){
      
      if(!this.info.name){
        this.judge_mark.name_error = true;
        return;
      }
      console.log('check_name');
      this.judge_mark.name_error = false;
    },
    check_education(){
      if(!this.info.education){
        this.judge_mark.education_error = true;
        return;
      }
      this.judge_mark.education_error = false;
    },
    check_counseling(){
      if(!Number(this.info.counseling) || Number(this.info.counseling) <= 0){
        this.judge_mark.counseling_error = true;
        return;
      }
      this.judge_mark.counseling_error = false;
    },
    check_design(){
      if(!Number(this.info.design) || Number(this.info.design) <= 0){
        this.judge_mark.design_error = true;
        return;
      }
      this.judge_mark.design_error = false;
    },
    check_style(){
      // console.log('check-style');
      let len = 0
      for( let key in this.info.style){
        if(this.info.style[key].selected){
          len++
        }
      };
      
      // console.log(len);
      
      if(len > 3 || len < 1){
        this.judge_mark.style_error = true;
        return;
      }
      this.judge_mark.style_error = false;
    },
    check_introduction(){
      if(this.info.introduction.length > 200){
        this.judge_mark.introduction_error = true;
        return;
      }
      this.judge_mark.introduction_error = false;
    },
    check_contact(){
      let len = 0;
      for( let key in this.info.contact){
        console.log(this.info.contact[key].value.length);
        if(this.info.contact[key].value.length > 0){
          len++;
        }
      }
      if(len === 0){
        this.judge_mark.contact_error = true;
        return;
      }
      
      //tel
      if(this.info.contact.tel.value.length > 0){
        if(!/^(0\d{2,3}-)|(\d{7,8})|(-\d{3,})$/.test(this.info.contact.tel.value)){
          this.judge_mark.contact_error = true;
          return;
        }
      }
      //phone
      if(this.info.contact.phone.value.length > 0){
        if(!/^1[3,4,5,7,8][0-9]{9}$/.test(this.info.contact.phone.value)){
          this.judge_mark.contact_error = true;
          return;
        }
      }
      //qq
      if(this.info.contact.qq.value.length){
        if(!/^[1-9]\d{4,14}$/.test(this.info.contact.qq.value)){
          this.judge_mark.contact_error = true;
          return;
        }
      }
      //wechat
      if(this.info.contact.wechat.value.length){
        if(!/^[a-zA-Z][a-zA-Z0-9_-]{5,19}$/.test(this.info.contact.wechat.value)){
          this.judge_mark.contact_error = true;
          return;
        }
      }
      this.judge_mark.contact_error = false;
    },
    init_city(){
      this.info.city.city = this.cur_area[0][1][0];
    }
  },
  watch: {
    cur_area: "init_city"
  },
  created(){
    this.$store.watch(state => {
      if(!state.origin_info){
        return;
      }
      this.origin_info = this.$store.state.origin_info;
      this.init_info();
    });
    // setTimeout(() => {
    //   this.init_info();
    // }, 1000);
    window.style = this.info.style;
  }
}
</script>

<style lang="less" scoped>
@darkGray: #666;
@white: white;
@red: #db3939;
.setting {
  padding: 50px 0 0 176px;
  color: @darkGray;
  font-size: 20px;
  > ul > li {
    height: 80px;
    line-height: 80px;
    > span:first-child {
      display: inline-block;
      width: 120px;
    }
    input {
      width: 260px;
      vertical-align: middle;
    }
    .error {
      padding: 0 5px;
      color: @red;
      font-size: 12px;
      font-weight: bold;
    }
    .explain {
      line-height: normal;
      font-size: 12px;
    }
    &.gender, &.expert-style {
      input {
        width: auto;
        
      }
      label {
        margin: 0 20px 0 0;
      }
    }
    &.profile, &.photo {
      img {
        width: 80px;
        height: 80px;
        background: @white;
        vertical-align: top;
      }
      input[type="file"] {
        top: 0px;
        left: 120px;
        width: 80px;
        height: 80px;
        opacity: 0;
        border: 0;
        outline: 0;
      }
    }
    &.photo {
      height: 108px;
      line-height: 108px;
      img {
        height: 108px;
      }
      input[type="file"] {
        height: 108px;
      }
    }
    &.fee {
      > input {
        width: 40px;
      }
    }
    &.expert-style {
      height: auto;
      line-height: normal;
      padding: 25px 0;
      label {
        display: inline-block;
      }
      span {
        vertical-align: top;
      }
      div {
        width: 260px;
      }
    }
    &.introduction {
      height: auto;
      line-height: normal;
      padding: 25px 0;
      textarea {
        width: 260px;
        height: 200px;
        vertical-align: top;
        resize: none;
      }
      p {
        padding: 5px 0 5px 120px;
        font-size: 12px;
      }
    }
  }
  .to-be-designer {
    height: 80px;
    line-height: 80px;
    padding-left: 20px;
  }
  .sure {
    width: 110px;
    height: 40px;
    font-size: 20px;
    background-color: @darkGray;
    color: @white;
    border: 0px;
    outline: 0px;
    margin: 25px 0 100px 230px;
  }
}
</style>