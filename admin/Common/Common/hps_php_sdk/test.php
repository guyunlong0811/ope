<?php
require_once "CBaseHpsInterface.php";
 class AccountInfoInterface extends CBaseHpsInterface
 {

     private static $instance = null;

     private $url_queryDisplayAccount;

     public static function getInstance()
     {
         if (self::$instance == null) {
             self::$instance = new self();
         }
         return self::$instance;
     }


     public function __construct()
     {
         parent::__construct("http://test.hps.sdo.com", "opus", "123456", 1, -1);

         $this->url_queryDisplayAccount = '/apl_displayacc/displayacc.displayAccount';
     }


     public function queryDisplayAccount($sdid)
     {
         $uriAttribs = array(
             'sdid' => $sdid,
         );

         $ret = $this->callHttpRequestWithLog($this->url_queryDisplayAccount, $uriAttribs, 10);
         if (!$ret) {
             return false;
         }

         return json_decode($ret, true);
     }
 }


function GetDisplayAccount($sdid)
{
    $ret = AccountInfoInterface::getInstance()->queryDisplayAccount($sdid);

    if (!isset($ret['return_code']) || $ret['return_code'] !== 0) {
        return false;
    }

    return $ret['data']['account'][0];
}

$account = GetDisplayAccount("3154832989");
var_dump($account);
