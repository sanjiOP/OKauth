<?php

namespace OKauth\Providers;


/**
 * Class Coding
 * @package OKAuth\Providers
 */

abstract class Baseprovider
{


   /*------------------------------------------------
    * ProviderTrait
    * -----------------------------------------------
    *
    * */
    use ProviderTrait;





    /*------------------------------------------------
     * http请求类
     * -----------------------------------------------
     * 提供get和post方式请求
     *
     * */
    public $request;






    /*------------------------------------------------
     * 错误信息
     * -----------------------------------------------
     *
     * */
    protected $error;







    /*------------------------------------------------
     * 错误码
     * -----------------------------------------------
     *
     * */
    protected $error_code = 0;






    /*------------------------------------------------
     * 用户身份标示编号
     * -----------------------------------------------
     *
     * */
    protected $oauth_id;






   /*------------------------------------------------
    * 用户身份令牌票据
    * -----------------------------------------------
    *
    * */
    protected $access_token = [];







    /*------------------------------------------------
     * 用户基本数据
     * -----------------------------------------------
     *
     * */
    protected $oauth_user_info = [];






    /*------------------------------------------------
     * @__construct 构造器
     * -----------------------------------------------
     *
     * */
    public function __construct($request=null){

        if(is_string($request)){
            $this->request = new $request;
        }elseif(is_object($request)){
            $this->request  = $request;
        }

    }







    /*------------------------------------------------
     * @abstract 用户授权引导
     * -----------------------------------------------
     *
     * */
    abstract public function authorize_url();







    /*------------------------------------------------
     * @abstract access_token 接口
     * -----------------------------------------------
     *
     * */
    abstract protected function _pull_access_token($code);








    /*------------------------------------------------
     * @abstract oauth_user_info 接口
     * -----------------------------------------------
     *
     * */
    abstract protected function _pull_account_user();







    /*------------------------------------------------
     * @abstract oauth_user 唯一标识
     * -----------------------------------------------
     *
     * */
    abstract public function get_oauth_id();





    /*------------------------------------------------
     * @get 错误信息
     * -----------------------------------------------
     *
     * */
    public function get_error(){
        return ['error_code'=>$this->error_code,'error'=>$this->error];
    }







    /*------------------------------------------------
     * @callback 授权回调的入口方法
     * -----------------------------------------------
     *
     * */
    public function authorize_callback(){
        $code = $_GET['code'];
        return $this->_pull_access_token($code);
    }








    /*------------------------------------------------
     * @get 第三方用户基本信息
     * -----------------------------------------------
     *
     * */
    public function get_account_user(){

        if(!$this->access_token){
            throw new \Exception('access_token is not exist');
        }
        if($this->oauth_user_info){
            return $this->oauth_user_info;
        }else{
            if($this->_pull_account_user()){
                return $this->oauth_user_info;
            }else{
                return null;
            }
        }
    }







    /*------------------------------------------------
     * @get access_token
     * -----------------------------------------------
     *
     * */
    public function get_access_token($field='access_token'){
        if($this->access_token){
            return isset($this->access_token[$field]) ? $this->access_token[$field] : $this->access_token;
        }else{
            throw new \Exception('access_token is not exist');
        }
    }





    /*------------------------------------------------
     * 刷新access_token的有效期
     * -----------------------------------------------
     * access_token一般有效期3个月
     * refresh_token长久有效,在重新授权后,会生成新的refresh_token
     * 该方法需要[网站应用方]主动发起,[网站应用方]主动监测到[用户]的 access_token 过期后,执行该方法
     * 重新获取access_token和expires_in,refresh_token,并自行保存数据库,待下次监测
     * */
    public function refresh_access_token_expires_in($refresh_token){

        //TODO 暂未实现该方法
        return false;
    }






}
