<?php
class User extends CI_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('user_model');

		}

		public function view($page = 'home', $dataArg = null)
        {
            if ($this->session->userdata('userName') == "" && $page != 'login') {
                show_404();
            }
            else {
	        if ( ! file_exists(APPPATH.'views/user/'.$page.'.php'))
		        {
		            // Whoops, we don't have a page for that!
		            show_404();
		        }

	        $data['title'] = ucfirst($page); // Capitalize the first letter
	        
           
            $data["dbData"] = $dataArg;

	        $this->load->view('templates/header', $data);
	        $this->load->view('templates/sidebar', $data);
	        $this->load->view('user/'.$page, $data);
	        $this->load->view('templates/footer', $data);
            }
        }

        public function isLoggedIn() {
            $this->output->set_content_type('application_json');
            if ($this->session->userdata('userName') == "") {
                 $this->output->set_output(json_encode(['result' => 0]));
            } else {
                $this->output->set_output(json_encode(['result' => 1]));
            }
        }
        public function index() {
            $this->view("index",$this->user_model->get());
        }

        public function create() {
        	$this->output->set_content_type('application_json');
        	
        	$this->form_validation->set_rules('userName','User Name','required|min_length[4]|max_length[15]|is_unique[user.userName] ');
        	$this->form_validation->set_rules('userEmail','Email','required|valid_email|is_unique[user.userEmail]');
        	$this->form_validation->set_rules('userPassword','Password','required|min_length[4]|max_length[15]');
        	$this->form_validation->set_rules('confirmPassword','Confirm Password','required|matches[userPassword]');
            $this->form_validation->set_rules('userPhone','Phone/Mobile Number','required');
            $this->form_validation->set_rules('userAddress','Address','required');
            $this->form_validation->set_rules('userFullName','Name','required');

        	if (!$this->form_validation->run()){
        		$this->output->set_output(json_encode(['result' => 0, 'data' => validation_errors()]));
        		return false;
        	}


        	$userName = $this->input->post('userName');
        	$userEmail = $this->input->post('userEmail');
            $userFullName = $this->input->post('userFullName');
            $userAddress = $this->input->post('userAddress');
            $userPhone = $this->input->post('userPhone');
        	$userPassword = hash('sha256',$this->input->post('userPassword'). SALT);
        	$passwordConfirmation = $this->input->post('confirmPassword');

            // if (!($userGender == "Male" || $userGender == "Female" || $userGender == "male" || $userGender == "female" )) {
            //     $this->output->set_output(json_encode(['result' => 0, 'data' => "Invalid Gender"]));
            //     return false;
            // }
        	$result = $this->user_model->insert([
        		'userName' => $userName,
        		'userEmail' => $userEmail,
                'userFullName' => $userFullName,
                'userAddress' => $userAddress,
                'userPhone' => $userPhone,
        		'userPassword' => $userPassword
        		]);
                $this->output->set_output(json_encode(['result' => 1]));
                 return true;
        		redirect('user/view/login', 'refresh');
        }


        public function logout() {
        	$this->session->sess_destroy();
        	$this->session->unset_userdata(array('userId' => '','userName'=> ''));
        	$user_id = $this->session->userdata('userId');
       		redirect('/user/view/login');
        }

        public function signin($data = null) {
        	$userName = $this->input->post('userName');
        	$userPassword = hash('sha256',$this->input->post('userPassword'). SALT);
              if(filter_var($userName, FILTER_VALIDATE_EMAIL)) {
                  $authFormat = 'userEmail';
                }
                else {
                    $authFormat = 'userName';
                }

        	$result = $this->user_model->get([
        		$authFormat => $userName,
        		'userPassword' => $userPassword
        		]);


        	if ($result) {
        		$this->session->set_userdata(['userId' => $result[0]['userId'],'userName' =>  $result[0]['userName']]);
        		$this->output->set_output(json_encode(['result' => 1]));
            //    redirect("/diversvisiblity/user/index");
        		return false;
        	}

     		$this->output->set_output(json_encode(['result' => 0, 'data1'=>$userName, 'data' => "Invalid UserName/Email or Password"]));

        }
}