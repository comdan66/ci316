<?php defined ('BASEPATH') || exit ('此檔案不允許讀取。');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2017, OACI
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

class Config {
  private static $files = array ();

  public static function init () {
    Config::get ('defines');
  }
  
  public static function get () {
    if (!(($args = func_get_args ()) && ($fileName =  array_shift ($args))))
      exit ('找不到該 Config 檔案：' . $fileName);

    if (!isset (self::$files[$fileName]))
      self::$files[$fileName] = file_exists ($path = APPPATH . 'config' . DIRECTORY_SEPARATOR . ENVIRONMENT . DIRECTORY_SEPARATOR . $fileName . '.php') || file_exists ($path = APPPATH . 'config' . DIRECTORY_SEPARATOR . $fileName . '.php') ? include_once ($path) : null;

    if (self::$files[$fileName] === null)
      exit ('找不到該 Config 檔案：' . $fileName);

    $t = self::$files[$fileName];

    foreach ($args as $arg)
      if (!$t = isset ($t[$arg]) ? $t[$arg] : null)
        break;

    return $t;
  }
}
