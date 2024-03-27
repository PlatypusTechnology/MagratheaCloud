<?php

include("_inc.php");
include("api.php");

$api = new MagratheaCloud\MagratheaCloudApi();

if(@$_GET["debug"] == "true") {
	$api->Debug();
	die;
}
$api->Run();

