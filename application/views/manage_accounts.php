<h3 style="text-align:left;">Manage Accounts</h3>
<table style="width:600px;">
<?php foreach($accounts as $account) : ?>
	
		<tr>
			<th style="text-align:left; border-bottom:1px solid #C6D6F8; border-top:1px solid #C6D6F8;">
				<?php echo anchor('site/account/' . $account->username, $account->username, 'style="color: #100A3E;"'); ?>
			</th>
			<td style="text-align:left; border-bottom:1px solid #C6D6F8; border-top:1px solid #C6D6F8;">
				<?php
					if($account->member_type == "admin")
						echo '<a href="change_account_type/' . $account->id . '">Make User</a>&nbsp;&nbsp;&nbsp;';
					else
						echo '<a href="change_account_type/' . $account->id . '">Make Admin</a>';
			
					echo '<a href="manage_accounts" onclick="confirmDelete(\''. $account->username . '\',' . $account->id . ');" style="padding:20px;">Delete</a>';
				?>
			</td>
		</tr>
		<tr>
			<td>Name:</td>
			<td><?php echo $account->first_name . ' ' . $account->last_name; ?></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><?php echo $account->email_address; ?></td>
		</tr>
		<tr>
			<td>Type:</td>
			<td><?php echo $account->member_type; ?></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	

<?php endforeach; ?>
</table>

<script type="text/javascript">

function confirmDelete(name, id)
{
	var x = confirm("Delete " + name + "'s account?");

	if(x==true)
	{
		window.location = "http://localhost/forum/index.php/site/delete_account/" + id;
	}
}

</script>