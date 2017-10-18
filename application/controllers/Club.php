<?php
class Club extends CI_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('club_model');
            $this->load->model('upload_model');
            $this->load->library('aws3');
            $config['upload_path'] = './uploads';
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config); 
        //     if ($this->session->userdata('userName') == "" && $this->session->userdata('adminName') == "") {
        //         show_404();
        // }
    }

		public function view($page = 'home',$dataArg = null)
        {
            if ($this->checkAdmin()) {
	        if ( ! file_exists(APPPATH.'views/club/'.$page.'.php'))
		        {
		            // Whoops, we don't have a page for that!
                //    $this->output->set_output(json_encode(['result' => 0 ]));
                    return json_encode(['result' => 0 ]);
		            show_404();
		        }

	        $data['title'] = ucfirst($page); // Capitalize the first letter
            $data['dbData'] = $dataArg;
            
	        $this->load->view('templates/header', $data);
	        $this->load->view('templates/sidebar', $data);
            $this->load->view('club/'.$page, $data);
	        $this->load->view('templates/footer', $data);
             } else {
            $this->output->set_output(json_encode(['result' => 0, 'message' => "You are not logged In!" ]));
             }
        }
        public function indexApi() {
            if (!$this->checkUser() && !$this->checkAdmin()) {
            $this->output->set_output(json_encode(['result' => 0, 'message' => "You are not logged In!" ]));
            } 
            else {
            $this->output->set_content_type('application_json');
            $allClubs = $this->club_model->get();
            $allUrls = [];
            foreach ($allClubs as $key => $thisClub) {
                $imageIds = preg_split('@ @', $thisClub["clubPictures"], NULL, PREG_SPLIT_NO_EMPTY);
                if (empty($imageIds) ) {
                    array_push($allUrls, "https://diversvisiblity.s3.us-east-2.amazonaws.com/club14500.png");
                } else{
                    $temp = "";
                    foreach ($imageIds as $key1 => $value) {
                    $temp = $temp . $this->upload_model->get(['uploadId' => $value])[0]["uploadUrl"] . ' ';
                }    
                array_push($allUrls, $temp);
                
          }
        }
            $clubs = $this->club_model->get();
            //$data = array_map(function ($a, $b) { return "$a','$b"; }, $clubs, $allUrls);
            foreach ($clubs as $key => $club) {
                $clubs[$key]["url"] = $allUrls[$key];
            }
            $this->output->set_output(json_encode(['result' => 1, 'data' => $clubs ]));
            }
        }

        public function checkUser()
        {
            if ($this->session->userdata('userName') == "")
            {     
                return false;
            } else {
                return true;
            }
        }
        
        public function checkAdmin()
        {
            if ($this->session->userdata('adminName') == "")
            {     
                return false;
            } else {
                return true;
            }
        }

        public function index() {
            $this->view("index",$this->club_model->get());
        }
        public function show($id) {
        $thisClub = $this->club_model->get(['clubId' => $id])[0];
            $clubPictures = explode(' ', $thisClub["clubPictures"]);
            $pictureUrls = [];
            foreach ($clubPictures as $key => $value) {
                $temp = $this->upload_model->get(['uploadId' => (int)$value]);
                if (count($temp) > 0) {
               array_push($pictureUrls ,$temp[0]);
           }
            }
            $this->view("show",["club" => $thisClub, "urls" => $pictureUrls] );
        }

        public function edit($id) {
          $thisClub = $this->club_model->get(['clubId' => $id])[0];
            $clubPictures = explode(' ', $thisClub["clubPictures"]);
            $pictureUrls = [];
            foreach ($clubPictures as $key => $value) {
                $temp = $this->upload_model->get(['uploadId' => (int)$value]);
                if (count($temp) > 0) {
               array_push($pictureUrls ,$temp[0]);
           }
            }
            $this->view("edit",["club" => $thisClub, "urls" => $pictureUrls] );
        }



        public function create() {

        	$this->output->set_content_type('application_json');
        	
        	$this->form_validation->set_rules('clubName','Club Name','required|min_length[4]|max_length[15]|is_unique[club.clubName] ');
        	$this->form_validation->set_rules('clubEmail','Club Email','required|valid_email|is_unique[club.clubEmail]');
            $this->form_validation->set_rules('clubDescription','Club Description','max_length[1000]');
            $this->form_validation->set_rules('clubPhoneNumber','Club Contact Number','max_length[30]');
            $this->form_validation->set_rules('clubEmail','Club Email','required|valid_email|is_unique[club.clubEmail]');


        	if (!$this->form_validation->run()){
        		$this->output->set_output(json_encode(['result' => 0, 'data' => validation_errors()]));
        		return false;
        	}
              $filesCount = count($_FILES['userFiles']['name']);
             
              $clubPictures = [];
            for($i = 0; $i < $filesCount; $i++){
                $_FILES['userFile']['name'] = $_FILES['userFiles']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['userFiles']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['userFiles']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['userFiles']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['userFiles']['size'][$i];

                $uploadPath = './uploads';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('userFile')){
                    $fileData = $this->upload->data();
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    $uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");
                 $url = $_FILES['userFile'];
                 $temp = explode('.', $url['name']);
                $url['name'] = $temp[0] . mt_rand(10,10000). '.' .end($temp);
                $image_data = $this->upload->data();
                $image_data['file_name'] = $this->aws3->sendFile('diversvisiblity',$url); 
                
                $res = $this->upload_model->insert([
                    'uploadUrl' => $image_data['file_name'],
                    'uploadType' =>'image'
                    ]);
                array_push($clubPictures,$res);
                }
            }

         

        	$clubName = $_POST['clubName'];
        	$clubEmail = $_POST['clubEmail'];
            $clubDescription = $_POST['clubDescription'];
            $clubPhoneNumber = $_POST['clubPhoneNumber'];
            $clubAddress = $_POST['clubAddress'];
            $clubLat = $_POST['clubLat'];
            $clubLng = $_POST['clubLng'];
            $clubPictures  = implode(" ",$clubPictures);
        	$result = $this->club_model->insert([
        		'clubName' => $clubName,
        		'clubEmail' => $clubEmail,
        		'clubDescription' => $clubDescription,
                'clubPhoneNumber' => $clubPhoneNumber,
                'clubAddress' => $clubAddress,
                'clubLat' => $clubLat,
                'clubLng' => $clubLng,
                'clubPictures' => $clubPictures
        		]);

                $this->output->set_output(json_encode(['result' => 1]));
                return true;
        		redirect('club/index', 'refresh');
        }

        public function remove($clubId = null) {
            if ($this->checkAdmin()) {
            $this->output->set_content_type('application_json');
            $result = $this->club_model->delete(ucfirst($clubId));
                redirect('club/index', 'refresh');
            }  else {
            $this->output->set_content_type('application_json');
            $this->output->set_output(json_encode(['result' => 0, 'message' => "You are not logged In!" ]));
            }
        }

        public function update($clubId = null) {
            if ($this->checkAdmin()){
            $thisClub = $this->club_model->get(['clubId'=>$clubId])[0];
            
            if($this->input->post('clubName') != $thisClub["clubName"]) {
                   $is_uniqueName =  '|is_unique[club.clubname]';
                } else {
                   $is_uniqueName =  '';
                }
            if($this->input->post('clubEmail') != $thisClub["clubEmail"]) {
                   $is_uniqueEmail =  '|is_unique[club.clubemail]';
                } else {
                   $is_uniqueEmail =  '';
                }

            $this->output->set_content_type('application_json');
            
            $this->form_validation->set_rules('clubName','Club Name','required|min_length[4]|max_length[15]'.$is_uniqueName);
            $this->form_validation->set_rules('clubEmail','Club Email','required|valid_email'.$is_uniqueEmail);
            $this->form_validation->set_rules('clubDescription','Club Description','max_length[1000]');
            $this->form_validation->set_rules('clubPhoneNumber','Club Contact Number','max_length[30]');
           


            if (!$this->form_validation->run()){
                $this->output->set_output(json_encode(['result' => 0, 'data' => validation_errors()]));
                return false;
            }

            $filesCount = count($_FILES['userFiles']['name']);
             
            $clubPictures = $this->club_model->get(['clubId' => $clubId])[0]["clubPictures"];
            $clubPictures = explode(' ', $clubPictures);
           

            for($i = 0; $i < $filesCount; $i++){
                $_FILES['userFile']['name'] = $_FILES['userFiles']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['userFiles']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['userFiles']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['userFiles']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['userFiles']['size'][$i];

                $uploadPath = './uploads';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('userFile')){
                    $fileData = $this->upload->data();
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    $uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");
                 $url = $_FILES['userFile'];
                 $temp = explode('.', $url['name']);
                $url['name'] = $temp[0] . mt_rand(10,10000). '.' .end($temp);
                $image_data = $this->upload->data();
                $image_data['file_name'] = $this->aws3->sendFile('diversvisiblity',$url); 
                
                $res = $this->upload_model->insert([
                    'uploadUrl' => $image_data['file_name'],
                    'uploadType' =>'image'
                    ]);
                array_push($clubPictures,(string)$res);
                }
            }
              // $this->output->set_output(json_encode(['result' => 0, 'data' =>$clubPictures ]));
              //   return false;
            $removePics = explode(',', $this->input->post('removePics'));
            $clubPictures = array_diff($clubPictures, $removePics);


            $clubName = $this->input->post('clubName');
            $clubEmail = $this->input->post('clubEmail');
            $clubDescription = $this->input->post('clubDescription');
            $clubPhoneNumber = $this->input->post('clubPhoneNumber');
            $clubAddress = $this->input->post('clubAddress');
            $clubLat = $this->input->post('clubLat');
            $clubLng = $this->input->post('clubLng');
            $clubPictures  = implode(" ",$clubPictures);
            $result = $this->club_model->update($clubId,[
                'clubName' => $clubName,
                'clubEmail' => $clubEmail,
                'clubDescription' => $clubDescription,
                'clubPhoneNumber' => $clubPhoneNumber,
                'clubAddress' => $clubAddress,
                'clubLat' => $clubLat,
                'clubLng' => $clubLng,
                'clubPictures' => $clubPictures
                ]);
            $this->output->set_output(json_encode(['result' => 1]));
                return true;
                redirect('club/show/'.$clubId, 'refresh');
        }  else {
            $this->output->set_content_type('application_json');
            $this->output->set_output(json_encode(['result' => 0, 'message' => "You are not logged In!" ]));
            }
        }
}