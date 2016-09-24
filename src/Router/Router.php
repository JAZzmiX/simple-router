<?php

namespace src\Router;

use src\Actions\ActionInterface;

class Router
{
	private $routes;
	private $req;

	public function __construct()
	{
		$this->routes = ['GET', 'POST'];
	}

	public function get($url, $action, $actionGroup = '')
	{
		$this->req = $_GET;
		$this->register_route('GET', $actionGroup . $url, $action);
	}

	public function post($url, $action, $actionGroup = '')
	{
		$this->req = $_POST;
		$this->register_route('POST', $actionGroup . $url, $action);
	}

	public function run()
	{
		if ($this->isRouteNotExists()) {
			http_response_code(404);
			echo 'Not found';
		} else {
			$action = $this->routes[$_SERVER['REQUEST_METHOD']][$_SERVER['REQUEST_URI']];

			if($action instanceof ActionInterface) {
				echo $action->call($this->req);
			} else {
				echo $action($this->req);
			}
			
		}		
	}

	private function register_route($method, $url, $action)
	{
		if(empty($this->routes[$method])) {
			$this->routes[$method] = array();
		}

		$this->routes[$method][$url] = $action;
	}

	private function isRouteNotExists()
	{
		return is_null($this->routes[$_SERVER['REQUEST_METHOD']]) || is_null($this->routes[$_SERVER['REQUEST_METHOD']][$_SERVER['REQUEST_URI']]);
	}
}