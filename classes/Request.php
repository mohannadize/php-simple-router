<?php

class Request
{
  public $LOCATION;
  public $REQUEST_PATH;
  public $METHOD;
  public $QUERY;
  public $BODY;
  public $FILES;
  public $COOKIES;


  public function __construct()
  {
    $this->LOCATION = str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);
    $this->REQUEST_PATH = str_replace($this->LOCATION, "/", $_SERVER["REDIRECT_URL"]);
    $this->METHOD = $_SERVER['REQUEST_METHOD'];
    $this->QUERY = $_REQUEST;
    switch ($this->METHOD) {
      case 'POST':
        $this->BODY = $_POST;
        $this->FILES = $_FILES;
        break;

      default:
        $this->BODY = [];
        parse_str(file_get_contents("php://input"), $this->BODY);
        break;
    }
    $this->COOKIES = $_COOKIE;
  }
}