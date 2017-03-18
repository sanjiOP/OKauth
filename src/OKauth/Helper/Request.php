<?php
/**
 * @ling http://www.ruaby.com/
 * @copyright Copyright (c) 2016 RuabySoft LLC
 * @license http://www.ruaby.com/license/
 * @createTime 17/3/16 下午5:13
 */
namespace OKauth\Helper;


class Request{


    /**
     * 构造器
     * */
    public function __construct(){}




    /**
     * http post
     * */
    public function post($url,$data){
        $response = \Requests::post($url,[],$data);
        return $this->json_response($response);
    }


    /**
     * http get
     * */
    public function get($url,$format='json'){
        $response = \Requests::get($url);

        switch($format){
            case 'json':
                return $this->json_response($response);
                break;
            case 'http_query':
                return $this->http_query_response($response);
                break;
            case false:
                return $response;
            default:
                return null;
        }
    }


    /**
     * 请求响应数据返回
     *
     * */
    private function json_response($response){

        if(200 == $response->status_code){
            $body = $response->body;
            return json_decode($body,true);
        }else{
            return false;
        }
    }




    /*
     * 请求响应数据返回
     * */
    private function http_query_response($response){
        if(200 == $response->status_code){
            parse_str($response->body,$body);
            return $body;
        }else{
            return false;
        }
    }


}