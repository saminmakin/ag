<?php

defined("ABSPATH") or exit();
$username = "989057338842"; // نام کاربری شما
$password = "97dbf07e-4e82-4af7-8526-b13448937ab6"; // کلمه عبور شما
$apiMainurl = "http://api.payamak-panel.com";

class ConnectToApi
{
    public $url;
    public $username;
    public $password;
    public function __construct($MainUrl, $username, $password)
    {
        $this->url = $MainUrl;
        $this->username = $username;
        $this->password = $password;
    }
    
    public function Exec($urlpath, $req)
    {
        try {
            $url = $this->url . $urlpath . "?" . http_build_query($req);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (Exception $ex) {
            return "";
        }
    }
}

?>