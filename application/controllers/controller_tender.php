<?php

require_once("application/models/model_tender.php");

class Controller_Tender extends Controller
{
	function __construct()
	{
		$this->model = new Model_Tender(array('order' => 'name'));
	}

	public function action_index()
	{
		$title = "Тендеры";
		include_once "application/views/view_tender.php";
	}
		
	public function action_get_list()
	{
		$data = array();
		$this->model->query(array("order" => 'id'));
		$data['result'] = $this->model->getAllRows();
		echo json_encode($data);
	}
		
	public function action_edit()
	{
		$request = $this->get_request();

		$data = $this->model->edit_data($request);

		echo json_encode($data);
	}

	public function action_remove()
	{
		$request = $this->get_request();

		$data = $this->model->delete($request->id);

		$res = array('success'=>1, 'error_text'=>'');

		if(!$data)
		{
			$res = array('success'=>0, 'error_text'=>$data);
		}

		echo json_encode($res);
	}

	public function action_create()
	{
		$request = $this->get_request();

		$data = $this->model->create($request);

		echo json_encode($data);
	}

	public function action_get_edit_data()
	{
		$request = $this->get_request();
		$data = $this->model->get_data_for_edit($request->id);

		echo json_encode($data);
	}
}

?>