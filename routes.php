<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get(
    '/', function () {
        return Template::view(
            "index.html", [
            "url" => url("ContactsController@getAll")
            ]
        );
    }
)->name("landing");
  
SimpleRouter::group(
    ["prefix" => "/contacts"], function () {
        SimpleRouter::get("/", [ContactsController::class, "getAll"]); 
        SimpleRouter::post("/", [ContactsController::class, "newContact"]);
        SimpleRouter::group(
            ["where" => ["id" => "[0-9]+"]], function () {
                SimpleRouter::get("/{id}", [ContactsController::class, "getContact"]);
                SimpleRouter::put("/{id}", [ContactsController::class, "updateContact"]);
                SimpleRouter::delete("/{id}", [ContactsController::class, "deleteContact"]);
            }
        );
    }
);


class ContactsController
{
    function __construct() 
    {
        Database::query(
            "CREATE TABLE IF NOT EXISTS `contacts` (
        `id` integer PRIMARY KEY AUTOINCREMENT,
        `name` text not null UNIQUE,
        `phone` text not null
      )"
        );

        return;
    }

    function getAll()
    {
        $contacts = Database::query('SELECT * FROM `contacts`');
        return Template::view("contacts.html", ["contacts" => $contacts->fetchAll()]);
    }

    function newContact()
    {
        return "new contact";
    }

    function getContact(int $id)
    {
        return "get contact $id";
    }

    function updateContact(int $id)
    {
        return "updateContact $id";
    }

    function deleteContact(int $id)
    {
        return "deleteContact $id";
    }
}
