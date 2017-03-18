<?php

namespace OKauth\Providers;


/**
 * Class QQ
 * @package OKAuth\Providers
 */
class Qq extends Baseprovider
{



    /*------------------------------------------------
     * 客户端公钥
     * -----------------------------------------------
     *
     * */
    private $app_id = '';





    /*------------------------------------------------
     * 客户端私钥
     * -----------------------------------------------
     *
     * */
    private $app_key = '';






    /*------------------------------------------------
     * 回调地址
     * -----------------------------------------------
     * 当用户在coding页面授权通过后,会重定向到该定义的页面,并且附带code参数
     * http://oauth2.ruaby.com/api/callback/qq?code=972562D655E0F1AEB04B9F4EF6D1C136&state=oauth_qq_ruaby
     * */
    private $callback = '';






    /*------------------------------------------------
     * qq授权页面
     * -----------------------------------------------
     * *response_type   code
     * *client_id       $this->app_id
     * *redirect_uri    $this->callback
     * *state           oauth_qq_ruaby
     * *scope           get_user_info,list_album,upload_pic,do_like
     *
     *
     * */
    private $authorize_url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=%s&redirect_uri=%s&state=oauth_qq_ruaby&scope=%s';







    /*------------------------------------------------
     * @abstract 用户授权引导
     * -----------------------------------------------
     *
     * */
    public function authorize_url(){
        $scope = 'get_user_info';
        header("Location:".sprintf($this->authorize_url,$this->app_id,urlencode($this->callback),$scope));
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
            $this->error_code   = 80;
            $this->error        = '授权失败';
            return false;
        }
        $access_token_url       = 'https://graph.qq.com/oauth2.0/token?%s';
        $get                    = [];
        $get['grant_type']      = 'authorization_code';
        $get['client_id']       = $this->app_id;
        $get['client_secret']   = $this->app_key;
        $get['redirect_uri']    = $this->callback;
        $get['code']            = $code;
        $token                  = $this->request->get(sprintf($access_token_url,http_build_query($get)),'http_query');
        if($token){
            $this->_pull_oauth_id($token);
            $this->access_token = $token;
            return true;
        }else{
            $this->error_code   = 30;
            $this->error        = '[qq] 网络请求错误';
            return false;
        }
    }






    /*------------------------------------------------
     * @private 通过接口获取用于的唯一标识
     * -----------------------------------------------
     * 此处跟weibo不同,QQ需要再次pull获取open_id
     * */
    private function _pull_oauth_id($token){
        $api        = 'https://graph.qq.com/oauth2.0/me?%s';
        $response   = $this->request->get(sprintf($api,http_build_query(['access_token'=>$token['access_token']])),false);
        if(200 == $response->status_code){
            if(preg_match('/{[\s\S]+}/i',$response->body,$open_id_str)){
                $open_id_arr    = json_decode($open_id_str[0],true);
                $this->oauth_id = $open_id_arr['openid'];
                return true;
            }
        }
        $this->oauth_id = null;
        return false;
    }







    /*------------------------------------------------
     * @abstract oauth_user_info 接口
     * -----------------------------------------------
     *
     * */
    protected function _pull_account_user(){

        $api        = 'https://graph.qq.com/user/get_user_info?access_token=%s&oauth_consumer_key=%s&openid=%s';
        $response   = $this->request->get(sprintf($api,$this->get_access_token(),$this->app_id,$this->get_oauth_id()));
        if($response){
            $this->oauth_user_info = $response;
            return true;
        }else{
            $this->error_code   = 30;
            $this->error        = '[qq] 网络请求错误';
            return false;
        }
    }

}
