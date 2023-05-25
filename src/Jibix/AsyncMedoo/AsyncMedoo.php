<?php
namespace Jibix\AsyncMedoo;
use Exception;


/**
 * Class AsyncMedoo
 * @package Jibix\AsyncMedoo
 * @author Jibix
 * @date 26.05.2023 - 00:58
 * @project AsyncMedoo
 */
class AsyncMedoo{
    
    private static ?MySQLCredentials $credentials = null;
    
    public static function initialize(MySQLCredentials $credentials): void{
        include_once __DIR__ . "/util/Functions.php";
        self::$credentials = $credentials;
    }

    /**
     * Function getCredentials
     * @return MySQLCredentials
     * @throws Exception
     */
    public static function getCredentials(): MySQLCredentials{
        return self::$credentials ?? throw new Exception("The MySQL credentials are not initialized yet!");
    }
}