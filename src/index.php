<?php

$host = 'localhost';
$user = 'root';
$password = '9306251239zxt';

//连接数据库
$db = mysqli_connect($host,$user,$password);
if(!$db){
  deal_error("无法连接数据库");
}
mysqli_query($db,"set names 'utf8'");
mysqli_select_db($db,"love_home");

//查询语句
$GLOBALS['query']['base'] = "SELECT l_key,value FROM website WHERE l_key = 'logo' OR l_key = 'nopic' OR l_key = 'default_profile'";
$GLOBALS['query']['banner'] = "SELECT l_key,value FROM website WHERE l_key = 'banner'";
$GLOBALS['query']['nickname'] = "SELECT nickname FROM user WHERE nickname = ";
$GLOBALS['query']['password'] = "SELECT password FROM user WHERE nickname = ";

//获取基本信息
$GLOBALS['base_info'] = common_query($GLOBALS['query']['base'],'无记录');

//需要返回的网站基本数据
$obj = new StdClass();
$obj -> base_info = $GLOBALS['base_info'];

//首页
if((is_array($_GET)&&count($_GET)==0)&&(is_array($_POST)&&count($_POST))==0){
  $obj -> banner = banner();
  $obj -> designer = home_designer();
  $obj -> cases = home_case();

  replace_template('./home.html','爱家',json_encode($obj));
  // $query = 'SELECT * FROM website';
  // $result_array = common_query($query,'无记录');
  // $result_obj = array2object($result_array);
  // $result_obj_str = json_encode($result_obj);
  // print_r($result_obj_str);
}else if(isset($_GET['about'])){
  //关于爱家页面
  replace_template('./about.html','关于爱家',json_encode($obj));
}else if(isset($_POST['login'])){
  //登录/注册页面
  $res = new StdClass();
  //查询用户是否存在
  if($_POST['login'] === 'query_user'){

    $res = user_exist($GLOBALS['query']['nickname'].'"'.urldecode($_POST['nickname']).'"');
    print_r(json_encode($res));
  }else if($_POST['login'] === 'register'){
    //注册
    $result = new StdClass();
    $res = user_exist($GLOBALS['query']['nickname'].'"'.urldecode($_POST['nickname']).'"');
    if('y' === $res -> exist){
      print_r(json_encode($res));
      die();
    }
    if(isset($_POST['nickname']) && isset($_POST['pwd'])){
      $register_nickname = urldecode($_POST['nickname']);
      $register_pwd = urldecode($_POST['pwd']);
      $query = "INSERT INTO user (nickname, password)
      VALUES
      ('$register_nickname', '$register_pwd')";
      // print_r($query);
      $result = common_insert($query);
      print_r(json_encode($result));
    }else{
      $res -> response = 'error';
      print_r(json_encode($res));
    } 
  }else if($_POST['login'] === 'login'){
    //登录
    $login_nickname = urldecode($_POST['nickname']);
    $login_pwd = urldecode($_POST['pwd']);
    $query = $GLOBALS['query']['password'].'"'.$login_nickname.'"';
    $result = common_query($query,'err');
    if($login_pwd === $result[0]['password']){

      $token = $res -> token = $login_nickname.'_'.time().'_'.rand(10000,99999);
      $query = "UPDATE user SET token = '$token' WHERE nickname = '$login_nickname'";

      $result = common_insert($query);
      if(isset($result -> errorMsg)){
        print_r(json_encode($result));
        die();
      }

      $res -> response = 'success';
    }else{
      $res -> response = 'error';
    }
    print_r(json_encode($res));
  }
}

//

//查询 banner
function banner(){
  $result_array = common_query($GLOBALS['query']['banner'],'无记录');
  $default_banner = new StdClass();
  $default_banner -> href = '#';
  $default_banner -> src = $GLOBALS['base_info'][1]['value'];
  $result_array[0]['value'] = patch($result_array[0]['value'],3,$default_banner);
  $result_array_obj = array2object($result_array[0]['value']);
  return $result_array_obj;
  // print_r(json_encode($result_array_obj));
}

//首页设计师
function home_designer(){
  $designer = new StdClass();
  $random = [];
  $new = [];
  $a_de = new StdClass();
  $a_de -> href = '#';
  $a_de -> src = $GLOBALS['base_info'][1]['value'];
  $a_de -> name = '设计师';
  $designer -> random_designer = patch($random,4,$a_de);
  $designer -> new_designer = patch($new,4,$a_de);
  return $designer;
}

//首页案例
function home_case(){
  $case = new StdClass();
  $temp = [];
  $a_c = new StdClass();
  $a_c -> href = '#';
  $a_c -> src = $GLOBALS['base_info'][1]['value'];
  $a_c -> title = '案例';
  $case -> modern = ['现代简约',patch($temp,4,$a_c)];
  $case -> amercia = ['美式',patch($temp,4,$a_c)];
  $case -> europe = ['欧式',patch($temp,4,$a_c)];
  $case -> north = ['北欧',patch($temp,4,$a_c)];
  return $case;
}

//结果补全
function patch($target,$len,$insert){
  if(empty($target)){
    $target = [];
  }
  for($i=0;$i<$len;$i++){
    if(!isset($target[$i]) || 'no_result' === $target[$i]){
      $target[$i] = $insert;
    }
  }
  return $target;
}

//查询用户是否存在
function user_exist($query){
  $result = common_query($query,'查询错误');
  $res = new StdClass();
  if('no_result'===$result[0]){
    $res -> exist = 'n';
  }else{
    $res -> exist = 'y';
  }
  return $res;
}

//查询数据库
function common_query($query,$error_msg){
  global $db;
  $result = mysqli_query($db,$query);
  // print_r($GLOBALS['query']['banner']);
  // print_r($result);
  if(!$result){
    deal_error($error_msg);
  }
  if(!mysqli_num_rows($result)){
    $result_array[0] = 'no_result';
    mysqli_free_result($result);
    return $result_array;
  }
  $result_array = mysqli_fetch_all($result,MYSQL_ASSOC);
  mysqli_free_result($result);
  // print_r($result_array);
  return $result_array;
}

//插入/更新数据
function common_insert($query){
  global $db;
  $result = mysqli_query($db,$query);
  $res = new StdClass();
  if($result){
    $res -> response = 'success';
  }else{
    $res -> errorMsg = 'error';
  }
  return $res;
}

//查询错误处理
function deal_error($error_msg){
  global $db;
  if($db){
    mysqli_close($db);
  }
  $obj = new StdClass();
  $obj -> errorMsg = $error_msg;
  print_r(json_encode($obj));
  die();
}

//替换模板
function replace_template($path,$title,$content){
  $html_str = file_get_contents($path);
  $html_str = str_replace("<title>Page Title</title>","<title>".$title."</title>",$html_str);
  $html_str = str_replace("</vuebody>",$content.'</vuebody>',$html_str);
  print_r($html_str);
}

//数组和对象的转换
//数组转对象
function array2object($array) {
  if (is_array($array)) {
    $obj = new StdClass();
    foreach ($array as $key => $val){
      $obj->$key = $val;
    }
  }
  else { $obj = $array; }
  return $obj;
}
//对象转数组
function object2array($object) {
  if (is_object($object)) {
    foreach ($object as $key => $value) {
      $array[$key] = $value;
    }
  }
  else {
    $array = $object;
  }
  return $array;
}