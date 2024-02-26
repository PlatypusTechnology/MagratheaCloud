<?php

use MagratheaCloud\File\File;

require "../vendor/autoload.php";
//include("shared/Helper.php");

try {
	Magrathea2\MagratheaPHP::Instance()
		->AppPath(realpath(dirname(__FILE__)))
		->AddCodeFolder(
			"CloudApi",
		)
		->AddFeature("Apikey", "File", "Sharekey")
		->Load();
} catch(Exception $ex) {
	\Magrathea2\p_r($ex);
}
