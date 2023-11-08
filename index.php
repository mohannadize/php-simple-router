<?php

require "config.php";

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['prefix' => $BASE_PATH], function () {
    require_once "routes.php";
});

SimpleRouter::start();
