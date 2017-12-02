<?php defined ('BASEPATH') || exit ('此檔案不允許讀取。');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2017, OACI
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

const CI_VERSION = '3.1.6';

include_once BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'Common' . '.php';

_r ('Benchmark');
_r ('Config');
_r ('Log');
_r ('Exceptions');
_r ('Charset');
_r ('Utf8');
_r ('URI');

_r ('Router');
_r ('Output');
_r ('Security');
_r ('Input');
_r ('Controller');
_r ('Model', null, false);

// 載入 Composer autoload
Config::get ('general', 'composer_autoload') && _r ('autoload', FCPATH . 'vendor' . DIRECTORY_SEPARATOR, false);

Benchmark::mark ('loading_time:_base_classes_end');

$class = Router::getClass ();
$method = Router::getMethod ();

if (!($class && $method !== '_' && file_exists (($path = APPPATH . 'controllers' . DIRECTORY_SEPARATOR . Router::getDirectory ()) . $class . '.php') && _r ($class, $path, false) && class_exists ($class, false) && method_exists ($class, $method) && is_callable (array ($class, $method)) && ($reflection = new ReflectionMethod ($class, $method)) && ($reflection->isPublic () && !$reflection->isConstructor ())))
	return Exceptions::show404 ();

$params = array_slice (URI::rsegments (), 2);

if (method_exists ($class, '_remap')) {
	$params = array ($method, $params);
	$method = Router::setMethod ('_remap');
}


/* ======================================================
 *  開始 */

Benchmark::mark ('controller_execution_time_( ' . $class . ' / ' . $method . ' )_start');
$CI = new $class();
call_user_func_array (array (&$CI, $method), $params);
Benchmark::mark ('controller_execution_time_( ' . $class . ' / ' . $method . ' )_end');

/*  結束
 * ====================================================== */

Output::display ();

// echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
// var_dump (Benchmark::elapsedTime ('total_execution_time_start', 'total_execution_time_end'), round (memory_get_usage () / 1024 / 1024, 2).'MB');
// exit ();
// echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
// var_dump (Router::getDirectory ());
// var_dump (Router::getClass ());
// var_dump (Router::getMethod ());
// var_dump ();
// exit ();
