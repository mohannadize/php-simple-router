<?php

require "init.php";

$router = new Router();

$router->get("/", function (Request $request) use ($db) {
  header("content-type: text/plain");
  $accounts = $db->query("SELECT * FROM `accounts`");
  foreach ($accounts as $account) {
    echo "$account[id]: $account[name] - $account[age]\n";
  }
});

$router->delete("/", function (Request $request) {
  global $db_filename;
  header("content-type: text/plain");
  unlink($db_filename);
  // echo ($db_filename);
});

$router->post("/", function (Request $request) use ($db) {
  $stmt_h = $db->prepare('insert into `accounts` (`name`, `age`) values (:name, :age)');
  $stmt_h->bindParam(':name', $name);
  $stmt_h->bindParam(':age', $age);

  
  $name = $request->BODY["name"];
  $age = $request->BODY["age"];
  // var_dump($name, $age);
  $stmt_h->execute();
});

$router->get("/error", function (Request $request) {
  throw new Exception("Server Error", 500);
});