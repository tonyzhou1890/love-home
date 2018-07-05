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
          console.log(newData);
          return newData;
        }
      ]
    }).then(response => {
      func(response);
    });
  }
};

export default base;