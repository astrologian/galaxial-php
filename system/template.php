<?php

/**
* Рендерер шаблона
*/

class Template {

  /**
  * Рендерит шаблон в строку
  * @param string $path_to_file путь к шаблону. Путь должен быть <i>path/to/file</i>, а сам шаблон должен находиться в файле <i>/app/<b>path/to/file</b>.html</i>
  * @param array $keys ассоциативный массив. Его элементы будут доступны в шаблоне в виде переменных
  * @return string буфер
  */

  public static function render($path_to_file, $keys) {
    extract($keys, EXTR_SKIP);       // Элементы массива становятся переменными
    ob_start();                      // Буферизуем вывод
    require 'app/' . $path_to_file . '.html'; // Подключаем файл с HTML
    return ob_get_clean();           // Закрываем и возвращаем буфер в виде строки
  }

}
