<?php

class Site extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('membership_model');		
	}

	function index()
	{
		$config['base_url'] = 'http://localhost/forum/index.php/site/index';
		$config['total_rows'] = $this->db->get('threads')->num_rows();
		$config['per_page'] = 10;
		$config['full_tag_open'] = '<div id="pagination">';
		$config['full_tag_close'] = '</div>';
		
		$this->pagination->initialize($config);

		$data['recent_threads'] = $this->membership_model->get_threads(NULL, $config['per_page'] = 10, $this->uri->segment(3));

		$data['main_content'] = 'view_section';
		$data['section'] = 'All';
		$this->load->view('includes/template', $data);	
	}

	function admin_area()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
			$data['main_content'] = 'admin_area';
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
		}

		$this->load->view('includes/template', $data);
	}

	function manage_accounts()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
		{
			$data['main_content'] = 'manage_accounts';

			$data['accounts'] = $this->membership_model->get_accounts();
		}
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
		}

		$this->load->view('includes/template', $data);
	}

	function change_account_type()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
		{
			$account_id = $this->uri->segment(3);
			$this->membership_model->change_account_type($account_id);
			redirect('site/manage_accounts');
		}
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);
		}		
	}

	function delete_account()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
		{
			$account_id = $this->uri->segment(3);
			$this->membership_model->delete_account($account_id);
			redirect('site/manage_accounts');
		}
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);
		}		
	}

	function account()
	{
		$account_name = $this->uri->segment(3);
		$page = $this->uri->segment(4);
		$message_id = $this->uri->segment(5);
		$error = false;

		if(!$this->membership_model->is_current_user($account_name)) //Viewing the account of another user
			$data['is_user'] = false;
		else
			$data['is_user'] = true;

		$is_logged_in = $this->session->userdata('is_logged_in');

		if(!isset($is_logged_in) || $is_logged_in != true)
			$data['is_logged_in'] = true;
		else
			$data['is_logged_in'] = false;

		$data['account'] = $this->membership_model->get_member($account_name);

		if($data['account'] != NULL)
		{
			if($page == NULL || is_numeric($page)) //Main account page is being viewed
			{
				$config['base_url'] = 'http://localhost/forum/index.php/site/account/' . $account_name;
				$this->db->where('author', $account_name);
				$config['total_rows'] = $this->db->get('posts')->num_rows();
				$config['per_page'] = 10;
				$config['full_tag_open'] = '<div id="pagination">';
				$config['full_tag_close'] = '</div>';
				$config['uri_segment'] = 4;
				
				$this->pagination->initialize($config);
				$data['recent_posts'] = $this->membership_model->recent_posts($account_name, $config['per_page'] = 10, $this->uri->segment(4));

				$data['main_content'] = 'account';
			}
			else if($message_id == NULL || strcmp($page, 'messages') != 0) //One of the other pages (tabs) is being viewed
			{
				$data['main_content'] = $page;

				if($page == 'messages' && !$this->membership_model->is_current_user($account_name)) //Attempting to view messages of other user
				{
					$data['main_content'] = 'access_denied';
					$data['hide_sidebar'] = true;
				}		
				else if($page == 'threads')	//Threads page	
				{
					$config['base_url'] = 'http://localhost/forum/index.php/site/account/' . $account_name . '/threads';
					$this->db->where('author', $account_name);
					$config['total_rows'] = $this->db->get('threads')->num_rows();
					$config['per_page'] = 10;
					$config['full_tag_open'] = '<div id="pagination">';
					$config['full_tag_close'] = '</div>';
					$config['uri_segment'] = 5;
				
					$this->pagination->initialize($config);

					$data['recent_threads'] = $this->membership_model->threads_by_user($account_name, $config['per_page'] = 10, $this->uri->segment(5));

					$data['most_commented_thread'] = $this->membership_model->most_replies($account_name, 1);
				}
				else //Messages page is being viewed
				{
					$data['type'] = 'inbox';
					$data['messages'] = $this->membership_model->get_messages($account_name, 'inbox');
				}
			}	
			else if(is_numeric($message_id)) //Specific message is being viewed
			{
				$message = $this->membership_model->get_message($message_id);
				if($message == NULL)
				{	$this->error(); $error = true;}
				else
				{
					if(!$this->membership_model->is_current_user($account_name))
					{	
						$data['main_content'] = 'access_denied';
						$data['hide_sidebar'] = true;
					}
					else
					{
						$data['message'] = $message;
						$data['main_content'] = 'view_message';
					}
				}
			}
			else //Sent messages being viewed
			{
				if($page == 'messages' && !$this->membership_model->is_current_user($account_name)) //Attempting to view messages of other user
				{
					$data['main_content'] = 'access_denied';
					$data['hide_sidebar'] = true;
				}	
				else
				{
					$data['main_content'] = 'messages';
					$data['type'] = 'sent';
					$data['messages'] = $this->membership_model->get_messages($account_name, 'sent');
				}	
			}

			if($error != true)
				$this->load->view('includes/template', $data);
		}
		else
			$this->error();
	}

	function view_thread()
	{
		$thread_id = $this->uri->segment(3);
		$page_num = $this->uri->segment(4);

		$data['thread'] = $this->membership_model->get_thread_info($thread_id);

		if($page_num != NULL)
			$data['page'] = '/' + $page_num;
		else
			$data['page'] = '';
	
		if($data['thread'] != NULL)
		{
			$config['base_url'] = 'http://localhost/forum/index.php/site/view_thread/' . $thread_id;
			$this->db->where('thread_id', $thread_id);
			$config['total_rows'] = $this->db->get('posts')->num_rows();
			$config['per_page'] = 14;
			$config['full_tag_open'] = '<div id="pagination">';
			$config['full_tag_close'] = '</div>';
			$config['uri_segment'] = 4;
			
			$this->pagination->initialize($config);

			if($this->uri->segment(4) == NULL)
			{
				$data['posts'] = $this->membership_model->get_posts($thread_id, $config['per_page'] = 14, $this->uri->segment(4));
				$data['hide_op'] = false;
			}
			else
			{
				$data['posts'] = $this->membership_model->get_posts($thread_id, 15, $this->uri->segment(4));
				$data['hide_op'] = true;
			}

			$username = $this->session->userdata('username');
			$password = $this->session->userdata('password');

			if($this->membership_model->isAdmin($username, $password))
				$data['is_admin'] = true;
			else
				$data['is_admin'] = false;

			$data['main_content'] = 'display_thread';
			$this->load->view('includes/template', $data);
		}
		else
			$this->error();
	}

	function view_section()
	{
		$section = $this->uri->segment(3);

		$config['base_url'] = 'http://localhost/forum/index.php/site/view_section/' . $section;
		$this->db->where('section', $section);
		$config['total_rows'] = $this->db->get('threads')->num_rows();
		$config['per_page'] = 10;
		$config['full_tag_open'] = '<div id="pagination">';
		$config['full_tag_close'] = '</div>';
		$config['uri_segment'] = 4;
		
		$this->pagination->initialize($config);

		$data['recent_threads'] = $this->membership_model->get_threads($section, $config['per_page'] = 10, $this->uri->segment(4));
		
		if($data['recent_threads'] != NULL)
		{
			$data['section'] = $section;
			$data['main_content'] = 'view_section';
			$this->load->view('includes/template', $data);
		}
		else
			$this->error();
	}

	function make_post()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');

		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);
		}
		else
		{
			$thread_id = $this->uri->segment(3);
			$post_num = $this->membership_model->make_post($thread_id);

			$data['thread'] = $this->membership_model->get_thread_info($thread_id);
			$data['posts'] = $this->membership_model->get_posts($thread_id);

			$page_num = ceil($post_num/15.0);

			if($page_num == 1)
				redirect('site/view_thread/' . $thread_id);
			else
				redirect('site/view_thread/' . $thread_id . '/' . ($page_num-1)*14);
		}
	}

	function delete_post()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
		{
			$post_id = $this->uri->segment(3);
			$thread_id = $this->membership_model->delete_post($post_id);
			redirect("site/view_thread/" . $thread_id);
		}
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);			
		}
	}

	function change_hidden()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
		{
			$post_id = $this->uri->segment(3);
			$thread_id = $this->membership_model->change_hidden($post_id);
			redirect("site/view_thread/" . $thread_id);
		}
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);			
		}
	}

	function create_thread()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');

		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);
		}
		else
		{
			if(!($this->uri->segment(3) === 'add'))
			{
				$data['main_content'] = 'create_thread';
				$this->load->view('includes/template', $data);
			}
			else
			{
				$thread_id = $this->membership_model->create_thread();
				$thread_address = 'site/view_thread/' . $thread_id;
				redirect($thread_address);
			}	
		}	
	}

	function move_thread()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
		{
			$thread_id = $this->uri->segment(3);
			$this->membership_model->move_thread($thread_id);

			redirect("site/view_thread/" . $thread_id);
		}
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);
		}
	}

	function delete_thread()
	{
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');

		if($this->membership_model->isAdmin($username, $password))
		{
			$thread_id = $this->uri->segment(3);
			$this->membership_model->delete_thread($thread_id);
			redirect("site");
		}
		else
		{
			$data['main_content'] = 'access_denied';
			$data['hide_sidebar'] = true;
			$this->load->view('includes/template', $data);
		}
	}

	function send_message()
	{
		$username = $this->session->userdata('username');
		
		$account = $this->membership_model->send_message($username);

		redirect('site/account/' . $account);
	}

	function delete_message()
	{
		$account = $this->membership_model->delete_message($this->uri->segment(3));

		redirect('site/account/' . $account . '/messages');

	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect("site");	
	}

	function error()
	{
		$data['main_content'] = 'display_error';
		$this->load->view('includes/template', $data);
	}
}

?>