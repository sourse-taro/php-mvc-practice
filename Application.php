<?php

namespace thecodeholic\phpmvc;

use thecodeholic\phpmvc\Router;
use thecodeholic\phpmvc\Request;
use thecodeholic\phpmvc\Response;
use thecodeholic\phpmvc\Session;
use thecodeholic\phpmvc\db\Database;
use thecodeholic\phpmvc\db\DbModel;
use thecodeholic\phpmvc\Controller;
use thecodeholic\phpmvc\View;

class Application {

    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $userClass;
    public $router;
    public $request;
    public $response;
    public $session;
    public $db;
    public ?UserModel $user;
    public $view;

    public static Application $app;
    public ?Controller $controller = null;
    public function __construct($rootPath, array $config)
    {
      $this->userClass = $config['userClass'];

      self::$ROOT_DIR = $rootPath;
      self::$app = $this;
      $this->request = new Request();
      $this->response = new Response();
      $this->session = new Session();
      $this->router =  new Router($this->request, $this->response);
      $this->view = new View();

      $this->db = new Database($config['db']);

      $primaryValue = $this->session->get('user');
      if ($primaryValue) {
        $primaryKey = $this->userClass::primaryKey();
        $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);

      } else {
        $this->user = null;
      }
    }

    public static function isGuest()
    {
      return !self::$app->user;
    }

    public function run() 
    {
      try {
        echo $this->router->resolve();
      } catch (\Exception $e) {
        $this->response->setStatusCode($e->getCode());
        echo $this->view->renderView('_error', [
          'exception' => $e
        ]);
      }
    }

    public function getController()
    {
      return $this->controller;
    }

    public function setController($controller)
    {
      $this->controller = $controller;
    }

    public function login($user)
    {
      $this->user = $user;
      $primaryKey = $user->primaryKey();
      $primaryValue = $user->{$primaryKey};
      $this->session->set('user', $primaryValue);
      return true;
    }

    public function logout()
    {
      $this->user = null;
      $this->session->remove('user');
    }

}

