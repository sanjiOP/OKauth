<?php
/**
 * @ling http://www.ruaby.com/
 * @copyright Copyright (c) 2016 RuabySoft LLC
 * @license http://www.ruaby.com/license/
 * @createTime 17/3/11 下午2:53
 */
namespace OKauth;

class Application
{



    private $provider;


    /**
     * 构造器
     *
     * */
    public function __construct($provider){
        $this->provider = OKauth::getProvider($provider);
    }




    /**
     * 错误信息
     *
     * */
    public function getError(){
        return $this->getProvider()->getError();
    }



    /**
     * 登录驱动
     * */
    public function getProvider(){
        return $this->provider;
    }


    /**
     * 授权引导
     * */
    public function authorize_url(){
        $this->provider->authorize_url();
    }


    /**
     * 授权后处理
     * */
    public function authorize_callback(){
        return $this->provider->authorize_callback();
    }


    /**
     * 获取oauth_id
     * */
    public function get_oauth_id(){
        return $this->provider->get_oauth_id();
    }


    /**
     * 获取
     * */
    public function get_access_token($field='access_token'){
        return $this->provider->get_access_token($field);
    }



    /**
     * 获取用户信息
     * */
    public function get_account_user(){
        return $this->provider->get_account_user();
    }

}