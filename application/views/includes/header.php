<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
	<title>Forum</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" media="screen" title="no title" charset="utf-8">

</head>
<body><div id="site" align="center"">
<div id="header">
	<div id="navbar" style="width:920px; text-align:left;">
	<h1 style="text-align:left;">Forum</h1>
	<?php echo anchor('site', "All"); ?>
	<?php echo anchor('site/view_section/general', 'General Discussion', 'style="padding:20px;"'); ?>
	<?php echo anchor('site/view_section/news', "News"); ?>

	<?php

		if($is_admin)
		{
			echo '<div style="float:right;">';
			echo anchor('site/admin_area', 'Admin Area', 'style="padding:20px;"');
		}
		else if ($is_logged_in)
			echo '<div style="float:right;">';

		if($is_logged_in)
		{
			echo anchor('site/account/' . $username, $username);
			echo anchor('site/logout', 'Logout', 'style="padding:20px;"');
		}
		else
		{
			echo '<div style="float:right; position:relative; right:10px;">';
			echo '<a href="http://localhost/forum/index.php/login">Login</a>';
		}
	?>
	
		</div>
	</div>
</div>

<br /><br />

<div id="page">