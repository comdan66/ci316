<?php defined ('OACI') || exit ('此檔案不允許讀取。');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2017, OACI
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

class Controller {
  public function __construct () {
  }
}

spl_autoload_register (function ($class) {
  if (!class_exists ($class, false) && preg_match ("/Controller$/", $class) && !Load::file (APPPATH . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . $class . EXT))
    gg ('找不到 Controller：' . $class);
});

