<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		//if user already logged, it will be redirected to homepage(forms page)
		if ($this->session->userdata('userrole'))
		{
			redirect(base_url(). "form");
		}
		else
		{
			$userAgent = "";
			
			//if browser is outdated (IE 6,7), there will be some errors in the javascript modules/
			if (($this->agent->browser() == "Internet Explorer") && ($this->agent->version() < 8))
			{
				$userAgent = "<b style='color: red'> Please use an newer browser. Internet Explorer 6 may result error and poor layout.</b>";
			}
			
			$data['userAgent'] = $userAgent;
			$this->load->view('login', $data);
		} //ends if already logged
		
	}
	
	public function log()
	{
		//sends from AJAX
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		
		//check if login is accepted 
		if($this->access_model->login($username, $password))
		{
			$userDetails = $this->access_model->getUserByUsername($username);
			foreach($userDetails->result() as $user)
			{
				//assign database value and create a user cookie.
					$userData = array (
						'userid' => $user->user_id,
						'username' => $username,
						'userrole' => $user->role_id
					);
				$this->session->set_userdata($userData);
			}
			echo true;
		}
		else
		{
			echo false;
		}
	}
	
	public function logout()
	{
		//destroys all sessions and redirects to login page
		$this->session->sess_destroy();
		redirect(base_url(), 'refresh');
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */