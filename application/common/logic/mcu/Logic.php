<?php
namespace app\common\mcu\logic;
use think\Db;
use app\common\traits\InstanceTrait;
use PhpXmlRpc\Value;
use PhpXmlRpc\Request;
use PhpXmlRpc\Client;
class Logic {
    
  use InstanceTrait;
   
  
    protected $username;  //用户名
    protected $password;    //密码
    protected $api_domain;   //接口域
    protected $api_path;    //接口路径
    protected $api_port;    //接口端口
    protected $return_type;   //返回类型
    public function __construct($username,$password,$api_domin,$api_port,$api_path,$return_type='xml') {
        $this->username=$username;
        $this->password=$password;
        $this->api_domain=$api_domin;
        $this->api_port=$api_port;
        $this->api_path=$api_path;
        $this->return_type=$return_type;
        
    }
    
    
    /*
     * xs_Template参数集
     * @param  array  $mtarr      模板参数集
     * $mtarr 数组参数字段注释
     * @$mtarr   string    _name                 模板名,默认空
     * @$mtarr   string    _id                   模板ID或者SIP注册名,默认空
     * @$mtarr   string    _password             会议密码或者SIP注册密码,默认空
     * @$mtarr   int       _bandwidth            模板带宽,默认1000000=1M
     * @$mtarr   boolean   _hall                 内部使用，大厅模板,默认false
     * @$mtarr   boolean   _auto_delete          结束会议后，自动删除该模板，默认false
     * @$mtarr   int       _duration             模板时长,默认0
     * @$mtarr   XMLRPC    _caps                 模板能力集，获取xs_Cap能力集，可不传递
     * @$mtarr   boolean   _mute_in              入会禁麦克,默认false
     * @$mtarr   XMLRPC    _mute_in_exclude      列表中的终端在_mute_in打开时不禁麦克,获取xs_PStringArray集，可不传递(暂时缺少)
     * @$mtarr   int       _max_terminals        会议最大终端数，0表示不限制,默认0
     * @$mtarr   XMLRPC    _terminals            终端列表，获取xs_Terminal能力集，可不传递
     * @$mtarr   int       _mode                 xmlrpc使用者用来保存模版的模式，mcu不做解释,默认0
     * @$mtarr   XMLRPC    _screens              屏幕列表，获取xs_Screen能力集，可不传递
     * @$mtarr   XMLRPC    _terminals            SIP注册信息，获取xs_SIPRegister能力集，可不传递
     * @return void
     */
    protected function xs_Template($mtarr=array()){
        $new_arr=[];
        $new_arr['_name']= new Value(isset($mtarr['_name'])?$mtarr['_name']:'', 'string');
        $new_arr['_id']  =new Value(isset($mtarr['_id'])?$mtarr['_id']:'', 'string');
        $new_arr['_password']= new Value(isset($mtarr['_password'])?$mtarr['_password']:'', 'string');
        $new_arr['_bandwidth']= new Value(isset($mtarr['_bandwidth'])?$mtarr['_bandwidth']:1000000, 'int');
        $new_arr['_hall']= new Value(isset($mtarr['_hall'])?$mtarr['_hall']:false, 'boolean');
        $new_arr['_auto_delete']= new Value(isset($mtarr['_auto_delete'])?$mtarr['_auto_delete']:false, 'boolean');
        $new_arr['_duration']= new Value(isset($mtarr['_duration'])?$mtarr['_duration']:0, 'int');
        isset($mtarr['_caps']) &&  $new_arr['_caps']=$mtarr['_caps'];
        $new_arr['_mute_in']= new Value(isset($mtarr['_mute_in'])?$mtarr['_mute_in']:false, 'boolean');
        isset($mtarr['_mute_in_exclude']) &&  $new_arr['_mute_in_exclude']=$mtarr['_mute_in_exclude'];
        $new_arr['_max_terminals']= new Value(isset($mtarr['_max_terminals'])?$mtarr['_max_terminals']:0, 'int');
        isset($mtarr['_terminals']) &&  $new_arr['_terminals']=$mtarr['_terminals'];
        $new_arr['_mode']= new Value(isset($mtarr['_mode'])?$mtarr['_mode']:0, 'int');
        isset($mtarr['_screens']) &&  $new_arr['_screens']=$mtarr['_screens'];
        isset($mtarr['_terminals']) &&  $new_arr['_terminals']=$mtarr['_terminals'];    
        
        
        
        return new Value($new_arr,'struct');
    }
    
    /*
     * xs_Screen参数集
     * @param  array  $mtarr      分屏设置参数集
     * $mtarr 数组参数字段注释
     * @$mtarr   int        _which                        第几路视频流：1-主流，2-辅流,默认-1
     * @$mtarr   int        _role                         角色，0,1,2,3:主席，演讲者，听众，陌生人,默认为-1，即所有
     * @$mtarr   bool       _auto_mode                    是否自动分屏，默认false
     * @$mtarr   string     _size                         屏幕大小，格式宽x高，可用取模板中设置的最大值 
     * @$mtarr   int        _fps                          屏幕帧率，由MCU返回
     * @$mtarr   string     _mixermode                    分屏模式，取值见VideoMixerMode,默认MPVideoMixer::e_S1
     * enum VideoMixerMode {
			e_S1, 单屏
			e_S2, 两分屏
			e_S3, 三分屏
			e_S4, 四分屏
			e_S5P1, 五加一分屏
			e_S9, 九分屏
			e_S8P2, 八加二分屏
			e_S12P1, 十二加一分屏
			e_S16, 十六分屏
			e_S1P14, 一加十四分屏
			e_NumberOfVideoMixerMode
		};
     * @$mtarr   XMLRPC     _osm                          屏幕文字信息,获取xs_OnScreenMessage能力集
     * @$mtarr   XMLRPC     _subscreens                   子画面配置,获取xs_SubScreen能力集
     * @return void
     */
    
    protected function xs_Screen($mtarr=array()) {
        $new_arr=[];
        $new_arr['_which']= new Value(isset($mtarr['_which'])?$mtarr['_which']:-1, 'int');
        $new_arr['_role']= new Value(isset($mtarr['_role'])?$mtarr['_role']:-1, 'int');    
        $new_arr['_auto_mode']= new Value(isset($mtarr['_auto_mode'])?$mtarr['_auto_mode']:false, 'boolean');  
        $new_arr['_size']= new Value(isset($mtarr['_size'])?$mtarr['_size']:"", 'string');  
        $new_arr['_fps']= new Value(isset($mtarr['_fps'])?$mtarr['_fps']:30, 'int');  
        $new_arr['_mixermode']= new Value(isset($mtarr['_mixermode'])?$mtarr['_mixermode']:"MPVideoMixer::e_S1", 'string');  
        isset($mtarr['_osm']) &&  $new_arr['_osm']=$mtarr['_osm']; 
        isset($mtarr['_subscreens']) &&  $new_arr['_subscreens']=$mtarr['_subscreens']; 
        
        return new Value($new_arr,'struct');
    }
    /*
     * xs_SIPRegister参数集
     * @param  array  $mtarr      SIP注册信息
     * $mtarr 数组参数字段注释
     * @$mtarr   string          _authuser                         sip用户名,默认""
     * @$mtarr   string          _password                         sip密码,默认""
     * @return void
     */
    
    protected function xs_SIPRegister($mtarr=array()) {
        $new_arr=[];
        $new_arr['_authuser']= new Value(isset($mtarr['_authuser'])?$mtarr['_authuser']:"", 'string');
        $new_arr['_password']= new Value(isset($mtarr['_password'])?$mtarr['_password']:"", 'string');
        
        return new Value($new_arr,'struct');
    } 
    /*
     * xs_OnScreenMessage参数集
     * @param  array  $mtarr      滚动信息显示
     * $mtarr 数组参数字段注释
     * @$mtarr   int          _pos                         0–上方, 1–下方,默认1
     * @$mtarr   string       _msg                         滚动信息内容
     * @return void
     */
    
    protected function xs_OnScreenMessage($mtarr=array()) {
        $new_arr=[];
        $new_arr['_pos']= new Value(isset($mtarr['_pos'])?$mtarr['_pos']:1, 'int');
        $new_arr['_msg']  =new Value(isset($mtarr['_msg'])?$mtarr['_msg']:"", 'string');
        
        return new Value($new_arr,'struct');
    }
    
    /*
     * xs_SubScreen参数集
     * @param  array  $mtarr      子画面设置（轮询设置）
     * $mtarr 数组参数字段注释
     * @$mtarr   int          _subinterval                 子画面切换间隔。-1代表手动切换，>0代表自动轮巡间隔，<= -2 代表语音激励计算时长,默认-1
     * @$mtarr   XMLRPC       _subterminals                子画面终端列表,获取xs_DisplayStream能力集
     * @$mtarr   string       _displaying                  正在显示的终端，由MCU返回,默认""
     * @$mtarr   int          _pos                         子画面位置，从0开始计算，比如16分屏，则子画面编号有0-15，共16个可用,默认-1
     * @$mtarr   string       _ddmode                      接口使用者可以用来存储一个字符串，通过setup_screen设置，通过get_screen获取，MCU本身不解析,默认""
     * @return void
     */
    
    protected function xs_SubScreen($mtarr=array()) {
        $new_arr=[];
        $new_arr['_subinterval']= new Value(isset($mtarr['_subinterval'])?$mtarr['_subinterval']:-1, 'int');
        isset($mtarr['_subterminals']) &&  $new_arr['_subterminals']=$mtarr['_subterminals']; 
        $new_arr['_displaying']= new Value(isset($mtarr['_displaying'])?$mtarr['_displaying']:"", 'string');
        $new_arr['_pos']= new Value(isset($mtarr['_pos'])?$mtarr['_pos']:-1, 'int');
        $new_arr['_ddmode']= new Value(isset($mtarr['_ddmode'])?$mtarr['_ddmode']:"", 'string');
        
        
        return new Value($new_arr,'struct');
    }
    
    /*
     * xs_DisplayStream参数集
     * @param  array  $mtarr      子画面终端列表
     * $mtarr 数组参数字段注释
     * @$mtarr   string          _name                 终端名
     * @$mtarr   bool            _hasstream            是否有视频流，由MCU返回
     * @return void
     */
    
    protected function xs_DisplayStream($mtarr=array()) {
        $new_arr=[];
        $new_arr['_name']= new Value(isset($mtarr['_name'])?$mtarr['_name']:"", 'string');
        isset($mtarr['_hasstream']) &&  $new_arr['_hasstream']=new Value($mtarr['_hasstream'], 'boolean'); 
        
        return new Value($new_arr,'struct');
    }
    /*
     * xs_Terminal参数集
     * @param  array  $mtarr      终端列表参数集
     * $mtarr 数组参数字段注释
     * @$mtarr   string    _name                     终端名,必填
     * @$mtarr   int       _role                     0,1,2,3:主席，演讲者，听众，陌生人,默认为2，即听众
     * @$mtarr   bool     _start_by_this             内部使用
     * @return void
     */
    
    protected function xs_Terminal($mtarr=array()) {
        $new_arr=[];
        $new_arr['_name']= new Value(isset($mtarr['_name'])?$mtarr['_name']:'', 'string');
        $new_arr['_role']  =new Value(isset($mtarr['_role'])?$mtarr['_role']:2, 'int');
        $new_arr['_start_by_this']= new Value(isset($mtarr['_start_by_this'])?$mtarr['_start_by_this']:false, 'boolean');
        
        return new Value($new_arr,'struct');
    }
    /*
     * xs_Cap参数集
     * @param  array  $mtarr      模板能力参数集
     * $mtarr 数组参数字段注释
     * @$mtarr   int      _type                    音频、视频、双流、数据,取值范围（0,1,2,3）。3暂时无法使用
     * @$mtarr   int      _codec                   编码，音频编码从AudioCodecType中选取，视频编码从VideoCodecType中选取，详情参考文档，按序号从0开始,音频默认13，视频默认9都是自动协商
     * @$mtarr   int      _bitrate                 码率，取值范围（128000-8192000）,默认为0,_type为1时或者2设置，其他情况不设置
     * @$mtarr   int      _fps                     帧率，取值范围（1-60),默认0,_type为1时或者2设置，其他情况不设置
     * @$mtarr   int      _channel                 音频通道,默认为1,用途待定，_type为0或者2时设置为1，其他情况不设置
     * @$mtarr   string   _sizes                   视频大小，格式：宽x高,宽x高,宽x高,_type为1时或者2设置为空，其他情况不设置
     * @return void
     */  
    protected function xs_Cap($mtarr=array()) {
        $new_arr=[];
        $mtarr_type= isset($mtarr['_type'])?$mtarr['_type']:0;
        switch($mtarr_type){
            case 0: //音频
                $new_arr['_type']= new Value(0, 'int');
                $new_arr['_codec']= new Value(isset($mtarr['_codec'])?$mtarr['_codec']:13, 'int');
                $new_arr['_channel']= new Value(isset($mtarr['_channel'])?$mtarr['_channel']:1, 'int');
                break;
            case 1:
                $new_arr['_type']= new Value(1, 'int');
                $new_arr['_codec']= new Value(isset($mtarr['_codec'])?$mtarr['_codec']:9, 'int');
                $new_arr['_bitrate']= new Value(isset($mtarr['_bitrate'])?$mtarr['_bitrate']:0, 'int');
                $new_arr['_fps']= new Value(isset($mtarr['_fps'])?$mtarr['_fps']:0, 'int');
                $new_arr['_sizes']= new Value(isset($mtarr['_sizes'])?$mtarr['_sizes']:"", 'string');
                break;
            case 2:
                $new_arr['_type']= new Value(2, 'int');
                $new_arr['_codec']= new Value(isset($mtarr['_codec'])?$mtarr['_codec']:9, 'int');
                $new_arr['_bitrate']= new Value(isset($mtarr['_bitrate'])?$mtarr['_bitrate']:0, 'int');
                $new_arr['_fps']= new Value(isset($mtarr['_fps'])?$mtarr['_fps']:0, 'int');
                $new_arr['_sizes']= new Value(isset($mtarr['_sizes'])?$mtarr['_sizes']:"", 'string');
                break;
        }
        
        
        return new Value($new_arr,'struct');
        
        
    }
    /*
     * 返回消息
     */
   public function response_msg($r=array(),$msg=''){
       if ($r->faultCode()){ //失败时
//           return "An error occurred: ";
           return "Code: " . htmlspecialchars($r->faultCode()) . " Reason: '" . htmlspecialchars($r->faultString()) . "'\n";
        }else
        {
          
           
           $value = xmlrpc_decode($r->value());
           
           switch ($value) {
               case 0:

               return '成功';
                   break;
               case 1:

               return '其它错误';
                   break;
               case 2:

               return '资源不存在';
                   break;
               case 3:

               return '超出资源限制';
                   break;
               case 4:

               return '已经存在';
                   break;
               case 5:

               return '资源忙';
                   break;

               default:
                   
                return '意料之外的错误';
                   break;
           }
           
          
        }
   }
   
   
   
     /*
     * 请求mcu接口
     */
    public function send_msg($methodname,$params){
        $xmlrpc=new Request($methodname,$params);
        
        $params=$xmlrpc->serialize('UTF-8');

        /*** client side ***/
        $c = new Client($this->api_path,$this->api_domain,$this->api_port);

        // tell the client to return raw xml as response value
        $c->return_type = $this->return_type;
        $c->setCredentials($this->username, $this->password);  //设置用户验证
        // let the native xmlrpc extension take care of encoding request parameters
        $r = $c->send($params);

        return $this->response_msg($r);
        
        
    }
}
