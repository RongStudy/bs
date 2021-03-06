<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * 全局md5加密
 */
function cthink_md5($str){
    $key = 'cthink';
    if(config('enstring')){
        $key = config('enstring');
    }
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * 判断系统后台是否登录
 */
function is_login_admin(){
    $return     = false;
    $session    = session('admin.user_auth');
    if(!empty($session)){
        $return = $session;
    }
    return $return;
}

/**
 * 判断用户端是否已登录
 */
function is_login_home(){
    $return  = false;
    $session = session('home.user_auth');
    if(!empty($session)){
        $return = $session;
    }
    return $return;
}


/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = [];
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 图片路径
 * $type == 1：获取缩略图
 * $type != 1：获取原图
 */
function photoPath($data, $type = 1){
    $imgPath = [];
    if($type == 1){
        foreach ($data as $key => $value) {
            $imgPath[] = config('url_domain').'/public/uploads/'.$value['thumb_path'].'/'.$value['thumb_name'];
        }
    }else{
        foreach ($data as $key => $value) {
            $imgPath[] = config('url_domain').'/public/uploads/'.$value['save_path'].'/'.$value['name'];
        }
    }
    return $imgPath;
}

function photoPath2($data, $type = 1){
    $imgPath = [];
    if($type == 1){
        foreach ($data as $key => $value) {
            $imgPath[$key]['img_id'] = $value['id'];
            $imgPath[$key]['thumb_p'] = config('url_domain').'/public/uploads/'.$value['thumb_path'].'/'.$value['thumb_name'];
        }
    }else{
        foreach ($data as $key => $value) {
            $imgPath['img_id'][] = $value['id'];
            $imgPath['thumb_p'][] = config('url_domain').'/public/uploads/'.$value['save_path'].'/'.$value['name'];
        }
    }
    return $imgPath;
}

/**
 * [make_random 生成随机字符]
 * @param  integer $length     [生成的长度]
 * @param  boolean $hasSpecial [是否包含特殊字符]
 * @return [string]            [description]
 */
function make_random( $length=8, $hasSpecial=true ){
    if(!$hasSpecial){
        // 随机数字符集，可任意添加你需要的字符
       $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h','i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's','t', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D','E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        );
    }else{
         $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h','i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's','t', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D','E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '@','#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',', '.', ';', ':', '/', '?', '|'
        );
    }
    $keys = array_rand($chars, $length); // 在 $chars 中随机取 $length 个数组元素键名
    $random = '';
    for($i = 0; $i < $length; $i++){
        $random .= $chars[$keys[$i]];  // 将 $length 个数组元素连接成字符串
    }
    return $random;
}

/**
 * 商品种类
 * @return [type] [description]
 */
function goods_type(){
    $goodsTypeModel = model('GoodsType');
    $list = $goodsTypeModel->getAll();

    $up_type = array();
    $down_type = array();
    foreach ($list as $key => $value) {
        if($value['pid'] == 0){
            $up_type[] = $value;
        }else{
            $down_type[] = $value;
        }
    }
    return array('up_type'=>$up_type, 'down_type'=>$down_type);
}

/**
 * 时间字符串
 * @return [string] [description]
 */
function timeStr(){
    $return = '';
    $time = date('Y-m-d H:i:s', time());
    $time = explode(' ', $time);
    $date = $time[0];
    $time = $time[1];
    $return = str_replace('-', '', $date).str_replace(':', '', $time)
    ;
    return $return;
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key 加密密钥
 * @param int $expire 过期时间 单位 秒
 * return string
*/
function think_encrypt($data, $key = '', $expire = 0) {
    $key = md5(empty($key) ? config('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }
    $str = sprintf('%010d', $expire ? $expire + time():0);
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    $returnStr = str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
    $returnStr = $returnStr.make_random(5, false);
    return $returnStr;
}

/**
 * 系统解密方法
 * @param string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param string $key 加密密钥
 * return string
*/
function think_decrypt($data, $key = ''){
    $data = substr($data, 0, -5);
    $key = md5(empty($key) ? config('DATA_AUTH_KEY') : $key);
    $data = str_replace(array('-','_'),array('+','/'),$data);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    $data = base64_decode($data);
    $expire = substr($data,0,10);
    $data = substr($data,10);
    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}
