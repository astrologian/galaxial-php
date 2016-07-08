<?php

/**
 * Контроллер ошибки 404
 */

class State_404
{

  /**
   * Основная функция
   */

  public function main()
  {

    // Готовим вьюшку
    $view = Template::render("state/404/view", array());

    // Возвращаем готовую страницу
    return Template::render("common/index", array('title' => 'Страница не найдена', 'view' => $view));
  }
}
