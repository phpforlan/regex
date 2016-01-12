<?php
/**
 * @file regexTool.class.php
 * @author lurenzhong@baidu.com
 * @date 15/12/10
 * @brief 正则工具类，可直接实例化后调用
 * @version
 */

class regexTool {

    //存放常用的正则表达式
    private $validate = array(
        'require' => '/.+/',  //非空验证
        'email'   => '/^\w+(\.\w+)*@\w+(\.\w+)+$/', //email验证
        'url'     => '^(https?://)?(\w+\.)+[a-zA-Z]+$', //url验证
        'mobile'  => '/^1(3|4|5|7|8)\d{9}$/', //手机号验证
    );

    private $returnMatchResult = false;//true表示返回匹配结果数组，false表示返回布尔值
    private $fixMode = null;
    private $matches = array();
    private $isMatch = false;

    //构造方法
    public function __construct($returnMatchResult = false,$fixMode = null){
        $this->returnMatchResult = $returnMatchResult;
        $this->fixMode = $fixMode;
    }

    //执行匹配操作的方法
    private function regex($pattern, $subject){
        //获取现有的正则匹配模式
        if(array_key_exists(strtolower($pattern),$this->validate)){
            $pattern = $this->validate[$pattern].$this->fixMode;
        }

        $this->returnMatchResult ?
            preg_match_all($pattern,$subject,$this->matches) :
            $this->isMatch = preg_match($pattern,$subject) === 1;

        return $this->getRegexResult();
    }

    //返回匹配结果的方法
    private function getRegexResult(){
        if($this->returnMatchResult){
            return $this->matches;
        }else{
            return $this->isMatch;
        }
    }

    //切换结果返回类型
    public function toggleReturnType($bool = null){
        if(empty($bool)){
            $this->returnMatchResult = !$this->returnMatchResult;
        }else{
            $this->returnMatchResult = is_bool($bool)? $bool : (bool)$bool;
        }
    }

    //修改修正模式的方法
    public function setFixMode($fixMode){
        $this->fixMode = $fixMode;
    }

    //验证非空的方法
    public function noEmpty($str){
        return $this->regex('require',$str);
    }

    //验证email地址的方法
    public function isEmail($str){
        return $this->regex('email',$str);
    }

    //验证是否是手机号的方法
    public function isMobile($str){
        return $this->regex('mobile',$str);
    }

    //用于自定义的正则表达式验证方法
    public function check($pattern,$subject){
        return $this->regex($pattern,$subject);
    }

}


/*
该类的使用方法如下：
require_once 'regexTool.class.php';

$regex = new regexTool();
$regex->setFixMode('U');//设置为贪婪模式

$res1 = $regex->isEmail('50505022@qq.com');
$res2 = $regex->check('/lubot/','this file is by lubot ');

var_dump($res1);//true
var_dump($res2);//true

*/
