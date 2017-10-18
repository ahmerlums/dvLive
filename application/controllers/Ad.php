<?php
class Ad extends CI_Controller {

		public function __construct() {
			parent::__construct();
            $this->load->model('upload_model');
             $this->load->model('ad_model');
            $this->load->library('aws3');
            $config['upload_path'] = './uploads';
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config); 
       
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

          public function checkUser()
        {
            if ($this->session->userdata('userName') == "")
            {     
                return false;
            } else {
                return true;
            }
        }
        

       public function getAd($lat = null, $lng = null) {
       	if (!$this->checkUser() && !$this->checkAdmin()) {
            $this->output->set_output(json_encode(['result' => 0, 'message' => "You are not logged In!" ]));
        }
        else 
        {
            $ads = $this->ad_model->get();
            $smallestDistance = "undefined";
            $thisAd = [];
            $Ads = [];
            foreach ($ads as $key => $ad) {
                $temp = $this->distance(floatval($lat),floatval($lng),floatval($ad["AdLat"]),floatval($ad["AdLng"]),'K');
                if ($temp <= 1000) {
                    array_push($Ads,$ad);
                }
                // if ($smallestDistance = "undefined" || $smallestDistance > $temp){ 
                //     $smallestDistance = $temp;
                //     $thisAd = $ad;
                //  }
            }
         //   print_r($Ads);  
            $thisAd = $Ads[array_rand($Ads)];
         //   print_r($thisAd);
            // $this->output->set_content_type('application_json');
            // $ad = $this->ad_model->getRandomAd()[0];
            $url = $this->upload_model->get(['uploadId' => (int)($thisAd['AdPicture']) ] ) [0]["uploadUrl"];
          //  print_r($url);
            $this->output->set_output(json_encode(['result' => 1, 'data' => ['uploadUrl' => $url,'adUrl' => $thisAd['AdLink']]] ));
        } 

       }
      function distance($lat1, $lon1, $lat2, $lon2, $unit) {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
          return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
      } else {
          return $miles;
      }
    }
    public function view($page = 'home',$dataArg = null)
        {
            if ($this->checkAdmin()) {
	        if ( ! file_exists(APPPATH.'views/ad/'.$page.'.php'))
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
            $this->load->view('ad/'.$page, $data);
	        $this->load->view('templates/footer', $data);
             } else {
            $this->output->set_output(json_encode(['result' => 0, 'message' => "You are not logged In!" ]));
             }
        }

           public function create() {

        	$this->output->set_content_type('application_json');
        	
        	// $this->form_validation->set_rules('AdName','ad Name','required|min_length[4]|max_length[15]|is_unique[ad.adName] ');
        	// $this->form_validation->set_rules('A','ad Email','required|valid_email|is_unique[ad.adEmail]');
         //    $this->form_validation->set_rules('adDescription','ad Description','max_length[1000]');
         //    $this->form_validation->set_rules('adPhoneNumber','ad Contact Number','max_length[30]');
         //    $this->form_validation->set_rules('adEmail','ad Email','required|valid_email|is_unique[ad.adEmail]');


        	// if (!$this->form_validation->run()){
        	// 	$this->output->set_output(json_encode(['result' => 0, 'data' => validation_errors()]));
        	// 	return false;
        	// }
              $filesCount = count($_FILES['userFiles']['name']);
             
              $adPictures = [];
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
                array_push($adPictures,$res);
                }
            }

         

        	$adName = $_POST['adName'];
        	$adStatus = $_POST['adStatus'];
            $adStartTime = $_POST['startDate'];
            $adEndTime = $_POST['endDate'];
            $adLink = $_POST['adLink'];
            $adLat = $_POST['adLat'];
            $adLng = $_POST['adLng'];
            $adPictures  = implode(" ",$adPictures);
        	$result = $this->ad_model->insert([
        		'adName' => $adName,
        		'adStatus' => $adStatus,
        		'adStartTime' => $adStartTime,
                'adEndTime' => $adEndTime,
                'adLink' => $adLink,
                'adPicture' => $adPictures,
                'adLat' => $adLat,
                'adLng' => $adLng
        		]);

                $this->output->set_output(json_encode(['result' => 1]));
                return true;
        		redirect('ad/index', 'refresh');
        }


}