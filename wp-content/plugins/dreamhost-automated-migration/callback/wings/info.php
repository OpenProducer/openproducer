<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVInfoCallback')) :

class BVInfoCallback extends BVCallbackBase {
	public $db;
	public $settings;
	public $siteinfo;
	public $bvinfo;
	
	const INFO_WING_VERSION = 1.1;

	public function __construct($callback_handler) {
		$this->db = $callback_handler->db;
		$this->siteinfo = $callback_handler->siteinfo;
		$this->settings = $callback_handler->settings;
		$this->bvinfo = new DHInfo($this->settings);
	}

	public function getPosts($post_type, $count = 5) {
		$output = array();
		$args = array('numberposts' => $count, 'post_type' => $post_type);
		$posts = get_posts($args);
		$keys = array('post_title', 'guid', 'ID', 'post_date');
		$result = array();
		foreach ($posts as $post) {
			$pdata = array();
			$post_array = get_object_vars($post);
			foreach ($keys as $key) {
				$pdata[$key] = $post_array[$key];
			}
			$result["posts"][] = $pdata;
		}
		return $result;
	}

	public function getStats() {
		return array(
			"posts" => get_object_vars(wp_count_posts()),
			"pages" => get_object_vars(wp_count_posts("page")),
			"comments" => get_object_vars(wp_count_comments())
		);
	}

	public function getPlugins() {
		if (!function_exists('get_plugins')) {
			require_once (ABSPATH."wp-admin/includes/plugin.php");
		}
		$plugins = get_plugins();
		$result = array();
		foreach ($plugins as $plugin_file => $plugin_data) {
			$pdata = array(
				'file' => $plugin_file,
				'title' => $plugin_data['Title'],
				'version' => $plugin_data['Version'],
				'active' => is_plugin_active($plugin_file),
				'network' => $plugin_data['Network']
			);
			$result["plugins"][] = $pdata;
		}
		return $result;
	}

	public function themeToArray($theme) {
		if (is_object($theme)) {
			$pdata = array(
				'name' => $theme->Name,
				'title' => $theme->Title,
				'stylesheet' => $theme->get_stylesheet(),
				'template' => $theme->Template,
				'version' => $theme->Version
			);
		} else {
			$pdata = array(
				'name' => $theme["Name"],
				'title' => $theme["Title"],
				'stylesheet' => $theme["Stylesheet"],
				'template' => $theme["Template"],
				'version' => $theme["Version"]
			);
		}
		return $pdata;
	}

	public function getThemes() {
		$result = array();
		$themes = function_exists('wp_get_themes') ? wp_get_themes() : get_themes();
		foreach($themes as $theme) {
			$pdata = $this->themeToArray($theme);
			$result["themes"][] = $pdata;
		}
		$theme = function_exists('wp_get_theme') ? wp_get_theme() : get_current_theme();
		$pdata = $this->themeToArray($theme);
		$result["currenttheme"] = $pdata;
		return $result;
	}

	public function getSystemInfo() {
		$sys_info = array(
			'host' => $_SERVER['HTTP_HOST'],
			'phpversion' => phpversion(),
			'AF_INET6' => defined('AF_INET6')
		);
		if (array_key_exists('SERVER_ADDR', $_SERVER)) {
			$sys_info['serverip'] = $_SERVER['SERVER_ADDR'];
		}
		if (function_exists('get_current_user')) {
			$sys_info['user'] = get_current_user();
		}
		if (function_exists('getmygid')) {
			$sys_info['gid'] = getmygid();
		}
		if (function_exists('getmyuid')) {
			$sys_info['uid'] = getmyuid();
		}
		if (function_exists('posix_getuid')) {
			$sys_info['webuid'] = posix_getuid();
			$sys_info['webgid'] = posix_getgid();
		}
		return $sys_info;
	}

	public function getWpInfo() {
		global $wp_version, $wp_db_version, $wp_local_package;
		$siteinfo = $this->siteinfo;
		$db = $this->db;
		$upload_dir = wp_upload_dir();

		$wp_info = array(
			'dbprefix' => $db->dbprefix(),
			'wpmu' => $siteinfo->isMultisite(),
			'mainsite' => $siteinfo->isMainSite(),
			'main_site_id' => $siteinfo->getMainSiteId(),
			'name' => get_bloginfo('name'),
			'siteurl' => $siteinfo->siteurl(),
			'homeurl' => $siteinfo->homeurl(),
			'charset' => get_bloginfo('charset'),
			'wpversion' => $wp_version,
			'dbversion' => $wp_db_version,
			'mysql_version' => $db->getMysqlVersion(),
			'abspath' => ABSPATH,
			'bvpluginpath' => defined('DHBASEPATH') ? DHBASEPATH : null,
			'uploadpath' => $upload_dir['basedir'],
			'uploaddir' => wp_upload_dir(),
			'contentdir' => defined('WP_CONTENT_DIR') ? WP_CONTENT_DIR : null,
			'contenturl' => defined('WP_CONTENT_URL') ? WP_CONTENT_URL : null,
			'plugindir' => defined('WP_PLUGIN_DIR') ? WP_PLUGIN_DIR : null,
			'dbcharset' => defined('DB_CHARSET') ? DB_CHARSET : null,
			'disallow_file_edit' => defined('DISALLOW_FILE_EDIT'),
			'disallow_file_mods' => defined('DISALLOW_FILE_MODS'),
			'custom_users' => defined('CUSTOM_USER_TABLE') ? CUSTOM_USER_TABLE : null,
			'custom_usermeta' => defined('CUSTOM_USERMETA_TABLE') ? CUSTOM_USERMETA_TABLE : null,
			'locale' => get_locale(),
			'wp_local_string' => $wp_local_package,
			'charset_collate' => $db->getCharsetCollate()
		);
		return array("wp" => $wp_info);
	}

	public function getUsers($full, $args = array()) {
		$results = array();
		$users = get_users($args);
		if ('true' == $full) {
			$results = $this->objectToArray($users);
		} else {
			foreach( (array) $users as $user) {
				$result = array();
				$result['user_email'] = $user->user_email;
				$result['ID'] = $user->ID;
				$result['roles'] = $user->roles;
				$result['user_login'] = $user->user_login;
				$result['display_name'] = $user->display_name;
				$result['user_registered'] = $user->user_registered;
				$result['user_status'] = $user->user_status;
				$result['user_url'] = $user->url;

				$results[] = $result;
			}
		}
		return $results;
	}
	
	public function availableFunctions(&$info) {
		if (extension_loaded('openssl')) {
			$info['openssl'] = "1";
		}
		if (function_exists('is_ssl') && is_ssl()) {
			$info['https'] = "1";
		}
		if (function_exists('openssl_public_encrypt')) {
			$info['openssl_public_encrypt'] = "1";
		}
		if (function_exists('openssl_public_decrypt')) {
			$info['openssl_public_decrypt'] = "1";
		}
		$info['sha1'] = "1";
		$info['apissl'] = "1";
		if (function_exists('base64_encode')) {
			$info['b64encode'] = true;
		}
		if (function_exists('base64_decode')) {
			$info['b64decode'] = true;
		}
		return $info;
	}
	
	public function servicesInfo(&$data) {
		$settings = $this->settings;
		$data['protect'] = $settings->getOption('bvptconf');
		$data['brand'] = $settings->getOption($this->bvinfo->brand_option);
		$data['badgeinfo'] = $settings->getOption($this->bvinfo->badgeinfo);
		$data[$this->bvinfo->services_option_name] = $this->bvinfo->config;
	}

	public function dbconf(&$info) {
		$db = $this->db;
		if (defined('DB_CHARSET'))
			$info['dbcharset'] = DB_CHARSET;
		$info['dbprefix'] = $db->dbprefix();
		$info['charset_collate'] = $db->getCharsetCollate();
		return $info;
	}

	public function cookieInfo() {
		$resp = array();
		if (defined('COOKIEPATH'))
			$resp['cookiepath'] = COOKIEPATH;
		if (defined('COOKIE_DOMAIN'))
			$resp['cookiedomain'] = COOKIE_DOMAIN;
		return array('cookieinfo' => $resp);
	}
	
	public function activate() {
		$resp = array();
		$this->siteinfo->basic($resp);
		$this->servicesInfo($resp);
		$this->dbconf($resp);
		$this->availableFunctions($resp);
		return array('actinfo' => $resp);
	}

	public function getHostInfo() {
		$host_info = $_SERVER;
		$host_info['PHP_SERVER_NAME'] = php_uname('\n');
		if (array_key_exists('IS_PRESSABLE', get_defined_constants())) {
			$host_info['IS_PRESSABLE'] = true;
		}

		if (array_key_exists('GRIDPANE', get_defined_constants())) {
			$host_info['IS_GRIDPANE'] = true;
		}

		if (defined('WPE_APIKEY')) {
			$host_info['WPE_APIKEY'] = WPE_APIKEY;
		}

		return array('host_info' => $host_info);
	}

	function refreshUpdatesInfo() {
		global $wp_current_filter;
		$wp_current_filter[] = 'load-update-core.php';

		if (function_exists('wp_clean_update_cache')) {
			wp_clean_update_cache();
		} else {
			$this->settings->deleteTransient('update_plugins');
			$this->settings->deleteTransient('update_themes');
			$this->settings->deleteTransient('update_core');
		}

		wp_update_plugins();
		wp_update_themes();

		array_pop($wp_current_filter);

		wp_update_plugins();
		wp_update_themes();

		wp_version_check();
		wp_version_check(array(), true);

		return true;
	}

	function getUsersHandler($args = array()) {
		$db = $this->db;
		$table = "{$db->dbprefix()}users";
		$count = $db->rowsCount($table);
		$result = array("count" => $count);

		$max_users = array_key_exists('max_users', $args) ? $args['max_users'] : 500;
		$roles = array_key_exists('roles', $args) ? $args['roles'] : array();

		$users = array();
		if (($count > $max_users) && !empty($roles)) {
			foreach ($roles as $role) {
				if ($max_users <= 0)
					break;
				$args['number'] = $max_users;
				$args['role'] = $role;
				$fetched = $this->getUsers($args['full'], $args);
				$max_users -= sizeof($fetched);
				$users = array_merge($users, $fetched);
			}
		} else {
			$args['number'] = $max_users;
			$users = $this->getUsers($args['full'], $args);
		}
		$result['users_info'] = $users;

		return $result;
	}

	function getTransient($name, $asarray = true) {
		$transient = $this->settings->getTransient($name);
		if ($transient && $asarray)
			$transient = $this->objectToArray($transient);
		return array("transient" => $transient);
	}

	function getPluginsHandler() {
		return array_merge($this->getPlugins(), $this->getTransient('update_plugins'));
	}

	function getThemesHandler() {
		return array_merge($this->getThemes(), $this->getTransient('update_themes'));
	}

	public function getSiteInfo($args) {
		$result = array();

		if (array_key_exists('refresh', $args))
			$result['refreshed'] = $this->refreshUpdatesInfo();

		if (array_key_exists('users', $args))
			$result['users'] = $this->getUsersHandler($args['users']);

		if (array_key_exists('plugins', $args))
			$result['plugins'] = $this->getPluginsHandler();

		if (array_key_exists('themes', $args))
			$result['themes'] = $this->getThemesHandler();

		if (array_key_exists('core', $args))
			$result['core'] = $this->getTransient('update_core');

		if (array_key_exists('sys', $args))
			$result['sys'] = $this->getSystemInfo();

		return $result;
	}

	public function process($request) {
		$db = $this->db;
		$params = $request->params;
		switch ($request->method) {
		case "activateinfo":
			$resp = $this->activate();
			break;
		case "ckeyinfo":
			$resp = $this->cookieInfo();
			break;
		case "gtpsts":
			$count = 5;
			if (array_key_exists('count', $params))
				$count = $params['count'];
			$resp = $this->getPosts($params['post_type'], $count);
			break;
		case "gtsts":
			$resp = $this->getStats();
			break;
		case "gtplgs":
			$resp = $this->getPlugins();
			break;
		case "gtthms":
			$resp = $this->getThemes();
			break;
		case "gtsym":
			$resp = array('sys' => $this->getSystemInfo());
			break;
		case "gtwp":
			$resp = $this->getWpInfo();
			break;
		case "gtallhdrs":
			$data = (function_exists('getallheaders')) ? getallheaders() : false;
			$resp = array("allhdrs" => $data);
			break;
		case "gtsvr":
			$resp = array("svr" => $_SERVER);
			break;
		case "getoption":
			$resp = array("option" => $this->settings->getOption($params['name']));
			break;
		case "gtusrs":
			$full = false;
			if (array_key_exists('full', $params))
				$full = true;
			$resp = array('users' => $this->getUsers($full, $params['args']));
			break;
		case "gttrnsnt":
			$resp = $this->getTransient($params['name'], array_key_exists('asarray', $params));
			break;
		case "gthost":
			$resp = $this->getHostInfo();
			break;	
		case "gtplinfo":
			$args = array(
				'slug' => wp_unslash($params['slug'])
			);
			$action = $params['action'];
			$args = (object) $args;
			$args = apply_filters('plugins_api_args', $args, $action);
			$data = apply_filters('plugins_api', false, $action, $args);
			$resp = array("plugins_info" => $data);
			break;
		case "gtsteinfo":
			$resp = $this->getSiteInfo($params);
			break;
		default:
			$resp = false;
		}
		return $resp;
	}
}
endif;