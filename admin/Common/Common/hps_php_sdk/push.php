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
         parent::__construct("http://hps.sdo.com", SNDA_HPS_USER, SNDA_APP_KEY, SNDA_APP_ID, -1);
         $this->url_queryDisplayAccount = '/youyun/createTask';
     }


     public function push($params)
     {
         $ret = $this->callHttpRequestWithLog($this->url_queryDisplayAccount, $params, 10);
         if (!$ret) {
             return false;
         }

         return json_decode($ret, true);
     }
 }