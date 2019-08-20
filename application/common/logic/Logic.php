<?php
namespace app\common\logic;
error_reporting(E_ERROR | E_PARSE );
use app\common\traits\InstanceTrait;
class Logic {
    
  use InstanceTrait;
   
  
  
  /*
   * 格式化数组为命令行文本
   */
  protected function format_arr(array $arr=[]) {
      
      foreach (@$arr as $key => $value) {
          if(empty($value)){
              unset($arr[$key]);
          }
      }
      return implode(' ', $arr);
  }
  

  /*
   * 生成enable命令
   */
  protected function enable(array $arr=[]) {
      
      $arr[]='enable';
      
      return $arr;
  }
  
  /*
   * 生成configure
   */
  protected function configure(array $arr=[]) {
      
     
      $arr[]='configure';
      
      return $arr;
  }
  
  
  /*
   * 生成end
   */ 
  protected function end(array $arr=[]) {
      
     
      $arr[]='end';
      
      return $arr;
  }
  
   /*
   * 生成exit
   */ 
  protected function back(array $arr=[]) {
      
    
      $arr[]='exit';
      
      return $arr;
  }
  
}
