<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVCallbackBase')) :

class BVCallbackBase {

	public static $wing_infos = array("BRAND_WING_VERSION" => '1.0',
		"DB_WING_VERSION" => '1.0',
		"ACCOUNT_WING_VERSION" => '1.0',
		"MISC_WING_VERSION" => '1.1',
		"FS_WING_VERSION" => '1.0',
		"INFO_WING_VERSION" => '1.1',
		);

	public function objectToArray($obj) {
		return json_decode(json_encode($obj), true);
	}

	public function base64Encode($data, $chunk_size) {
		if ($chunk_size) {
			$out = "";
			$len = strlen($data);
			for ($i = 0; $i < $len; $i += $chunk_size) {
				$out .= base64_encode(substr($data, $i, $chunk_size));
			}
		} else {
			$out = base64_encode($data);
		}
		return $out;
	}
}
endif;