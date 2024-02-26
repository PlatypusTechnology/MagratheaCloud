<?php

include("_inc.php");
include("admin/CloudAdmin.php");

use Magrathea2\Admin\AdminManager;

try {
	AdminManager::Instance()->Start(new MagratheaCloud\CloudAdmin());
} catch(Exception $ex) {
	\Magrathea2\p_r($ex);
}
