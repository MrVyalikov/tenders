<?php

class Model_Tender extends Model {
     
    public $id;
    public $creation_date;
    public $name;
    public $code;
    public $year;

    public function fieldsTable(){
        return array(
            'id' => 'Id',
            'creation_date' => 'Creation date',
            'name' => 'Tender name',
            'code' => 'Tender code',
            'year' => 'Tender year',
        );
    }

    public function create($request)
    {
        $res = $this->validate($request);
        if ($res['success'] == 0)
            return $res;

        $res = $this->create_new($request);
        return $res;
    }

    public function get_data_for_edit($id)
    {
        $id = htmlspecialchars($id);

        $data = array();

        $data['result'] = $this->getRowById($id);
        $data['id'] = $id;

        return ($data);
    }

    public function edit_data($request)
    {
        $id = (int) $request->id;

        $name = $this->post_text_data($request->name);
        $code = $this->post_text_data($request->code);
        $year = (int)($request->year); 

        $ret = $this->validate($request);
        if($ret['success'] == 0) // validation error
        {
            return $ret;
        }


        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->year = $year;

        $query_res = $this->update();

        $ret = array('success'=>1, 'error_text'=>'');

        if(!$query_res)
        {
           $ret = array('success'=>0, 'error_text'=>'Ошибка изменения тендера');
        }

        return $ret;
    }

    private function create_new($request)
    {
        $name = $this->post_text_data($request->name);
        $code = $this->post_text_data($request->code);
        $year = (int)$this->post_text_data($request->year);  

        $this->name = $name;
        $this->code = $code;   
        $this->creation_date = date('Y-m-d H:i:s');
        $this->year = $year;

        $res = $this->save();

        if (!$res)
            return array('success'=>0, 'error_text'=>'Не удалось добавить запись в базу данных');

         return array('success'=>1, 'error_text'=>'');
    }

    public function delete($id)
    {
        $this->id = $id;
        $res = $this->deleteRow();
        return $res;
    }

    public function validate($request)
    {

        $name = $this->post_text_data($request->name);
        $code = $this->post_text_data($request->code);
        $year = (int)$this->post_text_data($request->year);

        if (!preg_match("/^[a-z]{1,}+[a-z]{0,24}$/iu", $name))
             return array('success'=>0, 'error_text'=>'Название должно быть от 1 до 24 символов, используйте латинские буквы.');
        else if (!preg_match("/^[a-z0-9]{1,}+[a-z0-9]{0,24}$/iu",  $code))
            return array('success'=>0, 'error_text'=>'Код должен быть от 1 до 24 символов, используйте латинские буквы и цифры.');
        else if (!preg_match("/^[0-9]{1,4}$/iu",  $year))
             return array('success'=>0, 'error_text'=>'Введите корректный год');

        return array('success'=>1, 'error_text'=>'');
    }
     
}

?>