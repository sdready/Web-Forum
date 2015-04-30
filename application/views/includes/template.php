<?php

$is_logged_in = $this->session->userdata('is_logged_in');

if(!isset($is_logged_in) || $is_logged_in != true)
	$data['is_logged_in'] = false;
else
	$data['is_logged_in'] = true;

if($this->membership_model->isAdmin($this->session->userdata('username'), $this->session->userdata('password')))
	$data['is_admin'] = true;
else
	$data['is_admin'] = false;

$data['username'] = $this->session->userdata('username');

?>

<?php $this->load->view('includes/header', $data); ?>

<?php 
if(!(isset($hide_sidebar) && $hide_sidebar == true))
{
	$data2['sb_recent_threads'] = $this->membership_model->get_threads(NULL, 5, 0);
	$data2['sb_most_replies'] = $this->membership_model->most_replies(NULL, 5);
	$this->load->view('includes/sidebar', $data2); 
}
?>

<?php $this->load->view($main_content); ?>

<?php $this->load->view('includes/footer'); ?>