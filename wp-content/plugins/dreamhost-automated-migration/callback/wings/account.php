<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVAccountCallback')) :
class BVAccountCallback extends BVCallbackBase {
	public $account;
	public $settings;
	
	const ACCOUNT_WING_VERSION = 1.0;

	public function __construct($callback_handler) {
		$this->account = $callback_handler->account;
		$this->settings = $callback_handler->settings;
	}

	function process($request) {
		$params = $request->params;
		$account = $this->account;
		$settings = $this->settings;
		switch ($request->method) {
		case "addacc":
			DHAccount::addAccount($this->settings, $params['public'], $params['secret']);
			$resp = array("status" => DHAccount::exists($this->settings, $params['public']));
			break;
		case "rmacc":
			$resp = array("status" => DHAccount::remove($this->settings, $params['public']));
			break;
		case "updt":
			$account->updateInfo($params);
			$resp = array("status" => DHAccount::exists($this->settings, $params['pubkey']));
			break;
		case "updtapikey":
			DHAccount::updateApiPublicKey($this->settings, $params['pubkey']);
			$resp = array("status" => $this->settings->getOption(DHAccount::$api_public_key));
			break;
		case "rmbvscrt":
			$resp = array("status" => $settings->deleteOption('bvSecretKey'));
			break;
		case "rmbvkeys":
			$resp = array("status" => $settings->deleteOption('bvKeys'));
			break;
		case "rmdefpub":
			$resp = array("status" => $settings->deleteOption('bvDefaultPublic'));
			break;
		case "rmoldbvacc":
			$resp = array("status" => $settings->deleteOption('bvAccounts'));
			break;
		case "fetch":
			$resp = array("status" => DHAccount::allAccounts($this->settings));
			break;
		default:
			$resp = false;
		}
		return $resp;
	}
}
endif;