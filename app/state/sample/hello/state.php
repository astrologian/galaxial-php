<?php

/**
 * Sample controller
 */

class State_Sample_Hello
{

  /**
   * Main function
   * @param string $bar sample parameter
   */

  public function main($bar)
  {

    // Get message
    $message = Query::createFromFile('state/sample/hello/get')
      ->bind('bar', $bar)
      ->get();

    // Prepare content
    $content = Template::render("state/sample/hello/content", array('message' => 'Hello, ' . $message->hello));

    // Prepare sidebar
    $sidebar = Template::render("state/sample/sidebar", array());

    // Prepare main view
    $view = Template::render("common/2column", array('content' => $content, 'sidebar' => $sidebar));

    // Return rendered page
    return Template::render("common/index", array('title' => $bar, 'view' => $view));
  }
}
