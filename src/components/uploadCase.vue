<template>
  <div class="upload">
    <ul>
      <li>
        <span>标题：</span>
        <input type="text"
          v-model="info.title"
          @blur="check_title"
        >
        <span class="error"
          v-show="info.title_error"
        >!</span>
        <span class="explain">必填</span>
      </li>
      <li>
        <span>户型：</span>
        <select name="house" id="house" v-model="info.house">
          <option 
            v-for="item in house"
            :key="item"
            :value="item"
            v-html="item"
          >四室两厅</option>
        </select>
      </li>
      <li>
        <span>风格：</span>
        <select name="style" id="style" v-model="info.style">
          <option 
            v-for="item in style"
            :key="item"
            :value="item"
            v-html="item"
          >现代简约</option>
        </select>
      </li>
      <li class="cover-li">
        <span>封面：</span>
        <div class="cover ilb por">
          <img :src="info.cover" alt="">
          <input type="file" class="poa cp" 
            @change="check_cover"
          />
          <!-- <div class="delete poa cp"></div> -->
        </div>
        <span class="error"
          v-show="info.cover_error"
        >!</span>
        <span class="explain">必填(最大35k。建议宽高比10：6。)</span>
      </li>
      <li class="pic-upload-li">
        <span>图片：</span>
        <div class="pic-upload-div ilb">
          <div class="areas"
            v-for="(item,index) in info.content"
            :key="index"
          >
            <p class="area-text">
              <span
                v-html="item.name"
              >户型图</span>
              <span class="error"
                v-show="item.error"
              >!</span>
              <span class="explain"
                v-html="item.err_info"
              >必填</span>
            </p>
            <div class="area-stage">
              <div class="pic-and-description ilb"
                v-for="(item_sub_1,index_sub_1) in item.detail"
                :key="index_sub_1"
                v-show="index_sub_1"
              >
                <div class="pic-stage por tac">
                  <div class="pic">
                    <img :src="item_sub_1.path" alt="">
                  </div>
                  <div class="pic-cover poa">
                    <div class="delete cp poa tac"
                      @click="delete_pic(item.detail,index_sub_1,index)"
                    >X</div>
                  </div>
                </div>
                <textarea class="pic-description"
                  v-model="item_sub_1.description"
                ></textarea>
              </div>
              <div class="add cp tac ilb por">
                +
                <input type="file" class="poa cp" multiple="multiple"
                  @change="add($event,item,index)"
                >
              </div>
            </div>
          </div>
        </div>
      </li>
      <li class="button-li tac">
        <button class="cp"
          @click="upload()"
          v-html="button_text"
        >上传</button>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data(){
    return {
      info: {
        title: '',
        title_error: false,
        house: '四室两厅',
        style: '现代简约',
        cover: '',
        cover_error: false,
        content: [
          {
            name: '户型图',
            err_info: '必填(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '玄关',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '客厅',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '餐厅',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '厨房',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '卫生间',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '书房',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '卧室',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          },
          {
            name: '其他',
            err_info: '至少有一个区域的图片(图片最大300k)',
            error: false,
            detail: [
              {
                path: '',
                description: ''
              }
            ]
          }
        ]
      },
      house: [
        '四室两厅',
        '三室两厅',
        '三室一厅',
        '两室两厅',
        '一室一厅',
        '复式',
        '别墅',
        '其他'
      ],
      style: [
        '现代简约',
        '美式',
        '欧式',
        '北欧',
        '地中海',
        '中式',
        '新中式',
        '和式',
        '混搭'
      ],
      button_text: '上传'
    }
  },
  methods: {
    check_title(){
      console.log('check title');
      if(!this.info.title){
        this.info.title_error = true;
        return;
      }
      this.info.title_error = false;
    },
    check_cover(e){
      e = e || event;
      console.log(e.target.files);
      let pic = {};

      if(window.FileReader){
        pic = e.target.files[0];
        console.log(pic);
      }else{  //低版本ie兼容
        try{
          e.target.select();
          e.target.blur();
          let path = document.selection.createRange().text;
          let fso = new ActiveXObject("Scripting.FileSystemObject");
          pic.path = path;
          pic.size = fso.GetFile(path).size;
          pic.type = fso.GetFile(path).type;
          console.log("269" + pic);
        }catch(e){
          alert(e+"\n"+"如果错误为：Error:Automation 服务器不能创建对象；"+"\n"+"请按以下方法配置浏览器："+"\n"+"请打开【Internet选项-安全-Internet-自定义级别-ActiveX控件和插件-对未标记为可安全执行脚本的ActiveX控件初始化并执行脚本（不安全）-点击启用-确定】");
        }
      }
      let rule = {
        size: 35 * 1024,
        type: [
          'jpeg',
          'png',
          'gif'
        ]
      };
      this._CHECK_PIC(rule,pic)
        .then(result => {
          if(result.right){
            this.info.cover = result.data;
            this.info.cover_error = false;
          }else{
            this.info.cover_error = true;
          }
          // e.target.value = '';
        });
    },
    check_content(index){
      console.log(index);
      //检查户型图
      if(0 == index || -1 == index){
        console.log('plan:'+this.info.content[0].detail.length);
        if(this.info.content[0].detail.length === 1){
          this.info.content[0].error = true;
        }else{
          this.info.content[0].error = false;
        }
      }
      
      //检查所有空间
      if(0 != index){
        let all_room = this.info.content.slice(1);
        if(all_room.every( val => val.detail.length === 1)){
          all_room.forEach( val => val.error = true);
        }else{
          all_room.forEach(val => val.error = false);
        }
      }
    },
    add(e,item,index){
      e = e || event;

      //如果值为空，返回。主要针对 createTextRange 触发的onchange
      if(!e.target.value){
        return;
      }

      let pics = [];
      if(window.FileReader){
        pics = e.target.files;
      }else{  //低版本ie兼容
        try{
          e.target.select();
          e.target.blur();
          let path = document.selection.createRange().text;
          let fso = new ActiveXObject("Scripting.FileSystemObject");
          pics[0] = {};
          pics[0].path = path;
          pics[0].size = fso.GetFile(path).size;
          pics[0].type = fso.GetFile(path).type;
        }catch(e){
          alert(e+"\n"+"如果错误为：Error:Automation 服务器不能创建对象；"+"\n"+"请按以下方法配置浏览器："+"\n"+"请打开【Internet选项-安全-Internet-自定义级别-ActiveX控件和插件-对未标记为可安全执行脚本的ActiveX控件初始化并执行脚本（不安全）-点击启用-确定】");
        }
      }



      let len = pics.length;
      //这里的mark 为什么设置为 -2，而不是0.原因未知，总之下面的 mark++ 执行次数会比循环次数多 2。所以设置为 -2 .
      let mark = -2;
      if(!window.FileReader){ //ie9 不存在多执行两次的情况
        mark = 0;
      }
      // console.log(e.target);
      // console.log(pics);
      let rule = {
        size: 300 * 1024,
        type: [
          'jpeg',
          'png',
          'gif'
        ]
      };
      for(let key in pics){
        this._CHECK_PIC(rule,pics[key])
          .then( result => {
            if(result.right){
              console.log("359" + pics);
              item.detail.push({
                path: result.data,
                description: ''
              });
            }
            mark++;
            console.log('len:'+len+' mark:'+mark);
            if(len === mark){
              e.target.value = '';
              if(window.ActiveXObject){
                e.target.createTextRange().execCommand('delete');
                e.target.blur();
              }
              
              console.log(e.target.value);
              this.check_content(index);
            }
          });
      }
      
      // item.detail.push({
      //   path: '',
      //   description: ''
      // })
    },
    delete_pic(item,index_sub,index){
      item.splice(index_sub,1);
      this.check_content(index);
    },
    upload(){
      if('上传中' === this.button_text || '已完成' === this.button_text || '后台错误' === this.button_text || '后台错误' === this.button_text){
        return;
      }
      this.check_title();
      if(!this.info.cover){
        this.info.cover_error = true;
      }
      this.check_content(-1);
      if(this.info.title_error || this.info.cover_error || this.info.content.some(val => val.error)){
        this.button_text = '信息不全';
        return;
      }else{

        //ie9阻止上传
        if(!window.FileReader){
          alert("您的浏览器版本过低，不支持上传。");
          return;
        }

        this.button_text = '上传中';
      }
      //上传案例
      if(!localStorage.lh_token){
        this.button_text = '无法上传';
        return;
      }
      let post_data = {
        upload: 'case',
        data: JSON.stringify(this.info),
        token: localStorage.lh_token
      };

      

      this._POST(post_data,response => {
        console.log(response.data);
        if('success' === response.data.response){
          this.button_text = '已完成';
        }else{
          this.button_text = '后台错误';
        }
      });
      console.log(post_data);
    }
  }
}
</script>

<style lang="less" scoped>
@white: white;
@darkGray: #666;
@red: #db3939;
.upload{
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
    &.cover-li {
      height: 230px;
      line-height: 170px;
      padding: 30px 0 30px 0;
      box-sizing: border-box;
      span {
        vertical-align: top;
        line-height: 170px;
      }
      .cover {
        background: @white;
        height: 170px;
        img {
          width: 260px;
          height: 170px;
        }
        input {
          width: 260px;
          height: 170px;
          top: 0;
          left: 0;
          opacity: 0;
          border: 0;
          outline: 0;
        }
      }
    }
    &.pic-upload-li {
      height: auto;
      line-height: normal;
      padding: 30px 0 30px 0px;
      span:first-child {
        vertical-align: top;
      }
      .pic-upload-div {
        span:first-child {
          vertical-align: top;
        }
        .area-stage {
          width: 500px;
          height: 270px;
          box-sizing: border-box;
          padding: 10px;
          margin: 10px 0 20px 0;
          background: @white;
          overflow-x: auto;
          overflow-y: hidden;
          white-space: nowrap;
          .pic-and-description {
            margin-right: 20px;
            .pic-stage {
              width: 140px;
              height: 140px;
              
              .pic {
                width: 140px;
                height: 140px;
                max-width: 140px;
                max-height: 140px;
                display: table-cell;
                background: @darkGray;
                vertical-align: middle;
                img {
                  max-width: 100%;
                  max-height: 100%;
                  width: auto;
                  height: auto;
                  vertical-align: middle;
                }
              }
              
              .pic-cover {
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                .delete {
                  width: 16px;
                  height: 16px;
                  display: none;
                  border-radius: 50%;
                  top: -6px;
                  right: -6px;
                  background-color: @darkGray;
                  color: @white;
                  font-size: 12px;
                  line-height: 16px;
                }
                
              }
              &:hover .delete {
                display: block;
              }
            }
            .pic-description {
              margin: 20px 0 0 0;
              width: 140px;
              height: 60px;
              line-height: 20px;
              font-size: 16px;
              resize: none;
              box-sizing: border-box;
            }
          }
          .add {
            width: 60px;
            height: 60px;
            box-sizing: border-box;
            border: 2px dotted @darkGray;
            line-height: 60px;
            font-size: 20px;
            vertical-align: top;
            input {
              width: 100%;
              height: 100%;
              top: 0;
              left: 0;
              opacity: 0;
            }
          }
        }
      }
    }
    &.button-li {
      width: 580px;
      button {
        width: 110px;
        height: 40px;
        line-height: 40px;
        font-size: 20px;
        background-color: @darkGray;
        color: @white;
        border: 0px;
        outline: 0px;
      }
    }
  }
}
</style>