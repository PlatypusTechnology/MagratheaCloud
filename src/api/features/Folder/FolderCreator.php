<?php
namespace MagratheaCloud\Folder;

use Magrathea2\Exceptions\MagratheaApiException;

class FolderCreator {

	public static function Create($path) {
		if (!file_exists($path)) {
			$success = @mkdir($path);
			if(!$success) {
				$err = error_get_last();
				throw new MagratheaApiException($err['message'], 500, ["path" => $path]);
			}
			return $success;
		}
	}

}
