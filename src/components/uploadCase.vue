<template>
  <div class="upload">
    <ul>
      <li>
        <span>标题：</span>
        <input type="text">
        <span class="error">!</span>
        <span class="explain">必填</span>
      </li>
      <li>
        <span>户型：</span>
        <select name="house" id="house">
          <option value="四室两厅">四室两厅</option>
        </select>
      </li>
      <li>
        <span>风格：</span>
        <select name="style" id="style">
          <option value="现代简约">现代简约</option>
        </select>
      </li>
      <li class="cover-li">
        <span>封面：</span>
        <div class="cover ilb por">
          <img src="" alt="">
          <input type="file" class="poa cp" />
          <!-- <div class="delete poa cp"></div> -->
        </div>
        <span class="error">!</span>
        <span class="explain">必填</span>
      </li>
      <li class="pic-upload-li">
        <span>图片：</span>
        <div class="pic-upload-div ilb">
          <p class="area-text">
            <span>户型图</span>
            <span class="error">!</span>
            <span class="explain">必填</span>
          </p>
          <div class="area-stage">
            <div class="pic-and-explain fl">
              <div class="pic-stage por">
                <img src="" alt="">
                <div class="pic-cover poa">
                  <div class="delete cp poa tac">X</div>
                </div>
              </div>
              <textarea class="pic-explain"></textarea>
            </div>
            <div class="add cp tac ilb por">
              +
              <input type="file" class="poa cp">
            </div>
          </div>
        </div>
      </li>
      <li class="button-li tac">
        <button class="cp">上传</button>
      </li>
    </ul>
  </div>
</template>



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
          overflow-x: scroll;
          overflow-y: hidden;
          .pic-and-explain {
            margin-right: 20px;
            .pic-stage {
              width: 140px;
              height: 140px;
              background: @darkGray;
              display: table-cell;
              img {
                max-width: 100%;
                max-height: 100%;
                vertical-align: middle;
              }
              .pic-cover {
                width: 100%;
                height: 100%;
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
                &:hover .delete {
                  display: block;
                }
              }
              
            }
            .pic-explain {
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