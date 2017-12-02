<?php defined ('BASEPATH') || exit ('此檔案不允許讀取。');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2017, OACI
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

if (!function_exists ('_r')) {
	function _r ($class, $path = null, $init = true) {
		$path === null && $path = BASEPATH . 'core' . DIRECTORY_SEPARATOR;

		if (!file_exists ($path = $path . $class . '.php'))
			exit ('初始化失敗。');

		include_once ($path);

		if ($init && class_exists ($class) && is_callable (array ($class, 'init')))
			$class::init ();

		return true;
	}
}

if (!function_exists ('is_php')) {
	function is_php ($version) {
		static $_isPHP;
		$version = (string) $version;
		return !isset ($_isPHP[$version]) ? $_isPHP[$version] = version_compare (PHP_VERSION, $version, '>=') : $_isPHP[$version];
	}
}

if (!function_exists ('remove_invisible_characters')) {
	function remove_invisible_characters ($str, $urlEncoded = true) {
		$n = array ();

		if ($urlEncoded)
			array_push ($n, '/%0[0-8bcef]/i', '/%1[0-9a-f]/i', '/%7f/i');

		array_push ($n, '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S');

		do {
			$str = preg_replace ($n, '', $str, -1, $count);
		} while ($count);

		return $str;
	}
}

if (!function_exists ('html_escape')) {
	function html_escape ($var, $doubleEncode = true) {
		if (empty ($var))
			return $var;

		if (is_array ($var)) {
			foreach (array_keys ($var) as $key)
				$var[$key] = html_escape ($var[$key], $doubleEncode);

			return $var;
		}

		return htmlspecialchars ($var, ENT_QUOTES, Config::get ('general', 'charset'), $doubleEncode);
	}
}

if (!function_exists ('stringify_attributes')) {
	function stringify_attributes ($attrs, $js = false) {
		$atts = '';

		if (!$attrs)
			return $atts;
		
		if (is_string ($attrs))
			return ' ' . $attrs;
		
		if (!is_array ($attrs))
			return $atts;

		foreach ($attrs as $key => $val)
			$atts .= $js ? $key . '=' . $val . ',' : ' ' . $key . '="' . $val . '"';

		return rtrim ($atts, ',');
	}
}

if (!function_exists ('request_is_cli')) {
	function request_is_cli () {
		return (PHP_SAPI === 'cli') || defined ('STDIN');
	}
}

if (!function_exists ('request_is_https')) {
	function request_is_https () {
		return (!empty ($_SERVER['HTTPS']) && strtolower ($_SERVER['HTTPS']) !== 'off')
				|| (isset ($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower ($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
				|| (!empty ($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower ($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off');
	}
}

if (!function_exists ('request_is_method')) {
	function request_is_method () {
		return strtolower (request_is_cli () ? 'cli' : (isset ($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : (isset ($_POST['_method']) ? $_POST['_method'] : 'get')));
	}
}

if (!function_exists ('request_is_ajax')) {
	function request_is_ajax () {
    return !empty ($_SERVER['HTTP_X_REQUESTED_WITH'])
         && strtolower ($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  }
}