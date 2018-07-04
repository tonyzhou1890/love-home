<template>
  <div class="about">
    <div class="about-area ova">
      <div class="tags fl tac">
        <ul class="cp">
          <li @click="curTag = 'about'" :class="{active:'about' === curTag}">关于爱家</li>
          <li @click="curTag = 'items'" :class="{active:'items' === curTag}">注意条款</li>
        </ul>
      </div>
      <div class="info fl">
        <section v-show="curTag === 'about'">
          <p>爱家，一个将业主和设计师直接联系起来的装修信息网站。</p>
          <p>在这里，业主可以从海量的精美案例里挑选合适的案例。在这里，设计师可以将自己满意的案例分享出来。在这里，没有中间商抽成，业主可以直接联系设计师咨询装修问题。</p>
        </section>
        <section v-show="curTag === 'items'">
          <p class="no-indent">注意条款：</p>
          <p>1.只有在收藏了案例后才可以联系案例设计师。</p>
          <p>2.这只是一个装修信息网站，不提供设计师资质、业主资金方面的保证，请各位注意，谨防诈骗。</p>
          <p>3.本网站还在测试中。所有信息来自网络，如有侵权请联系 1332988826@qq.com</p>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      curTag: 'about'
    }
  }
}
</script>

<style lang="less" scoped>
@darkGray: #666;
@red: #db3939;
@white: white;
@lightPink: #eee;

.about {
  width: 1200px;
  box-sizing: border-box;
  padding: 50px 10px 30px 10px;
  margin: auto;
  .about-area {
    width: 1180px;
    box-sizing: border-box;
    border: 2px solid @darkGray;
    .tags {
      height: 636px;
      background-color: @darkGray;
      ul {
        li {
          height: 48px;
          width: 198px;
          color: @white;
          font-size: 30px;
          line-height: 48px;
        }
        li.active {
          color: @red;
          background: @lightPink;
        }
      }
    }
    .info {
      padding: 40px;
      box-sizing: border-box;
      width: 977px;
      section {
        font-size: 20px;
        text-indent: 2em;
        .no-indent {
          text-indent: 0;
        }
      }
    }
  }
}
</style>