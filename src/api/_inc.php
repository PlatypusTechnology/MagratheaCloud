<?php

use MagratheaCloud\File\File;

require "../vendor/autoload.php";
//include("shared/Helper.php");

try {
	Magrathea2\MagratheaPHP::Instance()
		->AppPath(realpath(dirname(__FILE__)))
		->AddCodeFolder("CloudApi")
		->AddCodeFolder(__DIR__."/Authentication")
		->AddFeature("Apikey", "File", "Folder", "Sharekey")
		->Load();
} catch(Exception $ex) {
	\Magrathea2\p_r($ex);
}
