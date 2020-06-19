<div class="well well_border_left">
	<h4 class="text-center"> <i class="fa fa-clock-o"></i> <?php echo $this->lang->line("Cron Job"); ?></h4>
</div>
<?php $this->load->view('admin/theme/message'); ?>
<section class="content-header">
   <section class="content">
	     	<?php 
			$text="Generate Your ".$this->config->item("product_short_name")." API Key";
			$get_key_text="Get Your ".$this->config->item("product_short_name")." API Key";
			if(isset($api_key) && $api_key!="") 
			{
				$text="Re-generate Your ".$this->config->item("product_short_name")." API Key";
				$get_key_text="Your ".$this->config->item("product_short_name")." API Key";
	   		} 
	   		?>
		    	
		       		<!-- form start -->
		    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'ssem_api/get_api_action';?>" method="GET">
		        <div class="box-body" style="padding-top:0;">
		           	<div class="form-group">
		           		<div class="small-box bg-blue" style="background:#fff !important; color:#777 !important;border-color:#ccc;"">
							<div class="inner">
								<h4><?php echo $get_key_text; ?></h4>
								<p>									
		   							<h2><?php echo $api_key; ?></h2>
								</p>
								<input name="button" type="submit" class="btn btn-primary btn-lg btn" value="<?php echo $text; ?>"/>
							</div>
							<div class="icon">
								<i class="fa fa-key"></i>
							</div>
						</div>
		            </div>	           
	         		               
		           </div> <!-- /.box-body -->      
		    </form> 

		   <?php 
		if($api_key!="" && $this->session->userdata("user_type")=="Admin") { ?>
			
			<div id = 'facebook_check'>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#fff !important; color:<?php echo $THEMECOLORCODE;?> !important;border-color:#fff;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("Scheduled/SMS Cron Job Command [once per minute or higher]"); ?>
					</div>
				</h4>
				<div class="well" style="background:#fff;margin-top:0;border-radius:0;">
					<?php echo "curl ".site_url("ssem_api/ssem_sms_sending_command")."/".$api_key; ?>
				</div>
			</div>

			<div id = 'facebook_check'>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#fff !important; color:<?php echo $THEMECOLORCODE;?> !important;border-color:#fff;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("Scheduled/Email Cron Job Command [once per minute or higher]"); ?>
					</div>
				</h4>
				<div class="well" style="background:#fff;margin-top:0;border-radius:0;">
					<?php echo "curl ".site_url("ssem_api/ssem_email_sending_command")."/".$api_key; ?>
				</div>
			</div>

			<div id = 'facebook_check'>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#fff !important; color:<?php echo $THEMECOLORCODE;?> !important;border-color:#fff;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("Scheduled/Birthday-wish SMS/Email Cron Job Command [once per day or higher]"); ?>
					</div>
				</h4>
				<div class="well" style="background:#fff;margin-top:0;border-radius:0;">
					<?php echo "curl ".site_url("ssem_api/birthday_scheduler")."/".$api_key; ?>
				</div>
			</div>
		<?php }?>

		<?php $call_sync_contact_url=site_url("ssem_api/sync_contact"); ?>	
		<div class="alert alert-success" style="margin-bottom:0;">
			<h3><u>Sync your contact</u></h3>
			<ul>
				<li>Use this function below in your code with your <b>$data</b> array</li>	
				<li>The indexes api_key, first_name, email, contact_group_id are required </li>
				<li>Get your <b>API Key</b> and use it and you will find the <b>Contact Group ID</b> in <b>My Phonebook > Contact Group</b><br/></li>
				<li> <b><u>API Response:</u></b><br/><br/>	
				    status: 0 = failed and 1 = succeed<br/><br/>
				    response_code : <br/>
				    1100 = data recieved but failed to sync<br/>
				    1101 = data recieved and updated<br/>
				    1110 = data recieved and inserted<br/>
				    1000 = one or more required fields are missing<br/>
				    0000 = invalid user<br/><br/>
				    details : details of response
				    
				</li>
			</ul>
		</div>
		<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
		<?php 		
		highlight_string ('
		function call_sync_contact()
		{    
			$url ="'.$call_sync_contact_url.'";
	        $data=array
	        (
	            "api_key"           => "1n2U51455446947iBlwn",
	            "first_name"        => "Al-amin",
	            "last_name"         => "Jwel",
	            "mobile"            => "8801723309003",
	            "email"             => "jwel.cse@gmail.com",
	            "contact_group_id"  => "1",
	            "date_birth"        => "1989-12-21"
	        );
	         
	        $ch=curl_init($url);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
	        curl_setopt($ch, CURLOPT_HEADER, 0);  
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
	        curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
	        curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
	        $response = curl_exec( $ch );
	        curl_close($ch);        
	        $response=json_decode($response,TRUE);
	        print_r($response);
		}') ?>
    	</div>


    	<?php $call_send_email_api_url=site_url("ssem_api/send_email_api"); ?>	
		<div class="alert alert-success" style="margin-bottom:0;">
			<h3><u>Send Email (Bulk/Single)</u></h3>
			<ul>
				<li>Use this function in your code with your <b>$data</b> array</li>	
				<li>All indexes api_key, email (comma seperated,if multiple), reference_id, gateway_name, subject, message are required </li>
				<li>Possible <b>Gateway</b> names : smtp,mandrill,sendgrid,mailgun </li>
				<li>Get your <b>API Key</b> and use it and you will find the <b>Reference ID</b> in <b>My Email > Email API</b><br/></li>
				<li> <b><u>API Response:</u></b><br/><br/>	
				   status  : 0 = failed and 1 = succeed<br/><br/>				    
				   details : details of response				    
				</li>
			</ul>
		</div>
		<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
		<?php 		
		highlight_string ('
		function call_send_email_api()
		{    
			$url ="'.$call_send_email_api_url.'";
	        $data=array
	        (
	            "api_key"       => "1n2U51455446947iBlwn",
	            "email"         => "jwel.cse@gmail.com,konokronok@gmail.com,mostofa.ru@gmail.com",
            	"reference_id"  => "1",
            	"gateway_name"	=> "smtp",
            	"subject"       => "test subject",
            	"message"       => "this is a test email"
	        );
	         
	        $ch=curl_init($url);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
	        curl_setopt($ch, CURLOPT_HEADER, 0);  
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
	        curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
	        curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
	        $response = curl_exec( $ch );
	        curl_close($ch);        
	        $response=json_decode($response,TRUE);
	        print_r($response);
		}') ?>
    	</div>


    	<?php $call_send_sms_api_url=site_url("ssem_api/send_sms_api"); ?>	
		<div class="alert alert-success" style="margin-bottom:0;">
			<h3><u>Send SMS (Bulk/Single)</u></h3>
			<ul>
				<li>Use this function in your code with your <b>$data</b> array</li>	
				<li>All indexes api_key, mobile (comma seperated,if multiple) , reference_id, message are required </li>
				<li>Get your <b>API Key</b> and use it and you will find the <b>Reference ID</b> in <b>My SMS > SMS API</b><br/></li>
				<li> <b><u>API Response:</u></b><br/><br/>	
				   status  : 0 = failed and 1 = succeed<br/><br/>				    
				   details : details of response				    
				</li>
			</ul>
		</div>
		<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
		<?php 		
		highlight_string ('
		function call_send_sms_api()
		{    
			$url ="'.$call_send_sms_api_url.'";

	        $data=array
	        (
	            "api_key"       => "1n2U51455446947iBlwn",
	            "mobile"        => "8801722977459,8801723309003",
	            "reference_id"  => "5",
	            "message"       => "this is a test sms"
	        );
	         
	        $ch=curl_init($url);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
	        curl_setopt($ch, CURLOPT_HEADER, 0);  
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
	        curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
	        curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
	        $response = curl_exec( $ch );
	        curl_close($ch);        
	        $response=json_decode($response,TRUE);
	        print_r($response);
		}') ?>
    	</div>
   </section>
</section>



