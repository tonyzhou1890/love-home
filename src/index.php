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

//获取基本信息
$GLOBALS['base_info'] = common_query($GLOBALS['query']['base'],'无记录');


//首页
if((is_array($_GET)&&count($_GET)==0)&&(is_array($_POST)&&count($_POST))==0){
  $obj = new StdClass();
  $obj -> base_info = $GLOBALS['base_info'];
  $obj -> banner = banner();
  $obj -> designer = home_designer();
  $obj -> cases = home_case();

  $str = file_get_contents('./home.html');
  $str = str_replace('</vuebody>',json_encode($obj).'</vuebody>',$str);
  print_r($str);
  // $query = 'SELECT * FROM website';
  // $result_array = common_query($query,'无记录');
  // $result_obj = array2object($result_array);
  // $result_obj_str = json_encode($result_obj);
  // print_r($result_obj_str);
}

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