<?php

require "../vendor/autoload.php";

try {
	Magrathea2\MagratheaPHP::Instance()
		->AppPath(realpath(dirname(__FILE__)))
		->AddCodeFolder("CloudApi", "shared")
		->AddCodeFolder(__DIR__."/Authentication")
		->AddFeature("Apikey", "Crawl", "File", "Folder", "Sharekey")
		->Load();
} catch(Exception $ex) {
	\Magrathea2\p_r($ex);
}
