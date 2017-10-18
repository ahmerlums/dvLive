<?php
class Amazons3 extends CI_Controller {
	public function __construct() {
			parent::__construct();
			$this->load->library('aws3');
	}

	public function test_addbucket($name) {
		$return = $this->aws3->addbucket($name);
		var_dump($return);
	}

public function upload()
	{			
		$config['upload_path'] = './uploads';
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);
		$this->upload->initialize($config); 
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('amazons3/view_aws', $error);
		}
		else
		{
			$image_data = $this->upload->data();
			$image_data['file_name'] = $this->aws3->sendFile('diversvisiblity',$_FILES['userfile']);	
			$data = array('upload_data' => $image_data['file_name']);
			$this->load->view('amazons3/view_aws', $data);
		}
	}


}
