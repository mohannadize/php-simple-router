<?php

require "config.php";

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['prefix' => $ROOT_PATH], function () {
    
  require "routes.php";
    
});

SimpleRouter::start();
