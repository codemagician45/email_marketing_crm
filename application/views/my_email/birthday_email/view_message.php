<html>
	<head>
		<title><?php echo $this->config->item('product_name')." | ". $page_title; ?></title>
		<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
	<body style="font-family:'open sans',arial;"> 
		<br/><center><a href="<?php echo site_url('my_email/email_template'); ?>" style="background:#357CA5;padding:8px 15px;text-decoration:none;color:#fff;"><?php echo $this->lang->line("back to list"); ?></a></center>
		<div style="border:1px solid #ccc;margin:0 auto;width:95%;margin:20px;padding-bottom:10px;">
			<h3 align="center" style="height:40px;background:#357CA5;color:#fff;margin:0;padding-top:10px;border-top:3px solid #00C0EF;">
				<?php echo $this->lang->line("birthday wish email preview"); ?> : <?php echo $subject; ?>
			</h3>
			<br/>
			<?php echo $message;?>
		</div>
	</body>
</html>

