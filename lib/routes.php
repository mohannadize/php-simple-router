<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get(
    '/', function () {
      return Template::view("index.html", [
        "title" => "App title",
      ]);
    }
);

SimpleRouter::get(
    '/user/{id}', function ($userId) {
        return 'User with id: ' . $userId;
    }
);
