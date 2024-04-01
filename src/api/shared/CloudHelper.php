<?php

namespace MagratheaCloud;

class CloudHelper {
	public static function GetSize($size): string {
		if(empty($size)) return "-";
		$kb = $size / 1024;
		if($kb < 1024) return round($kb, 2, PHP_ROUND_HALF_UP)." KB";
		$mb = $kb / 1024;
		if($mb < 1024) return round($mb, 2, PHP_ROUND_HALF_UP)." MB";
		$gb = $mb / 1024;
		return round($gb, 2, PHP_ROUND_HALF_UP)." GB";
	}

	public static function cleanString(string $val): string {
		$respace = "_";
		$val = str_replace(' ', $respace, $val);
		$val = str_replace('\\', $respace, $val);
		$val = str_replace('/', $respace, $val);
		$utf8 = array(
			'/[áàâãªä]/u'   =>   'a',
			'/[ÁÀÂÃÄ]/u'    =>   'A',
			'/[ÍÌÎÏ]/u'     =>   'I',
			'/[íìîï]/u'     =>   'i',
			'/[éèêë]/u'     =>   'e',
			'/[ÉÈÊË]/u'     =>   'E',
			'/[óòôõºö]/u'   =>   'o',
			'/[ÓÒÔÕÖ]/u'    =>   'O',
			'/[úùûü]/u'     =>   'u',
			'/[ÚÙÛÜ]/u'     =>   'U',
			'/ç/'           =>   'c',
			'/Ç/'           =>   'C',
			'/ñ/'           =>   'n',
			'/Ñ/'           =>   'N',
			'/[,:.%]/'			=>	 '',
			'/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
			'/[’‘‹›‚]/u'    =>   '', // Literally a single quote
			'/[“”«»„]/u'    =>   '', // Double quote
			'/ /'           =>   $respace, // nonbreaking space (equiv. to 0x160)
		);
		$val = preg_replace(array_keys($utf8), array_values($utf8), $val);
		return $val;
	}

	public static function IsGDWorking(): bool {
		return function_exists('gd_info');
	}
}
