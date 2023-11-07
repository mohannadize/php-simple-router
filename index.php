<?php

require "./config.php";

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['prefix' => $ROOT_PATH], function () {
    
  require "./lib/routes.php";
    
});

SimpleRouter::start();
