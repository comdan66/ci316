<?php defined ('OACI') || exit ('此檔案不允許讀取。');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2017, OACI
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

if (!function_exists ('use_model')) {
  function use_model () {
    static $used;

    if (!empty ($used))
      return true;

    if (!$database = config ('database'))
      return false;

    Load::file (BASEPATH . 'model' . DIRECTORY_SEPARATOR . 'ActiveRecord.php', true);

    ActiveRecord\Config::initialize (function ($cfg) use ($database) {
      $cfg->set_model_directory (APPPATH . 'model')
          ->set_connections (array_combine (array_keys ($database['groups']), array_map (function ($group) { return $group['dbdriver'] . '://' . $group['username'] . ':' . $group['password'] . '@' . $group['hostname'] . '/' . $group['database'] . '?charset=' . $group['char_set']; }, $database['groups'])), $database['active_group']);

      ($cacheConfig = config ('model', 'cache')) && isset ($cacheConfig['enable'], $cacheConfig['driver']) && $cacheConfig['enable'] && Load::sysLib ('Cache.php') && $cfg->set_cache ($cacheConfig['driver'], isset ($cacheConfig['prefix']) ? $cacheConfig['prefix'] : null, isset ($cacheConfig['expire']) ? $cacheConfig['expire'] : null);

      class_exists ('Log') && $cfg->setLog ('Log') && Log::queryLine ();
    });

    class_alias ('ActiveRecord\Connection', 'ModelConnection');
    
    class Model extends ActiveRecord\Model {}



    spl_autoload_register (function ($class) {
      if (class_exists ($class, false))
        return;

      if (preg_match ("/Uploader$/", $class))
        Load::sysLib ('Uploader' . EXT) || gg ('找不到 Model 相關工具：' . $class);

      if ($class === 'WhereBuilder')
        Load::sysLib ('WhereBuilder' . EXT) || gg ('找不到 Model 相關工具：' . $class);
    });

    // Load::sysLib ('Uploader.php');
    // Load::sysLib ('WhereBuilder.php');
   
    return $used = true;
  }
}

config ('model', 'auto_load') && use_model ();