<html>
	<head>
		<title><?php echo $this->config->item('product_name')." | ". $page_title; ?></title>
		<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
	<body style="font-family:'open sans',arial;"> 
	<br/><center><a href="<?php echo site_url('my_email/email_history'); ?>" style="background:#357CA5;padding:8px 15px;text-decoration:none;color:#fff;"><?php echo $this->lang->line("back to list"); ?></a></center>
		<div style="border:1px solid #ccc;margin:0 auto;width:95%;margin:20px;padding-bottom:30px;">
			<h3 align="center" style="height:70px;background:#357CA5;color:#fff;margin:0;padding-top:10px;border-top:3px solid #00C0EF;">
				<?php echo $this->lang->line("email preview"); ?> : <?php echo $subject." <br/>". $to." @". $sent_time; ?>
			</h3>
			<br/>
			<?php echo $message;?>
		</div>
	</body>
</html>

