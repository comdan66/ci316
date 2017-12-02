<?php defined ('BASEPATH') || exit ('此檔案不允許讀取。');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2017, OACI
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

class URI {
  private static $uriString;
  private static $segments;
  private static $rsegments;

  public static function init () {
    URI::$uriString = '';
    URI::$segments = URI::$rsegments = array ();

    if (request_is_cli () || Config::get ('general', 'enable_query_strings') !== true) {
      $uri = request_is_cli () ? self::parseArgv () : self::parseRequestUri ();
      self::setUriString ($uri);
    }
  }

  private static function setUriString ($str) {
    self::$uriString = trim (remove_invisible_characters ($str, false), '/');

    if (self::$uriString !== '') {
      if (($suffix = (string)Config::get ('general', 'url_suffix')) !== '')
        if (substr (self::$uriString, -($slen = strlen ($suffix))) === $suffix)
          self::$uriString = substr (self::$uriString, 0, -$slen);

      self::$segments[0] = null;
      
      foreach (explode ('/', trim (self::$uriString, '/')) as $val) {
        $val = trim ($val);

        self::filterUri ($val);

        if ($val !== '')
          array_push (self::$segments, $val);
      }

      unset (self::$segments[0]);
    }
  }

  private static function parseRequestUri () {
    if (!isset ($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']))
      return '';

    $uri = parse_url ('http://dummy' . $_SERVER['REQUEST_URI']);
    $query = isset ($uri['query']) ? $uri['query'] : '';
    $uri = isset ($uri['path']) ? $uri['path'] : '';

    if (isset ($_SERVER['SCRIPT_NAME'][0]))
      $uri = strpos ($uri, $_SERVER['SCRIPT_NAME']) === 0 ? (string) substr ($uri, strlen ($_SERVER['SCRIPT_NAME'])) : (strpos ($uri, dirname ($_SERVER['SCRIPT_NAME'])) === 0 ? (string) substr ($uri, strlen (dirname ($_SERVER['SCRIPT_NAME']))) : $uri);

    if (trim ($uri, '/') === '' && strncmp ($query, '/', 1) === 0) {
      $query = explode ('?', $query, 2);
      $uri = $query[0];
      $_SERVER['QUERY_STRING'] = isset ($query[1]) ? $query[1] : '';
    } else {
      $_SERVER['QUERY_STRING'] = $query;
    }

    parse_str ($_SERVER['QUERY_STRING'], $_GET);

    if ($uri === '/' OR $uri === '')
      return '/';

    return self::removeRelativeDirectory ($uri);
  }
  private static function parseArgv () {
    return ($args = array_slice ($_SERVER['argv'], 1)) ? implode ('/', $args) : '';
  }
  private static function removeRelativeDirectory ($uri) {
    $uris = array ();
    $tok = strtok ($uri, '/');

    while ($tok !== false) {
      if ((!empty ($tok) || $tok === '0') && $tok !== '..')
        array_push ($uris, $tok);
      $tok = strtok ('/');
    }

    return implode ('/', $uris);
  }
  public static function filterUri (&$str) {
    $c = Config::get ('general', 'permitted_uri_chars');

    if ($str && $c && !preg_match ('/^[' . $c . ']+$/i' . (UTF8_ENABLED ? 'u' : ''), $str))
      class_exists ('Exceptions') && Exceptions::showError ('網址有不合法的字元！', 400);
  }
  public static function uriString () {
    return self::$uriString;
  }
  public static function segments () {
    return self::$segments;
  }
  public static function rsegments () {
    return self::$rsegments;
  }
  public static function setRsegments ($rsegments) {
    return self::$rsegments = $rsegments;
  }
}
