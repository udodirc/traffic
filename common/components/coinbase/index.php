<?php
require_once(dirname(__FILE__) . '/coinbase/Coinbase.php');

$api_key = "wFQgfEdmgzyOLdhJ";
$api_secret = "g4JeqaDjU4HSNK8cjzoyKj34e72ZmVn8";

$coinbase = Coinbase::withApiKey($api_key, $api_secret);
$user     = $coinbase->getUser();

//var_dump($user);
?>
