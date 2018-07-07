import axios from "axios";

const base = {};

base.install = function(Vue,options){
  Vue.prototype._POST = function(post_data,func){
    axios({
      method: 'post',
      url: './',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded'
      },
      data: post_data,
      transformRequest: [
        function(data){
          let newData = '';
          for ( let i in data){
            newData += encodeURIComponent(i) + '=' + encodeURIComponent(data[i]) + '&';
          }
          // console.log(newData);
          return newData;
        }
      ]
    }).then(response => {
      func(response);
    });
  },
  Vue.prototype._CHECK_PIC = function(rules,pic){
    //预设变量
    let variable = rules;
    let result = {
      right: true,
      data: null
    };
    return new Promise(function(resolve,reject){
      //检查图片格式
      if(rules.type){
        let r = rules.type.some(val => {
          return pic.type === 'image/' + val;
        });
        if(!r){
          result.right = false;
          resolve(result);
        }
      }
      
      //检查图片大小
      if(rules.size){
        if(pic.size > rules.size){
          result.right = false;
          resolve(result);
        }
      }
      
      //检查图片宽高
      let reader = new FileReader();
      //读取图片
      reader.readAsDataURL(pic);
      reader.onload = function(e){
        let temp_pic = new Image();
        temp_pic.src = e.target.result;
        temp_pic.onload = function(){ 

          console.log(temp_pic.width);
          console.log(temp_pic.height);
          console.log(variable);

          //检查宽高
          //检查最小宽高
          if(rules.min_width && rules.min_height){
            if(temp_pic.width < rules.min_width || temp_pic.height < rules.min_height){
              result.right = false;
              resolve(result);
            }
          }
          //检查最大宽高
          if(rules.max_width && rules.max_height){
            if(temp_pic.width > rules.max_width || temp_pic.height > rules.max_height){
              result.right = false;
              resolve(result);
            }
          }
          //检查最小宽高比
          if(rules.min_ratio){
            if(temp_pic.width / temp_pic.height < rules.min_ratio){
              result.right = false;
              resolve(result);
            }
          }
          //检查最大宽高比
          if(rules.max_ratio){
            if(temp_pic.width / temp_pic.height > rules.max_ratio){
              result.right = false;
              resolve(result);
            }
          }

          //图片有效
          result.data = temp_pic.src;
          console.log(result);
          resolve(result);

        }
      }
    })
  }
};

export default base;