<!DOCTYPE html>
<html>
<head>
	<title>Download</title>
	<link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/css/font-awesome.min.css" rel="stylesheet">
	<style>
	.box
	{
		border:1px solid #ccc;	
		margin: 0 auto;
		text-align: center;
		margin-top:10%;
		padding-bottom: 20px;
		background-color: #fffddd;
		color:#000;
	}
	.btn-warning
	{
		width: 200px;
	}
	</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
			<div class="box">
			<h2>Your file is ready to download</h2>
			If download does not start automatically, please click the button below.<br/>
			<?php 
			if(isset($file_name) && $file_name!="")
			{
				echo '<i class="fa fa-2x fa-thumbs-o-up"style="color:black"></i><br><br>';
				echo "<a id='download' href='".base_url().$file_name."' title='Download' class='btn btn-warning btn-lg'><i class='fa fa-cloud-download' style='color:white'></i> Download</a>";
			}
			?>
			</div>		
			
		</div>
	</div>
</div>	
</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
	$("document").ready(function()
	{
		window.location.href="<?php echo base_url().$file_name; ?>"; 
	});
</script>