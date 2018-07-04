<template>
  <footer>
    <div class="footer-area">
      <div class="web-info">
        <p>爱家</p>
        <p>不一样的装修体验——<a href="./?about" target="_blank">了解更多</a></p>
      </div>
      <div class="friend">
        <p>友情链接</p>
        <p>
          <a href="http://www.baidu.com" target="_blank">
            <span>百度</span>
          </a>
          <a href="http://www.163.com/" target="_blank">
            <span>网易</span>
          </a>
          <a href="http://www.jia.com" target="_blank">
            <span>齐家</span>
          </a>
          <a href="http://www.youzhu.com" target="_blank">
            <span>有住</span>
          </a>
        </p>
      </div>
      <div class="copyright">
        <span>©2018</span>
      </div>
    </div>
  </footer>
</template>

<style lang="less" scoped>
@pink: #edd9d9;
@darkGray: #666;
footer {
  background-color: @pink;
  .footer-area {
    width: 1200px;
    height: 200px;
    margin: auto;
    color: @darkGray;
    position: relative;
    padding-top: 1px;
    .web-info {
      float: left;
      margin: 20px 0 0 20px;
      width: 600px;
      p:first-child {
        font-size: 30px;
        margin-bottom: 10px;
      }
      p:last-child {
        font-size: 20px;
      }
    }
    .friend {
      float: left;
      margin: 20px 0 0 0;
      p:first-child {
        font-size: 30px;
        margin-bottom: 10px;
      }
      span {
        font-size: 20px;
        margin-right: 35px;
      }
    }
    .copyright {
      clear: both;
      text-align: center;
      padding-top: 60px;
    }
  }
}
</style>