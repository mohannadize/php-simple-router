<?php

require "config.php";

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['prefix' => ROOT_PATH], function () {
    require_once "routes.php";
});

SimpleRouter::start();
