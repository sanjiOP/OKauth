<?php

namespace OKauth\Providers;


/**
 * Class Weibo
 * @package OKAuth\Providers
 */
class Weibo extends Baseprovider
{



    /*------------------------------------------------
     * 客户端公钥
     * -----------------------------------------------
     *
     * */
    private $app_key = '';





    /*------------------------------------------------
     * 客户端私钥
     * -----------------------------------------------
     *
     * */
    private $app_secret = '';






    /*------------------------------------------------
     * 回调地址
     * -----------------------------------------------
     * 当用户在coding页面授权通过后,会重定向到该定义的页面,并且附带code参数
     * http://oauth2.ruaby.com/api/callback/weibo?code=37eeh974k0s9s
     * */
    private $callback = '';






    /*------------------------------------------------
     * weibo授权页面
     * -----------------------------------------------
     *
     * */
    private $authorizeUrl = 'https://api.weibo.com/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s';







    /*------------------------------------------------
     * @abstract 用户授权引导
     * -----------------------------------------------
     *
     * */
    public function authorize_url(){
        header("Location:".sprintf($this->authorizeUrl,$this->app_key,urlencode($this->callback)));
        exit;
    }






    /*------------------------------------------------
     * @get oauth_user 唯一标识
     * -----------------------------------------------
     *
     * */
    public function get_oauth_id(){
        if(!$this->oauth_id){
            throw new \Exception('error');
        }
        return $this->oauth_id;
    }






    /*------------------------------------------------
     * @abstract access_token 接口
     * -----------------------------------------------
     *
     * */
    protected function _pull_access_token($code=''){

        if(empty($code)){
            $this->error_code = 80;
            $this->error = '授权失败';
            return false;
        }
        $accessTokenUrl = 'https://api.weibo.com/oauth2/access_token';
        $post                   = [];
        $post['client_id']      = $this->app_key;
        $post['client_secret']  = $this->app_secret;
        $post['grant_type']     = 'authorization_code';
        $post['redirect_uri']   = $this->callback;
        $post['code']           = $code;
        $token                  = $this->request->post($accessTokenUrl,$post);
        if($token){
            $this->_pull_oauth_id($token);
            $this->access_token = $token;
            return true;
        }else{
            $this->error_code   = 30;
            $this->error        = '[weibo] 网络请求错误';
            return false;
        }
    }







    /*------------------------------------------------
     * @private 通过接口获取用于的唯一标识
     * -----------------------------------------------
     * 此处跟QQ不同,微博在获取access_token阶段同时返回类uid,所以不需要再次pull
     * */
    private function _pull_oauth_id($token){
        $this->oauth_id = $token['uid'];
    }








    /*------------------------------------------------
     * @abstract oauth_user_info 接口
     * -----------------------------------------------
     *
     * */
    protected function _pull_account_user(){

        $api        = 'https://api.weibo.com/2/users/show.json?access_token=%s&uid=%u';
        $response   = $this->request->get(sprintf($api,$this->get_access_token(),$this->oauth_id));
        if($response){
            $this->oauth_user_info = $response;
            return true;
        }else{
            $this->error_code   = 30;
            $this->error        = '[weibo] 网络请求错误';
            return false;
        }
    }

}
