<?php

$host = 'localhost';
$user = 'root';
$password = '9306251239zxt';
$token_expire = 250000;

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
$GLOBALS['query']['home_designer'] = "SELECT id,name,photo,thumb FROM designer ORDER BY id DESC LIMIT 4";
$GLOBALS['query']['home_designer_random'] = "SELECT id,name,photo,thumb FROM designer ORDER BY RAND() LIMIT 4";
$GLOBALS['query']['nickname'] = "SELECT nickname FROM user WHERE nickname = ";
$GLOBALS['query']['u_id'] = "SELECT id FROM user WHERE token = ";
$GLOBALS['query']['d_id'] = "SELECT id FROM designer WHERE u_id = ";
$GLOBALS['query']['password'] = "SELECT password FROM user WHERE nickname = ";
$GLOBALS['query']['token'] = "SELECT nickname,profile FROM user WHERE token = ";
$GLOBALS['query']['user_info'] = "SELECT * FROM user WHERE token = ";
$GLOBALS['query']['user_info_by_id'] = "SELECT * FROM user WHERE id = ";
$GLOBALS['query']['designer_info'] = "SELECT * FROM designer WHERE u_id = ";
$GLOBALS['query']['designer_info_by_id'] = "SELECT * FROM designer WHERE id = ";
$GLOBALS['query']['case_info'] = "SELECT * FROM cases WHERE id = ";
$GLOBALS['query']['collection'] = "SELECT collection FROM user WHERE token = ";
$GLOBALS['query']['d_contact'] = "SELECT contact FROM designer WHERE id = ";
$GLOBALS['query']['logout'] = "UPDATE user SET token = null WHERE token = ";

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

  // print_r($obj);
  replace_template('./home.html','爱家',json_encode($obj));
  // $query = 'SELECT * FROM website';
  // $result_array = common_query($query,'无记录');
  // $result_obj = array2object($result_array);
  // $result_obj_str = json_encode($result_obj);
  // print_r($result_obj_str);
}else if(isset($_GET['about'])){  //关于爱家页面
  replace_template('./about.html','关于爱家',json_encode($obj));
}else if(isset($_POST['login'])){ //登录/注册页面
  $result = login();
  print_r(json_encode($result));
}else if(isset($_GET['token'])){  //token验证
  $token = urldecode($_GET['token']);
  $result = verify_token($token);
  print_r(json_encode($result));
}else if(isset($_GET['setting'])){  //获取个人详细信息
  $token = urldecode($_GET['setting']);
  $result = getting_info($token);
  print_r(json_encode($result));
}else if(isset($_POST['setting'])){ //设置个人详细信息
  $setting = json_decode(urldecode($_POST['data']));
  $result = setting_info($setting);
  print_r(json_encode($result));
}else if(isset($_POST['upload'])){  //上传案例
  $upload_data = json_decode(urldecode($_POST['data']));
  $token = urldecode($_POST['token']);
  $result = upload_case($upload_data,$token);
  print_r(json_encode($result));
}else if(isset($_GET['c_id'])){ //案例详情
  $result = case_info($_GET['c_id']);
  $result -> base_info = $GLOBALS['base_info'];
  if(isset($result -> case_info)){
    $title = $result -> case_info['title'];
  }else{
    $title = $_GET['c_id'];
  }
  replace_template('./detail.html','案例-'.$title,json_encode($result));
  // print_r(json_encode($result));
}else if(isset($_POST['collection'])){  //collection相关
  $c_data = json_decode(urldecode($_POST['data']));
  $result = collection($_POST['collection'],$c_data);
  print_r(json_encode($result));
}else if(isset($_POST['d_contact'])){ //获取设计师联系方式
  $d_id = urldecode($_POST['d_contact']);
  $result = get_contact($d_id);
  print_r(json_encode($result));
}else if(isset($_GET['d_id'])){ //获取设计师详情
  $result = designer_info($_GET['d_id']);
  $result -> base_info = $GLOBALS['base_info'];
  if(isset($result -> designer_info)){
    $title = $result -> designer_info['name'];
  }else{
    $title = $_GET['d_id'];
  }
  replace_template('./designer.html','设计师-'.$title,json_encode($result));
  // print_r(json_encode($result));
}else if(isset($_GET['logout'])){ //退出
  $token = urldecode($_GET['logout']);
  $result = logout($token);
  print_r($result);
}else if(isset($_GET['random'])){ //随机四位设计师
  $result = get_designer('random');
  $res = new StdClass();
  $res -> designer = $result;
  print_r(json_encode($res));
};

//退出
function logout($token){
  $res = new StdClass();
  $result = common_insert($GLOBALS['query']['logout'].'"'.$token.'"');
  if(isset($result -> errorMsg)){
    $res -> response = 'error';
  }else{
    $res -> response = 'success';
  }
  return $res;
}

//获取设计师详情
function designer_info($d_id){
  $res = new StdClass();
  //设计师信息
  $designer = common_query($GLOBALS['query']['designer_info_by_id'].$d_id,'error');
  if('no_result' === $designer[0]){
    $res -> response = 'no_result';
    return $res;
  }
  $designer[0]['style'] = json_decode($designer[0]['style']);
  unset($designer[0]['contact']);
  $res -> designer_info = $designer[0];

  //设计师的用户信息
  $user = common_query("SELECT birth,gender FROM user WHERE id = ".$designer[0]['u_id'],'error');
  $res -> user_info = $user[0];

  //设计师相关案例
  $cases = common_query("SELECT id,cover,title FROM cases WHERE d_id = {$designer[0]['id']} ORDER BY id DESC",'error');
  if('no_result' === $cases[0]){
    $res -> response = 'no_case';
    return $res;
  }
  $res -> cases = $cases;
  $res -> response = 'success';
  return $res;
}


//获取设计师联系方式
function get_contact($d_id){
  $res = new StdClass();
  $result = common_query($GLOBALS['query']['d_contact'].$d_id,'error');
  if('no_result' === $result[0]){
    $res -> response = 'error';
    return $res;
  }
  $res -> response = 'success';
  $res -> contact = json_decode($result[0]['contact']);
  return $res;
}

//collection相关操作
function collection($act,$data){
  $res = new StdClass();
  $result = common_query($GLOBALS['query']['collection'].'"'.$data -> token.'"','error');
  if('query' === $act){
    if('no_result' === $result[0]){
      $res -> response = 'token_error';
      return $res;
    }
    if(!$result[0]['collection']){
      $res -> response = 'n';
      return $res;
    }
    if(in_array($data -> c_id,explode('-',$result[0]['collection']))){
      $res -> response = 'y';
    }else{
      $res -> response = 'n';
    };
    return $res;
  }else if('add' === $act){
    if(!$result[0]['collection']){
      $collection = $data -> c_id;
    }else{
      $collection = $result[0]['collection']."-{$data -> c_id}";
    }
    $result = common_insert("UPDATE user SET collection = '$collection' WHERE token = '{$data -> token}'");
    if(isset($result -> errorMsg)){
      $res -> response = 'error';
    }else{
      $res -> response = 'success';
    }
    return $res;
  }else if('delete' === $act){
    $collection = array_diff(explode('-',$result[0]['collection']),[$data -> c_id]);
    $collection = implode('-',$collection);
    $result = common_insert("UPDATE user SET collection = '$collection' WHERE token = '{$data -> token}'");
    if(isset($result -> errorMsg)){
      $res -> response = 'error';
    }else{
      $res -> response = 'success';
    }
    return $res;
  }else{
    $res -> response = 'error';
    return $res;
  }

}

//案例详情页
function case_info($c_id){
  $res = new StdClass();
  //案例信息
  $info = common_query($GLOBALS['query']['case_info'].$c_id,'error');
  if('no_result' === $info[0]){
    $res -> response = 'no_result';
    return $res;
  }
  
  $info[0]['content'] = json_decode($info[0]['content']);
  $res -> case_info = $info[0];

  //相关设计师信息
  $designer = common_query($GLOBALS['query']['designer_info_by_id'].$info[0]['d_id'],'error');
  if('no_result' === $designer[0]){
    $res -> response = 'no_designer';
    return $res;
  }

  $designer[0]['style'] = json_decode($designer[0]['style']);
  unset($designer[0]['contact']);
  $res -> designer_info = $designer[0];

  //设计师的用户信息
  $user = common_query("SELECT birth,gender FROM user WHERE id = ".$designer[0]['u_id'],'error');
  $res -> user_info = $user[0];

  //设计师相关案例
  // $cases_id = shuffle(explode('-',$designer[0]['cases']));
  $query = "SELECT id, cover, title FROM cases WHERE d_id = ".$info[0]['d_id']." ORDER BY RAND() LIMIT 4";
  $related_cases = common_query($query,'error');
  if('no_result' === $related_cases){
    $res -> response = 'no_case';
    return $res;
  }

  $res -> related_cases = $related_cases;

  $res -> response = 'success';
  return $res;
}

//登录或注册
function login(){
  $res = new StdClass();
  //查询用户是否存在
  if($_POST['login'] === 'query_user'){

    $res = user_exist($GLOBALS['query']['nickname'].'"'.urldecode($_POST['nickname']).'"');
    return $res;
  }else if($_POST['login'] === 'register'){
    //注册
    $result = new StdClass();
    $res = user_exist($GLOBALS['query']['nickname'].'"'.urldecode($_POST['nickname']).'"');
    if('y' === $res -> exist){
      return $res;
    }
    if(isset($_POST['nickname']) && isset($_POST['pwd'])){
      $register_nickname = urldecode($_POST['nickname']);
      $register_pwd = urldecode($_POST['pwd']);
      $query = "INSERT INTO user (nickname, password)
      VALUES
      ('$register_nickname', '$register_pwd')";
      // print_r($query);
      $result = common_insert($query);
      return $result;
    }else{
      $res -> response = 'error';
      return $res;
    } 
  }else if($_POST['login'] === 'login'){
    //登录
    $login_nickname = urldecode($_POST['nickname']);
    $login_pwd = urldecode($_POST['pwd']);
    $query = $GLOBALS['query']['password'].'"'.$login_nickname.'"';
    $result = common_query($query,'err');
    if($login_pwd === $result[0]['password']){

      $token = $res -> token = generate_token($login_nickname);
      $query = "UPDATE user SET token = '$token' WHERE nickname = '$login_nickname'";

      $result = common_insert($query);
      if(isset($result -> errorMsg)){
        return $result;
      }

      $res -> response = 'success';
    }else{
      $res -> response = 'error';
    }
    return $res;
  }
}

//上传案例
function upload_case($data,$token){
  $res = new StdClass();
  
  //判断图片是否都是base64并抽取图片
  //封面
  $pics = [];
  $pics[0] = $data -> cover;
  if(!'data:image' === substr($pics[0],0,10)){
    $res -> response = 'error';
    return $res;
  }
  //内容
  $pics[1] = [];
  $c = $data -> content;
  for($i = 0,$c_len = count($c); $i < $c_len; $i++){
    $pics[1][$i] = [];
    $d = $c[$i] -> detail;
    for($j = 1,$d_len = count($d); $j < $d_len; $j++){
      if(!'data:image' === substr($d[$j] -> path,0,10)){
        $res -> response = 'error';
        return $res;
      }
      $pics[1][$i][$j] = $d[$j] -> path;
    }
  }
  // print_r($pics);
  //图片转换
  //封面
  $result = base64image($pics[0],'./images/case/',date('YmdHis').rand(100000,999999));
  if('error' === $result -> result){
    $res -> response = 'error';
    return;
  }
  $pics[0] = $result -> path;
  //内容
  for($i = 0,$c_len = count($pics[1]); $i < $c_len; $i++){
    for($j = 1,$d_len = count($pics[1][$i]); $j <= $d_len; $j++){
      $result = base64image($pics[1][$i][$j],'./images/case/',date('YmdHis').rand(100000,999999));
      if('error' === $result -> result){
        $res -> response = 'error';
        return;
      }
      $pics[1][$i][$j] = $result -> path;
    }
  }
  // print_r($pics);
  //图片路径返回
  $data -> cover = $pics[0];
  for($i = 0,$c_len = count($pics[1]); $i < $c_len; $i++){
    for($j = 1,$d_len = count($pics[1][$i]); $j <= $d_len; $j++){
      $data -> content[$i] -> detail[$j] -> path = $pics[1][$i][$j];
    }
  }
  //生成内容图片缩略图
  for($i = 0,$c_len = count($pics[1]); $i < $c_len; $i++){
    for($j = 1,$d_len = count($pics[1][$i]); $j <= $d_len; $j++){
      $result = scalePic($pics[1][$i][$j],$maxX=260,$maxY=170,'thumb');
      $data -> content[$i] -> detail[$j] -> thumb = $result;
    }
  }
  //数据处理
  $content_str = str_replace('\\','\\\\',json_encode($data -> content));
  $u_id = common_query($GLOBALS['query']['u_id'].'"'.$token.'"','error');
  $u_id = $u_id[0]['id'];
  $d_id = common_query($GLOBALS['query']['d_id'].$u_id,'error');
  $d_id = $d_id[0]['id'];
  //写入数据库
  $query = "INSERT INTO cases
    (
      title,
      style,
      house,
      cover,
      content,
      d_id
    ) VALUES (
      '{$data -> title}',
      '{$data -> style}',
      '{$data -> house}',
      '{$data -> cover}',
      '$content_str',
      $d_id
    )";
  $insert_res = common_insert($query);
  if(isset($insert_res -> errorMsg)){
    $res -> response = 'error';
    return $res;
  }
  global $db;
  $c_id = mysqli_insert_id($db);
  $designer_cases = common_query("SELECT cases FROM designer WHERE u_id = '$u_id'",'error');
  $designer_cases = $designer_cases[0]['cases'];
  if(!$designer_cases){
    $query = "UPDATE designer SET cases = '$c_id' WHERE id = $d_id";
    common_insert($query);
  }else{
    $designer_cases .="-$c_id";
    $query = "UPDATE designer SET cases = '$designer_cases' WHERE id = $d_id";
    common_insert($query);
  }
  $res -> response = 'success';
  $res -> data = $data;
  return $res;
}

//设置个人信息
function setting_info($setting){
  //如果是base64
  // $setting -> user -> profile !== $GLOBALS['base_info'][2]['value']
  if(substr($setting -> user -> profile,0,10) === 'data:image'){
    $res = base64image($setting -> user -> profile,'./images/profile/',date('YmdHis').rand(100000,999999));
    if('success' === $res -> result){
      $setting -> user -> profile = $res -> path;
    }else{
      $setting -> user -> profile = $GLOBALS['base_info'][2]['value'];
    }
  }else if('./images/' === substr($setting -> user -> profile,0,9)){
    //如果是库文件
  }else{
    $setting -> user -> profile = $GLOBALS['base_info'][2]['value'];
  }
  $u = $setting -> user;
  $u_id = common_query($GLOBALS['query']['u_id'].'"'.$u -> token.'"','err');
  $u_id = $u_id[0]['id'];
  // print_r("120");
  // print_r($GLOBALS['query']);
  $query1 = "UPDATE user SET nickname = '{$u -> nickname}', birth = '{$u -> birth}', gender = '{$u -> gender}', profile = '{$u -> profile}' WHERE token = '{$u -> token}'";
  // print_r($GLOBALS);
  $update = common_insert($query1);
  $response = new StdClass();
  if(isset($update -> errorMsg)){
    $response -> response = 'error';
    return $response;
  }

  //是否要成为或者已经是设计师
  if(isset($setting -> designer)){
    $d = $setting -> designer;
    //将‘擅长风格’和‘联系方式’转为字符串
    $d_style = str_replace('\\','\\\\',json_encode($d -> style));
    $d_contact = str_replace('\\','\\\\',json_encode($d -> contact));
    //检查图片是否赋值并且是base64
    if($d -> photo && substr($d -> photo,0,10) === 'data:image'){
      $res = base64image($d -> photo, './images/profile/', date('YmdHis').rand(100000,999999));
      if('success' === $res -> result){ //如果转换成功
        $d -> photo = $res -> path;
        $d -> thumb = scalePic($res -> path,$maxX=260,$maxY=350,'thumb');
      }else{  //如果转换失败
        $d -> photo = null;
        $d -> thumb = null;
      }
    }else if($d -> photo && substr($d -> photo,0,9) === './images/'){
      //如果图片没变
      
    }else{  //其他情况
      $d -> photo = null;
      $d -> thumb = null;
    }
    //是否已经是设计师
    $d_result = common_query($GLOBALS['query']['d_id'].'"'.$u_id.'"','error');
    //如果还不是，就插入信息
    // print_r($d_result);
    if('no_result' === $d_result[0]){
      $q = "INSERT INTO designer 
        (
          u_id,
          photo,
          thumb,
          counseling,
          design,
          working_years,
          name,
          education,
          city,
          introduction,
          style,
          contact
        ) VALUES (
          '{$u_id}',
          '{$d -> photo}',
          '{$d -> thumb}',
          '{$d -> counseling}',
          '{$d -> design}',
          '{$d -> working_years}',
          '{$d -> name}',
          '{$d -> education}',
          '{$d -> city}',
          '{$d -> introduction}',
          '{$d_style}',
          '{$d_contact}'
        );";
    }else{  //否则就更新信息
      $q = "UPDATE designer SET 
        photo = '{$d -> photo}',
        thumb = '{$d -> thumb}',
        counseling = '{$d -> counseling}',
        design = '{$d -> design}',
        working_years = '{$d -> working_years}',
        name = '{$d -> name}',
        education = '{$d -> education}',
        city = '{$d -> city}',
        introduction = '{$d -> introduction}',
        style = '{$d_style}',
        contact = '{$d_contact}'
        WHERE u_id = '{$u_id}';
      ";
    }
    // print_r($q);
    $update = common_insert($q);
    // print_r($update);
    if(isset($update -> errorMsg)){
      $response -> response = 'error';
      return $response;
    }
    
  }
  $response -> response = 'success';
  return $response;
  // print_r(json_encode($setting));
}

//将base64转换为图片
function base64image($str,$path,$filename){
  $res = new StdClass();
  if(preg_match('/^(data:image\/(\w+);base64,)/', $str, $matches)){
    $type = $matches[2];
    $path= $path.$filename.'.'.$type;
    $str = str_replace(array($matches[0],' '),array('','+'),$str);

    // print_r($str);
    // die();
    file_put_contents($path,base64_decode($str));
    $res -> path = $path;
    $res -> result = 'success';
  }else{
    $res -> result = 'error';
  };
  return $res;
}

//获取个人详细信息
function getting_info($token){
  $result = common_query($GLOBALS['query']['user_info'].'"'.$token.'"','err');
  $res = new StdClass();
  $res -> user = array2object($result[0]);
  // print_r(json_encode(array2object($result[0])));
  $u_id = $res -> user -> id;
  // print_r($u_id);
  $result = common_query($GLOBALS['query']['designer_info'].$u_id,'err');
  // print_r($result);
  if('no_result' === $result[0]){
    $result[0] = null;
  }

  $res -> designer = array2object($result[0]);
  if($res -> designer){
    $res -> designer -> style = json_decode($res -> designer -> style);
    $res -> designer -> contact = json_decode($res -> designer -> contact);
  }
  if(!$res -> user -> profile){
    $res -> user -> profile = $GLOBALS['base_info'][2]['value'];
  }
  if(!$res -> user -> gender){
    $res -> user -> gender = '男';
  }
  return $res;
}

//token 验证
function verify_token($token){
  global $token_expire;
  $result = common_query($GLOBALS['query']['token'].'"'.$token.'"','err');
  $res = new StdClass();
  if($result[0] === 'no_result'){
    $res -> response = 'error';
  }else{
    $ex = explode('_',$token);
    $now = time();
    
    // print_r($token_expire);
    if($now - $ex[1] > $token_expire){
      $res -> response = 'error';
      clear_token();
    }else if($now - $ex[1] > $token_expire/2 && $now - $ex[1] < $token_expire){
      $new_token = generate_token($ex[0]);
      $query = "UPDATE user SET token = '$new_token' WHERE token = '$token'";

      $update = common_insert($query);
      if(isset($update -> errorMsg)){
        return $update;
      }
      $res -> token = $new_token;
    }else{
      $res -> response = 'success';
    };
    // print_r($result);
    
    $res -> nickname = $result[0]['nickname'];
    $res -> profile = $result[0]['profile'];
    if(!$res -> profile){
      $res -> profile = $GLOBALS['base_info'][2]['value'];
    }
  }
  return $res;
}

//生成token
function generate_token($start){
  return $start.'_'.time().'_'.rand(10000,99999);
}

//清除token
function clear_token($token){
  common_insert("UPDATE user SET token = null WHERE token = ".$token);
}

//查询 banner
function banner(){
  $result_array = common_query($GLOBALS['query']['banner'],'无记录');
  // print_r(json_decode($result_array[0]['value']));
  $default_banner = new StdClass();
  $default_banner -> href = '#';
  $default_banner -> src = $GLOBALS['base_info'][1]['value'];
  if($result_array[0]['value']){
    
    $result_array[0]['value'] = object2array(json_decode($result_array[0]['value']));
  }
  $result_array[0]['value'] = patch($result_array[0]['value'],3,$default_banner);
  $result_array_obj = array2object($result_array[0]['value']);
  return $result_array_obj;
  // print_r(json_encode($result_array_obj));
}

//首页设计师
function home_designer(){
  $designer = new StdClass();
  $random = get_designer('random');
  $new = get_designer('');
  $designer -> random_designer = $random;
  $designer -> new_designer = $new;
  return $designer;
}

//设计师4位
function get_designer($is_random){
  $a_de = [];
  $a_de['id'] = '#';
  $a_de['name'] = '设计师';
  $a_de['photo'] = $GLOBALS['base_info'][1]['value'];
  
  if('random' === $is_random){
    $result = common_query($GLOBALS['query']['home_designer_random'],'error');
  }else{
    $result = common_query($GLOBALS['query']['home_designer'],'error');
  }
  if('no_result' === $result[0]){
    $result = [];
  }
  $result = patch($result,4,$a_de);
  return $result;
}

//首页案例
function home_case(){
  $case = new StdClass();
  $temp = [];
  $a_c = [];
  $a_c['id'] = '#';
  $a_c['cover'] = $GLOBALS['base_info'][1]['value'];
  $a_c['title'] = '案例';

  $modern = common_query("SELECT id,cover,title FROM cases WHERE style = '现代简约' ORDER BY id DESC LIMIT 4",'error');
  if('no_result' === $modern){
    $modern = $temp;
  }


  $case -> modern = ['现代简约',patch($modern,4,$a_c)];
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

/**
 * @function 等比缩放函数(以保存的方式实现)
 * @param string $picname 被缩放的处理图片源
 * @param int $maxX 缩放后图片的最大宽度
 * @param int $maxY 缩放后图片的最大高度
 * @param string $pre 缩放后图片名的前缀名
 * @return string 返回后的图片名称(带路径),如a.jpg --> s_a.jpg
 */
function scalePic($picname,$maxX=100,$maxY=100,$pre='s_')
{
    $info = getimagesize($picname); //获取图片的基本信息
    $width = $info[0];//获取宽度
    $height = $info[1];//获取高度
    //判断图片资源类型并创建对应图片资源
    $im = getPicType($info[2],$picname);
    //计算缩放比例
    $scale = ($maxX/$width)>($maxY/$height)?$maxY/$height:$maxX/$width;
    //计算缩放后的尺寸
    $sWidth = floor($width*$scale);
    $sHeight = floor($height*$scale);
    //创建目标图像资源
    $nim = imagecreatetruecolor($sWidth,$sHeight);
    //等比缩放
    imagecopyresampled($nim,$im,0,0,0,0,$sWidth,$sHeight,$width,$height);
    //输出图像
    $newPicName = outputImage($picname,$pre,$nim);
    //释放图片资源
    imagedestroy($im);
    imagedestroy($nim);
    return $newPicName;
}

/**
 * function 判断并返回图片的类型(以资源方式返回)
 * @param int $type 图片类型
 * @param string $picname 图片名字
 * @return 返回对应图片资源
 */
function getPicType($type,$picname)
{
    $im=null;
    switch($type)
    {
        case 1:  //GIF
            $im = imagecreatefromgif($picname);
            break;
        case 2:  //JPG
            $im = imagecreatefromjpeg($picname);
            break;
        case 3:  //PNG
            $im = imagecreatefrompng($picname);
            break;
        case 4:  //BMP
            $im = imagecreatefromwbmp($picname);
            break;
        default:
            die("不认识图片类型");
            break;
    }
    return $im;
}

/**
 * function 输出图像
 * @param string $picname 图片名字
 * @param string $pre 新图片名前缀
 * @param resourse $nim 要输出的图像资源
 * @return 返回新的图片名
 */
function outputImage($picname,$pre,$nim)
{
    $info = getimagesize($picname);
    $picInfo = pathInfo($picname);
    $newPicName = $picInfo['dirname'].'/'.$pre.$picInfo['basename'];//输出文件的路径
    switch($info[2])
    {
        case 1:
            imagegif($nim,$newPicName);
            break;
        case 2:
            imagejpeg($nim,$newPicName);
            break;
        case 3:
            imagepng($nim,$newPicName);
            break;
        case 4:
            imagewbmp($nim,$newPicName);
            break;
    }
    return $newPicName;
}