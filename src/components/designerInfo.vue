<template>
  <div class="designer">
    <div class="pic fl">
      <img 
        :src="designer.photo" 
        :alt="designer.name" 
        :title="designer.name"
      >
    </div>
    <div class="info fl ova">
      <p>
        <span class="item">姓名：</span>
        <span class="content"
          v-text="designer.name"
        >
        </span>
      </p>
      <p>
        <span class="item">年龄：</span>
        <span class="content"
          v-text="age"
        >
        </span>
      </p>
      <p>
        <span class="item">性别：</span>
        <span class="content"
          v-text="gender"
        >
        </span>
      </p>
      <p>
        <span class="item">毕业院校：</span>
        <span class="content"
          v-text="designer.education"
        >
        </span>
      </p>
      <p>
        <span class="item">从业时间：</span>
        <span class="content"
          v-text="working"
        >
        </span>
      </p>
      <p>
        <span class="item">擅长风格：</span>
        <span class="content style ilb"
          v-for="style in designer.style"
          v-text="style.name"
          :key="style.name"
          v-if="style.selected"
        >
        </span>
      </p>
      <p>
        <span class="item">简介：</span>
        <span class="content"
          v-text="designer.introduction"
        >
        </span>
      </p>
      <p>
        <span class="item">咨询费：</span>
        <span class="content"
          v-text="designer.counseling + '/小时'"
        >
        </span>
      </p>
      <p>
        <span class="item">设计费：</span>
        <span class="content"
          v-text="designer.design + '/平米'"
        >
        </span>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    designer: {
      type: Object
    },
    user: {
      type: Object
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
  }
}
</script>

<style lang="less" scoped>
@darkGray: #666;
@pink: #edd9d9;
.designer {
  width: 1200px;
  > div {
    width: 50%;
    box-sizing: border-box;
    padding: 20px;
    color: @darkGray;
    overflow: hidden;
  }
  .pic {
    img {
      width: 560px;
      height: 760px;
    }
  }
  .info {
    
    .item {
      display: inline-block;
      line-height: 55px;
      font-size: 25px;
    }
    .content {
      font-size: 20px;
      &.style {
        margin: 0 5px;
        background-color: @pink;
      }
    }
  }
}
</style>