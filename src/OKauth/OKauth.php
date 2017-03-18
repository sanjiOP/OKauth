<?php

namespace OKauth;

/**
 * @ling http://www.ruaby.com/
 * @copyright Copyright (c) 2016 RuabySoft LLC
 * @license http://www.ruaby.com/license/
 * @createTime 17/3/11 上午12:24
 */
class OKauth
{


    /**
     * The OKauth version
     *
     * @var string
     */
    const VERSION = '0.1.1';




    static public $providers = [
        'coding'    =>['class'=>'\OKauth\Providers\Coding', 'request'=>'\OKauth\Helper\Request'],
        'weibo'     =>['class'=>'\OKauth\Providers\Weibo',  'request'=>'\OKauth\Helper\Request'],
        'qq'        =>['class'=>'\OKauth\Providers\Qq',     'request'=>'\OKauth\Helper\Request'],
    ];

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public static function version()
    {
        return static::VERSION;
    }


    /**
     * @param $provider
     * @return mixed
     * @throws \Exception
     *
     */
    public static function getProvider($provider){
        return self::createObject(static::$providers[$provider]);
    }


    /**
     * 创建对象
     *
     */
    public static function createObject($type){

        if (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            return new $class($type['request']);
        } else {
            throw new \Exception('Object configuration must be an array containing a "class" element.');
        }

    }


}