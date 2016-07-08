<?php

class Db_Db
{
  private static $db;
  public static function res() {
    return self::$db;
  }
  public static function connect($profile_name = 'deploy')
  {
    $cfg = json_decode(file_get_contents('app/db.json')); // Читаем конфиг
    $profile = $cfg->{$profile_name};
    self::$db = mysqli_connect($profile->server, $profile->user, $profile->password, $profile->db);
    mysqli_query(self::$db, 'set names utf8 collate utf8_unicode_ci');
  }
}
