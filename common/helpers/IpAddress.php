<?php
  
namespace common\helpers;

use Yii;
use common\components\CommonHelper;
use common\models\User;
use common\components\CommonException;



class IpAddress
{
  public static function get_ip()
  {
        $headers = $_SERVER;

		if ( array_key_exists( 'X-Forwarded-For', $headers )) {
          
		   $the_ip = $headers['X-Forwarded-For'];
           
		} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers )) {
          
		   $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
           
		} else {
          
		   $the_ip = $_SERVER['REMOTE_ADDR'];
           
		}
        
      return $the_ip;

   }
}