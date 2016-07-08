<?php

/**
 * Из имени класса формирует путь к файлу, где описан этот класс
 * @param array $parts Компоненты имени класса
 * @return string Часть пути к файлу
 */
function _make_path($parts)
{
  $path = '';
  foreach ($parts as $part) {
    $path .= '/' . lcfirst($part); // MyClass -> /myClass
  }
  return $path;
}

/**
 * Автозагрузка классов
 * @param string $class Имя класса, который мы хотим использовать
 */
function _load_class($class)
{
  $include_path = '';
  $parts = explode('_', $class); // Разделяем имя класса на компоненты. Эти компоненты будут использоваться для формирования пути к файлу с классом
  $search_path = array('app/classes', 'app', 'modules', 'system'); // Указываем, в каких каталогах искать файл с классом
  $path = _make_path($parts); // Формируем путь к файлу

  foreach ($search_path as $p) { // Ищем

    /*
      Формируем путь. Если первый компонент указывает на контроллер, то имя файла должно быть state.php
    */
    $include_path = $p . $path . ('State' === $parts[0] ? '/state' : '') . '.php';

    /*
      Проверяем, существует ли такой файл
    */
    if (file_exists($include_path)) {
      require $include_path; // Если существует, то подключаем его
      return;
    }
  }

  /*
    Не смогли найти класс? Передаем дальше.
    Например, PHPUnit использует свой автозагрузчик,
    поэтому мы должны сказать ему, что не нашли, он будет искать у себя
  */
  return false;
}

spl_autoload_register('_load_class'); // Регистрация функции автозагрузки классов
