<template>
  <div class="body ova">
    <casecontent
      :case_info="case_info"
      :collect="collect"
      v-if="'success' === response"
      @collection="set_collection"
    ></casecontent>
    <detailsidebar
      :designer="designer"
      :user="user"
      :cases="cases"
      :collect="collect"
      v-if="'success' === response"
    ></detailsidebar>
    <div class="no-result tac" v-if="'success' !== response">
      <h1>无记录</h1>
    </div>
  </div>
</template>

<script>
import detailsidebar from "./caseDetailSidebar.vue";
import casecontent from "./caseContent.vue";

export default {
  props: {
    case_info: {
      type: Object
    },
    designer: {
      type: Object
    },
    user: {
      type: Object
    },
    cases: {
      type: Array
    },
    response: {
      type: String
    }
  },
  components: {
    detailsidebar,
    casecontent
  },
  data(){
    return {
      collect: '收藏',
      first: 'true'
    }
  },
  methods: {
    set_collection(){
      let post_data = {};
      post_data.collection = 'query';
      if(this.first){
        post_data.data = JSON.stringify({
          token: window.localStorage.lh_token,
          c_id: this.case_info.id
        });
        this._POST(post_data,response => {
          console.log(response.data);
          this.first = false;
          if('y' === response.data.response){
            this.collect = '取消';
          }else if('n' === response.data.response){
          }else if('token_error' === response.data.response){
            this.first = true;
            setTimeout(() => {
              if(!window.localStorage.lh_token){
                this.first = false;
                return;
              }
              this.set_collection();
            }, 1000);
          }else{
            this.collect = '错误';
          }
        });
      }else if(window.localStorage.lh_token){
        if('错误' === this.collect || '操作中' === this.collect){
          return;
        }
        post_data.data = JSON.stringify({
          token: window.localStorage.lh_token,
          c_id: this.case_info.id
        });
        if('收藏' === this.collect){
          this.collect = '操作中';
          
          post_data.collection = 'add';
          this._POST(post_data,response => {
            console.log(response.data);
            if('success' === response.data.response){
              this.collect = '取消';
            }else{
              this.collect = '错误';
            }
          });
        }else{
          this.collect = '操作中';

          post_data.collection = 'delete';
          this._POST(post_data,response => {
            console.log(response.data);
            if('success' === response.data.response){
              this.collect = '收藏';
            }else{
              this.collect = '错误';
            }
          })
        }
      }else{
        window.location.href='./login.html';
      }
    }
  },
  created(){
    if('success' === this.response && window.localStorage.lh_token){
      this.set_collection();
    }else{
      this.first = false;
    }
  }
}
</script>

<style lang="less" scoped>
@darkGray: #666;
.body {
  width: 1200px;
  min-height: 1000px;
  margin: auto;
  .no-result {
    color: @darkGray;
  }
}
</style>