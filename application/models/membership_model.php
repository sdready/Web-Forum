<?php

class Membership_model extends CI_Model 
{
	//var $current_user;

	/********************************************************************************
		Account Management
	********************************************************************************/
	function validate()
	{
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password')));
		$query = $this->db->get('members');

		if($query->num_rows == 1)
		{
			return true;
		}
	}

	function isAdmin($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$this->db->where('member_type', "admin");
		$query = $this->db->get('members');

		if($query->num_rows == 1)
			return true;
	}

	function is_current_user($account_name)
	{
		$this->db->where('username', $account_name);
		$query = $this->db->get('members');

		if($query->num_rows != 1)
			return false;

		$account = $query->row();
		$account_pass = $account->password;

		if(strcmp($account_name, $this->session->userdata('username')) != 0 || strcmp($account_pass, $this->session->userdata('password')) != 0)
			return false;
		else
			return true;
	}

	function create_member()
	{
		$new_member_insert_data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email_address' => $this->input->post('email_address'),
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'member_type' => "user",
			'join_date' => now()
		);

		$insert = $this->db->insert('members', $new_member_insert_data);

		return $insert;
	}

	function change_account_type($account_id)
	{
		$this->db->where('id', $account_id);
		$account = $this->db->get('members')->row();
		
		if($this->isAdmin($account->username, $account->password))
		{
			$this->db->where('id', $account_id);
			$this->db->update('members', array('member_type' => "user"));
		}
		else
		{
			$this->db->where('id', $account_id);
			$this->db->update('members', array('member_type' => "admin"));
		}
	}

	function delete_account($account_id)
	{
		$this->db->delete('members', array('id' => $account_id));
	}

	function get_accounts()
	{
		$this->db->order_by('username');
		$query = $this->db->get('members');
		return $query->result();
	}

	function get_member($account_name)
	{
		$this->db->where('username', $account_name);
		$query = $this->db->get('members');

		if($query->num_rows == 0)
			$account = NULL;
		else
			$account = $query->row();

		return $account;
	}

	function get_post_count($username)
	{
		$this->db->where('username', $username);
		$member = $this->db->get('members')->row();
		$num_posts = $member->num_posts;

		return $num_posts;
	}

	function get_avatar($username)
	{
		$this->db->where('username', $username);
		$member = $this->db->get('members')->row();
		$image_url = $member->avatar;

		return $image_url;
	}


	/********************************************************************************
		Thread Management
	********************************************************************************/


	function get_threads($section, $limit, $offset)
	{
		if($section != NULL)
			$this->db->where('section', $section);

		$this->db->order_by('time', 'desc');
		$query = $this->db->get('threads', $limit, $offset);

		if($query->num_rows == 0)
			return NULL;
		else
			return $query->result();
	}

	function threads_by_user($username, $limit, $offset)
	{
		$this->db->order_by('time', 'desc');
		$this->db->where('author', $username);
		$query = $this->db->get('threads', $limit, $offset);

		if($query->num_rows == 0)
			return NULL;
		else
			return $query->result();
	}

	function most_replies($username, $limit)
	{
		if($username != NULL)
			$this->db->where('author', $username);

		$this->db->order_by('num_posts', 'desc');
		$this->db->limit($limit);

		if($limit == 1)
			return $this->db->get('threads')->row();
		else
			return $this->db->get('threads')->result();
	}

	function get_thread_info($thread_id)
	{
		$this->db->where('id', $thread_id);
		$query = $this->db->get('threads');

		if($query->num_rows == 0)
			return NULL;
		else
			return $query->row();
	}

	function create_thread()
	{
		$new_thread_insert_data = array(
			'section' => $this->input->post('section'),
			'title' => $this->input->post('title'),
			'author' => $this->session->userdata('username'),
			'original_post' => $this->input->post('post'),
			'num_posts' => 0,
			'time' => now()
		);

		$this->db->insert('threads', $new_thread_insert_data);
		
		return $this->db->insert_id();
	}

	function move_thread($thread_id)
	{
		$this->db->where('id', $thread_id);
		$this->db->update('threads', array('section' => $this->input->post('section')));
	}

	function delete_thread($thread_id)
	{
		$this->db->delete('threads', array('id' => $thread_id));
	}



	/********************************************************************************
		Post Management
	********************************************************************************/

	function get_posts($thread_id, $limit, $offset)
	{
		$this->db->order_by('id', 'asc');
		$this->db->where('thread_id', $thread_id);
		$query = $this->db->get('posts', $limit, $offset);

		return $query->result();
	}

	function recent_posts($username, $limit, $offset)
	{
		$this->db->order_by('time', 'desc');
		$this->db->where('author', $username);
		$query = $this->db->get('posts', $limit, $offset);

		return $query->result();
	}

	function make_post($thread_id)
	{
		$content = $this->input->post('content');

		$content = str_replace(array("\r\n", "\r", "\n"), "<br />", $content);

		$this->db->where('id', $thread_id);
		$thread = $this->db->get('threads')->row();
		
		$thread_title = $thread->title;

		$username = $this->session->userdata('username');

		$new_post_insert_data = array(
			'author' => $username,
			'post_content' => $content,
			'thread_id' => $thread_id,
			'thread_title' => $thread_title,
			'time' => now()
		);

		//Update number of post made by user
		$this->db->where('username', $username);
		$member = $this->db->get('members')->row();
		
		$num_posts = $member->num_posts + 1;

		$this->db->where('username', $username);
		$this->db->update('members', array('num_posts' => $num_posts));

		//Update number of posts in thread
		$num_posts = $thread->num_posts + 1;
		$post_num = $num_posts + 1;

		$this->db->where('id', $thread_id);
		$this->db->update('threads', array('num_posts' => $num_posts));


		//Insert the post into the thread
		$this->db->insert('posts', $new_post_insert_data);
		$post_id = $this->db->insert_id();

		$this->db->where('id', $post_id);
		$this->db->update('posts', array('post_content' => $content, 'post_num' => $post_num));

		return $post_num;
	}

	function delete_post($post_id)
	{
		$this->db->where('id', $post_id);
		$post = $this->db->get('posts')->row();

		$thread_id = $post->thread_id;
		$author = $post->author;	

		$this->db->delete('posts', array('id' => $post_id));

		//Update the number of posts in the thread
		$this->db->where('id', $thread_id);
		$thread = $this->db->get('threads')->row();

		$num_posts = $thread->num_posts - 1;

		$this->db->where('id', $thread_id);
		$this->db->update('threads', array('num_posts' => $num_posts));

		//Update the number of posts made by the user
		$this->db->where('username', $author);
		$member = $this->db->get('members')->row();
		
		$num_posts = $member->num_posts - 1;

		$this->db->where('username', $author);
		$this->db->update('members', array('num_posts' => $num_posts));

		return $thread_id;
	}

	function change_hidden($post_id)
	{
		$this->db->where('id', $post_id);
		$post = $this->db->get('posts')->row();

		$hidden = $post->hidden;
		$thread_id = $post->thread_id;

		if($hidden == 0)
		{
			$this->db->where('id', $post_id);
			$this->db->update('posts', array('hidden' => 1));
		}
		else
		{
			$this->db->where('id', $post_id);
			$this->db->update('posts', array('hidden' => 0));
		}

		return $thread_id;
	}



	/********************************************************************************
		Messaging
	********************************************************************************/

	function get_messages($username, $type)
	{
		if(strcmp($type, 'inbox') == 0)
			$this->db->where('receiver', $username);
		else
			$this->db->where('sender', $username);

		$this->db->order_by('id', 'desc');
		return $this->db->get('messages')->result();
	}

	function get_message($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('messages')->row();
	}

	function send_message($sender)
	{
		$receiver = $this->input->post('receiver');

		$new_message_insert_data = array(
			'sender' => $sender,
			'receiver' => $receiver,
			'subject' => $this->input->post('subject'),
			'message' => $this->input->post('message'),
			'time' => now()
		);

		$insert = $this->db->insert('messages', $new_message_insert_data);

		return $receiver;
	}

	function delete_message($id)
	{
		$this->db->where('id', $id);
		$message = $this->db->get('messages')->row();

		$this->db->delete('messages', array('id' => $id));

		return $message->receiver;
	}
}

?>