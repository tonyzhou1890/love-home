<template>
  <div class="advantage tac">
    <p>独家优势</p>
    <div>
      <div class = "adv fl">
        <p class = "item">专业</p>
        <p class = "intro">设计师经验丰富，可以独当一面，绝不敷衍搪塞。</p>
      </div>
      <div class = "adv middle fl">
        <p class = "item">实惠</p>
        <p class = "intro">直接与设计师联系，省去中间环节（装修公司）。</p>
      </div>
      <div class = "adv fl">
        <p class = "item">公益</p>
        <p class = "intro">本平台只提供信息，不收取中间费用。</p>
      </div>
    </div>
  </div>
</template>

<style lang="less" scoped>
@red: #db3939;
@darkGray: #666;

.advantage {
  width: 1200px;
  box-sizing: border-box;
  padding: 90px 20px 1px 20px;
  color: @darkGray;
  overflow: hidden;
  > p:first-child {
    color: @red;
    font-size: 60px;
    padding-bottom: 60px;
  }
  .adv {
    width: 260px;
    height: 170px;
    box-sizing: border-box;
    border: 4px solid @darkGray;
    padding: 2px;
    .item {
      margin-top: 14px;
      font-size: 30px;
    }
    .intro {
      margin-top: 30px;
      font-size: 18px;
    }
    &.middle {
      margin: 0 190px;
    }
    &:hover {
      color: @red;
      border-color: @red;
    }
  }
}
</style>