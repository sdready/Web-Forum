<?php

class Login extends CI_Controller
{
	function index()
	{
		$data['main_content'] = 'login_form';
		$data['hide_sidebar'] = true;
		$this->load->view('includes/template', $data);
	}

	function validate()
	{
		$this->load->model('membership_model');
		$query = $this->membership_model->validate();

		if($query)
		{
			$data = array(
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'is_logged_in' => true
				);

			$this->session->set_userdata($data);

			if($this->membership_model->isAdmin($data['username'], $data['password']))
				redirect('site/admin_area');
			else
				redirect('site/');
		}
		else
		{
			$this->index();
		}
	}

	function signup()
	{
		$data['main_content'] = "signup_form";
		$data['hide_sidebar'] = true;
		$this->load->view('includes/template', $data);
	}

	function create_member()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');

		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|matches[password]');
	
		if($this->form_validation->run() == FALSE)
		{
			$this->signup();
		}
		else
		{
			$this->load->model('membership_model');

			if($query = $this->membership_model->create_member())
			{
				$data['main_content'] = 'signup_successful';
				$this->load->view('includes/template', $data);
			}
			else
			{
				$this->signup();
			}
		}
	}
}

?>