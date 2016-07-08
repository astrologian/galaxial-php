<?php

/**
 * Роутер. ЧПУ -> контроллер
 */
class Router {

  /**
   * Список загруженных путей к контроллерам
   * @var array
   */
  private static $routes = array();

  /**
   * Префикс. Указывается, если сайт находится не в корне. Это дополнительный участок пути
   * @var string
   */
  public static $prefix = '';

  /**
   * Загружает пути к контроллерам
   * @param string $config_file путь к JSON файлу с путями
   */
  public static function load($config_file)
  {
    $cfg = json_decode(file_get_contents($config_file)); // JSON -> PHP
    foreach ($cfg as $c) {
      self::add($c[0], $c[1]);
    }
  }

  /**
   * Добавляет путь в свой список
   * @param string $uri_template шаблон URL
   * @param string $state контроллер
   */
  public static function add($uri_template, $state) {

    // Компиляция шаблона в регулярное выражение
    // Производим замену некоторых символов. Перед к шаблону добавляем префикс
    $uri_regex = str_replace(
      array('/', '.', '[', ']'),
      array('\/', '\.', '(?:', ')?'),
      self::$prefix . $uri_template
    );

    // Ищем описания параметров в шаблоне
    preg_match_all('/(?:\{(\w+)\:(\w+)\})/', $uri_regex, $matches);

    // Извлекаем имена параметров
    $names = $matches[1];

    // Заменяем параметры в шаблоне соответствующими регулярками
    $uri_regex = preg_replace(
      array('/(?:\{\w+\:string\})/', '/(?:\{\w+\:integer\})/'),
      array('(\w+(?:-\w+)*)', '(\d+)'),
      $uri_regex
    );
    $uri_regex = "/^{$uri_regex}$/"; // Конечный вариант

    self::$routes[] = (object)array(
      'uri'       => $uri_regex, // Готовая регулярка
      'state'     => $state, // Имя контроллера
      'names'     => $names, // Имена параметров. Очередность соответствует шаблону
      'arguments' => array()); // Фактические значения параметров. Пока пусто
  }

  /**
   * Запускает контроллер
   * @param string $uri часть URL без GET-параметров и домена
   */
   public static function dispatch($uri) {

     // Сначала нужно найти подходящий путь
     if (false !== $route = self::find($uri)) { // Нашли?

       // Смотрим, указан только класс или вместе с вызываемым методом
       $st = explode('.', $route->state);
       $class = $st[0];
       if     (2 == count($st)) { $func = $st[1]; }
       elseif (1 == count($st)) { $func = 'main'; }
       else throw new Exception("Invalid route spec", 1);

       // Создаем экземпляр клнтроллера и вызываем указанный метод с передачей параметров
       echo call_user_func_array(array(new $route->state(), $func), $route->arguments);

     } else { // Не нашли :(
       echo call_user_func_array(array(new State_404(), 'main'), array());
     }
   }

  /**
   * Ищет подходящий к URL контроллер
   * @param string $uri часть URL без GET-параметров и домена
   */
   private static function find($uri) {

     // Пробегаем список
     foreach (self::$routes as $route) {

       // по пути выполняя проверки на соответствие адреса регуляркам
       if (1 === preg_match($route->uri, $uri, $matches)) { // Соответствует? В $matches записываются значения, извлеченные регуляркой

         // Собираем ассоциативный массив. Ключами выступают сохраненные ранее имена, значениями - значения, извлеченные регуляркой из адреса
         $route->arguments = array_combine($route->names, array_slice($matches, 1)); // Из массива со значениями нужно убрать первый элемент, потому что он содержит переданный URI целиком
         return $route;
       }
     }
     return false; // Если ничего нет, говорим, что нет
   }

}
