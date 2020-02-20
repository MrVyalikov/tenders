<?php

class Controller {
	
	public $model;
	public $view;
	
	function __construct()
	{
		$this->view = new View();
	}

	function get_request()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		return $request;
	}
	
	function action_index()
	{
	}
}

?>