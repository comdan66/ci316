<?php defined ('BASEPATH') || exit ('此檔案不允許讀取。');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2017, OACI
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

class Charset {

  public static function init () {
    ini_set ('default_charset', $charset = Config::get ('general', 'charset'));

    if (extension_loaded ('mbstring')) {
      define ('MB_ENABLED', true);
      @ini_set ('mbstring.internal_encoding', $charset);
      mb_substitute_character ('none');
    } else {
      define ('MB_ENABLED', false);
    }

    if (extension_loaded ('iconv')) {
      define ('ICONV_ENABLED', true);
      @ini_set ('iconv.internal_encoding', $charset);
    } else {
      define ('ICONV_ENABLED', false);
    }

    is_php ('5.6') && ini_set ('php.internal_encoding', $charset);

    foreach (array ('mbstring', 'hash', 'password', 'standard') as $name)
      _r ($name, BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'compat' . DIRECTORY_SEPARATOR, false);

  }
}