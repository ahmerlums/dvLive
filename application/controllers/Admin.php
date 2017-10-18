<?php
class Admin extends CI_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('admin_model');

		}

		public function view($page = 'home')
        {
            if ($this->session->userdata('adminName') == "" && $page != 'login') {
                show_404();
            }
            else {
	        if ( ! file_exists(APPPATH.'views/admin/'.$page.'.php'))
		        {
		            // Whoops, we don't have a page for that!
		            show_404();
		        }

	        $data['title'] = ucfirst($page); // Capitalize the first letter
	        if ($page == "index") {
	        	$data['allAdmins'] = $this->admin_model->get();
	        }
	        $this->load->view('templates/header', $data);
	        $this->load->view('templates/sidebar', $data);
	        $this->load->view('admin/'.$page, $data);
	        $this->load->view('templates/footer', $data);
        }
        }

        public function create() {
        	$this->output->set_content_type('application_json');
        	
        	$this->form_validation->set_rules('adminName','Admin Name','required|min_length[4]|max_length[15]|is_unique[admin.adminName] ');
        	$this->form_validation->set_rules('adminEmail','Email','required|valid_email|is_unique[admin.adminEmail]');
        	$this->form_validation->set_rules('password','Password','required|min_length[4]|max_length[15]');
        	$this->form_validation->set_rules('confirmpassword','Confirm Password','required|matches[password]');

        	if (!$this->form_validation->run()){
        		$this->output->set_output(json_encode(['result' => 0, 'data' => validation_errors()]));
        		return false;
        	}

        	$adminName = $this->input->post('adminName');
        	$adminEmail = $this->input->post('adminEmail');
        	$password = hash('sha256',$this->input->post('password'). SALT);
        	$passwordConfirmation = $this->input->post('confirmpassword');

        	$result = $this->admin_model->insert([
        		'adminName' => $adminName,
        		'adminEmail' => $adminEmail,
        		'password' => $password
        		]);
                $this->output->set_output(json_encode(['result' => 1]));
                 return true;
        		redirect('admin/view/login', 'refresh');
        }


        public function logout() {
        	$this->session->sess_destroy();
        	$this->session->unset_userdata(array('adminId' => '','adminName'=> ''));
        	$user_id = $this->session->userdata('adminId');
       		redirect('/admin/view/login');
        }

        public function signin($data = null) {
        	$adminName = $this->input->post('adminName');
        	$password = hash('sha256',$this->input->post('password'). SALT);
        	$result = $this->admin_model->get([
        		'adminName' => $adminName,
        		'password' => $password
        		]);
        	if ($result) {
        		$this->session->set_userdata(['adminId' => $result[0]['adminId'],'adminName' =>  $result[0]['adminName']]);
        		$this->output->set_output(json_encode(['result' => 1]));
                redirect("/diversvisiblity/admin/index");
        		return false;
        	}

     		$this->output->set_output(json_encode(['result' => 0]));
            redirect("admin/view/index");

        }
}