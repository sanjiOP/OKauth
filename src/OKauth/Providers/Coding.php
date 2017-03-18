<?php

namespace OKauth\Providers;


/**
 * Class Coding
 * @package OKAuth\Providers
 */
class Coding extends Baseprovider
{




    /*------------------------------------------------
     * 客户端公钥
     * -----------------------------------------------
     *
     * */
    private $client_id = '';





   /*------------------------------------------------
    * 客户端公钥
    * -----------------------------------------------
    *
    * */
    private $client_secret = '';





    /*------------------------------------------------
     * 回调地址
     * -----------------------------------------------
     * 当用户在coding页面授权通过后,会重定向到该定义的页面,并且附带code参数
     * http://oauth2.ruaby.com/api/callback/coding?code=37eeh974k0s9s
     * */
    private $callback = '';






    /*------------------------------------------------
     * coding授权页面
     * -----------------------------------------------
     *
     * */
    private $authorizeUrl = 'https://coding.net/oauth_authorize.html?client_id=%s&redirect_uri=%s&response_type=code&scope=%s';







    /*------------------------------------------------
     * @abstract 用户授权引导
     * -----------------------------------------------
     *
     * */
    public function authorize_url(){
        $scope = 'user,project';
        header("Location:".sprintf($this->authorizeUrl,$this->client_id,$this->callback,$scope));
        exit;
    }






    /*------------------------------------------------
     * @get oauth_user 唯一标识
     * -----------------------------------------------
     *
     * */
    public function get_oauth_id(){
        if(!$this->oauth_id){
            $this->get_account_user();
            $this->oauth_id = $this->oauth_user_info['id'];
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
        $accessTokenUrl = 'https://coding.net/api/oauth/access_token?client_id=%s&client_secret=%s&grant_type=authorization_code&code=%s';
        $token = $this->request->get(sprintf($accessTokenUrl,$this->client_id,$this->client_secret,$code));
        if($token){
            $this->access_token = $token;
            return true;
        }else{
            $this->error_code   = 30;
            $this->error        = '[coding] 网络请求错误';
            return false;
        }
    }







    /*------------------------------------------------
     * @abstract oauth_user_info 接口
     * -----------------------------------------------
     *
     * */
    protected function _pull_account_user(){

        if(!$this->access_token){
            throw new \Exception('access_token is not exist');
        }

        $api = 'https://coding.net/api/current_user?access_token=%s';
        $response   = $this->request->get(sprintf($api,$this->get_access_token()));
        if($response['code'] == 0){
            $this->oauth_user_info = $response['data'];
            return true;
        }else{
            $this->error_code   = 30;
            $this->error        = '[coding] 网络请求错误';
            return false;
        }
    }



}
