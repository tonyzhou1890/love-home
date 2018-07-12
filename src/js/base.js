import axios from "axios";

const base = {};

base.install = function(Vue,options){
  //POST 请求
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
  //图片检查
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
          if(window.FileReader){
            return pic.type === 'image/' + val;
          }else{  //低版本ie兼容
            console.log(pic);
            let type = pic.type.split(" ")[0].toLowerCase();
            if('jpg' === type){
              type = 'jpeg';
            }
            return val === type;
          }
          
        });
        if(!r){
          result.right = false;
          resolve(result);
          return;
        }
      }
      
      //检查图片大小
      if(rules.size){
        if(pic.size > rules.size){
          result.right = false;
          resolve(result);
          return;
        }
      }
      
      //检查图片宽高
      if(window.FileReader){
        let reader = new FileReader();
        //读取图片
        reader.readAsDataURL(pic);
        reader.onload = function(e){
          let temp_pic = new Image();
          temp_pic.src = e.target.result;
          temp_pic.onload = function(){ 

            // console.log(temp_pic.width);
            // console.log(temp_pic.height);
            // console.log(variable);
            check_w_h(temp_pic,rules,result);
          }
        }
      }else{  //低版本ie兼容
        let temp_pic = new Image();
        temp_pic.src = pic.path;
        temp_pic.onload = function(){
          // console.log("89" + this);
          check_w_h(temp_pic,rules,result);
        }
      }
      


      //检查宽高
      function check_w_h(temp_pic,rules,result){
        // console.log("98" + this);
        //检查最小宽高
        if(rules.min_width && rules.min_height){
          if(temp_pic.width < rules.min_width || temp_pic.height < rules.min_height){
            result.right = false;
            resolve(result);
            return;
          }
        }
        //检查最大宽高
        if(rules.max_width && rules.max_height){
          if(temp_pic.width > rules.max_width || temp_pic.height > rules.max_height){
            result.right = false;
            resolve(result);
            return;
          }
        }
        //检查最小宽高比
        if(rules.min_ratio){
          if(temp_pic.width / temp_pic.height < rules.min_ratio * 0.9){
            result.right = false;
            resolve(result);
            return;
          }
        }
        //检查最大宽高比
        if(rules.max_ratio){
          if(temp_pic.width / temp_pic.height > rules.max_ratio * 1.1){
            result.right = false;
            resolve(result);
            return;
          }
        }

        //图片有效
        result.data = temp_pic.src;
        // console.log(result);
        resolve(result);
      }
    });
  }
};

export default base;