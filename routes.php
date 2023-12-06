<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get(
    '/', function () {
      return redirect("./contacts");
    }
)->name("landing");
  
SimpleRouter::group(
    ["prefix" => "/contacts"], function () {
        SimpleRouter::get("/", [ContactsController::class, "getAll"]); 
        SimpleRouter::get("/new", [ContactsController::class, "newContactView"]); 
        SimpleRouter::post("/new", [ContactsController::class, "newContact"]);
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

    function newContactView() {
      return Template::view("new_contact.html");
    }

    function newContact()
    {
        $body = input();
        $sql = Database::prepare("INSERT INTO contacts (`name`, `phone`) VALUES (?, ?)");
        $sql->execute([$body->find("name")->value, $body->find("phone")->value]);
        return redirect("./");
    }

    function getContact(int $id)
    {
        $sql = Database::prepare('SELECT * FROM `contacts` WHERE `id` = ?');
        $sql->execute([$id]);
        return Template::view("contact_view.html", ["contact" => $sql->fetch()]);
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
