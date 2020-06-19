<?php $this->load->view('admin/theme/message'); ?>

<section class="content-header">
   <section class="content">
   		<div class="" id="modal-id">
   			<div class="modal-dialog" style="width: 100%;margin:0;">
   				<div class="modal-content">
   					<div class="modal-header">
   						<h4 class="modal-title"><i class="fa fa-cogs"></i> <?php echo $this->lang->line("general settings");?></h4>
   					</div>
   					<form class="form-horizontal text-c" enctype="multipart/form-data" action="<?php echo site_url().'setting/edit_config';?>" method="POST">		     
			        <div class="modal-body">
			        	<div class="row">
			        		<!-- General settings -->
			        		<div class="col-xs-12 col-md-6">
			        			<fieldset style="padding:30px; min-height: 453px;">
			        				<legend class="block_title"><i class="fa fa-flag"></i> <?php echo $this->lang->line('Brand Settings'); ?></legend>

						           	<div class="form-group">
						             	<label for=""><i class="fa fa-globe"></i> <?php echo $this->lang->line("Application Name");?> </label>
				               			<input name="product_name" value="<?php echo $this->config->item('product_name');?>"  class="form-control" type="text">		          
				             			<span class="red"><?php echo form_error('product_name'); ?></span>
						            </div>

	        						<div class="form-group">
						             	<label for=""><i class="fa fa-compress"></i> <?php echo $this->lang->line("Application Short Name");?> </label>
				               			<input name="product_short_name" value="<?php echo $this->config->item('product_short_name');?>"  class="form-control" type="text">
				             			<span class="red"><?php echo form_error('product_short_name'); ?></span>
						            </div>

						           <div class="form-group">
						              	<label for=""><i class="fa fa-briefcase"></i> <?php echo $this->lang->line("company name");?></label>
				               			<input name="institute_name" value="<?php echo $this->config->item('institute_address1');?>"  class="form-control" type="text">	
				             			<span class="red"><?php echo form_error('institute_name'); ?></span>
				            		</div>

						            <div class="form-group">
						             	<label for=""><i class="fa fa-map-marker"></i> <?php echo $this->lang->line("company address");?></label>
				               			<input name="institute_address" value="<?php echo $this->config->item('institute_address2');?>"  class="form-control" type="text">
				             			<span class="red"><?php echo form_error('institute_address'); ?></span>
						           	</div>

						           <div class="row">
							           <div class="col-xs-12 col-md-6">
								           <div class="form-group">
								             	<label for=""><i class="fa fa-envelope"></i> <?php echo $this->lang->line("company email");?> *</label>
						               			<input name="institute_email" value="<?php echo $this->config->item('institute_email');?>"  class="form-control" type="email">
						             			<span class="red"><?php echo form_error('institute_email'); ?></span>
								           </div>  
								        </div>
								        <div class="col-xs-12 col-md-6">	
								            <div class="form-group">
								             	<label for=""><i class="fa fa-mobile"></i> <?php echo $this->lang->line("company phone / mobile");?></label>
						               			<input name="institute_mobile" value="<?php echo $this->config->item('institute_mobile');?>"  class="form-control" type="text">
						             			<span class="red"><?php echo form_error('institute_mobile'); ?></span>
								           </div>
							       		</div>
						           </div>

			        			</fieldset>
			        		</div>	
							<div class="col-xs-6 col-md-6">
								<fieldset style="padding:30px;min-height: 453px;">
									   <legend class="block_title"><i class="fa fa-tasks"></i> <?php echo $this->lang->line("Preference Settings");?></legend>

		   	           		           <div class="form-group">
		   	           		             	<label><i class="fa fa-language"></i> <?php echo $this->lang->line("language");?>
		   	           		             	</label>             			
	              	               			<?php
	              							$select_lan="english";
	              							if($this->config->item('language')!="") $select_lan=$this->config->item('language');
	              							echo form_dropdown('language',$language_info,$select_lan,'class="form-control" id="language"');  ?>		          
	              	             			<span class="red"><?php echo form_error('language'); ?></span>
		   	           	             		
		   	           		           </div>

       						            <div class="form-group">
       	                   	             	<label><i class="fa fa-window-restore"></i> <?php echo $this->lang->line("Back-end Theme");?>
       	                   	             	</label>
       	                            		           			
                   			        		<?php 
                      	               			$select_theme="skin-black-light";
                      							if($this->config->item('theme')!="") $select_theme=$this->config->item('theme');
                      							echo form_dropdown('theme',$themes,$select_theme,'class="form-control" id="theme"');  ?>	          
                                   			<span class="red"><?php echo form_error('theme'); ?></span>
       	                            		
       	                   	           	</div>

   				           	           	<div class="form-group">
   				           	             	<label><i class="fa fa-clock-o"></i> <?php echo $this->lang->line('Time Zone'); ?> 
   				           	             	</label>             			
   	                          				<?php	$time_zone['']="Time Zone";
   			           							echo form_dropdown('time_zone',$time_zone,$this->config->item('time_zone'),'class="form-control" id="time_zone"');  ?>		          
   		                        			<span class="red"><?php echo form_error('time_zone'); ?></span>
   				           	           	</div>

       	           	           			<div class="form-group">
       	           	             			<label><i class="fa fa-shield-alt"></i> <?php echo $this->lang->line('Force HTTPS');?>?</label>
       	           	               			<?php	
       	           	               			$force_https = $this->config->item('force_https');
       	           	               			if($force_https == '') $force_https='0';
       	           							echo form_dropdown('force_https',array('0'=>$this->lang->line('no'),'1'=>$this->lang->line('yes')),$force_https,'class="form-control" id="force_https"');  ?>		          
       	           	             			<span class="red"><?php echo form_error('force_https'); ?></span>
       	           	           			</div>

           					           	<div class="form-group">
           					             	<label><i class="fa fa-at"></i> <?php echo $this->lang->line('Email sending option');?></label> 
           			               			<?php	
           			               			if($this->config->item('email_sending_option') == '') $selected = 'php_mail';
           			               			else $selected = $this->config->item('email_sending_option');
           			               			$email_sending_option['php_mail']=$this->lang->line('I want to use native PHP mail option.');
           			               			$email_sending_option['smtp']=$this->lang->line('I want to use SMTP option.');
           									echo form_dropdown('email_sending_option',$email_sending_option,$selected,'class="form-control" id="email_sending_option"');  ?>
           			             			<span class="red"><?php echo form_error('email_sending_option'); ?></span>
           					           	</div>

								</fieldset>
								
							</div>

   		
			        	</div> 
						<br>
			        	<div class="row">
			        		<!-- Logo and favicon section -->
							<div class="col-xs-12 col-md-6">
				        		<fieldset style="padding:30px;min-height: 530px;">
				        			   <legend class="block_title"><i class="fa fa-image"></i> <?php echo $this->lang->line("Logo & Favicon Settings");?></legend>

				        			   <!-- Logo -->
							           <div class="form-group text-center">
							             	<label for=""><?php echo $this->lang->line("logo");?></label>
						           			<div class='text-center' style="padding:10px;"><img class="img-responsive center-block" src="<?php echo base_url().'assets/images/logo.png';?>" alt="Logo"/></div>
					               			<small><?php echo $this->lang->line("Max Dimension");?> : 600 x 300, <?php echo $this->lang->line("Max Size");?> : 200KB,  <?php echo $this->lang->line("Allowed Format");?> : png</small>
					               			<input name="logo" class="form-control" type="file">		          
					             			<span class="red"> <?php echo $this->session->userdata('logo_error'); $this->session->unset_userdata('logo_error'); ?></span>
							           </div> 
										<br><br>
							           <div class="form-group text-center">
							             	<center><label for=""><?php echo $this->lang->line("favicon");?></label></center>
					             			<div class='text-center'><img class="img-responsive center-block" src="<?php echo base_url().'assets/images/favicon.png';?>" alt="Favicon"/></div>
					               			 <small><?php echo $this->lang->line("Max Dimension");?> : 32 x 32, <?php echo $this->lang->line("Max Size");?> : 50KB, <?php echo $this->lang->line("Allowed Format");?> : png</small>
					               			<input name="favicon"  class="form-control" type="file">		          
					             			<span class="red"><?php echo $this->session->userdata('favicon_error'); $this->session->unset_userdata('favicon_error'); ?></span>
							           </div>
				        		</fieldset>	
			        		</div>	     

			        		<!-- SMS sending settings -->
			        		<div class="col-xs-12 col-md-6">
			        			<fieldset style="padding:30px; min-height: 250px; margin-bottom: 20px;">
			        				<legend class="block_title"><i class="fa fa-send"></i> <?php echo $this->lang->line('SMS Sending Settings'); ?></legend>

   			           	           	<div class="form-group">
   			           	             	<label class="control-label" for="name"><?php echo $this->lang->line('Number of SMS Send per Cron Job'); ?> 
   			           	             	</label>             			
                             			<?php 
					             			$number_of_sms_to_be_sent_in_try = $this->config->item('number_of_sms_to_be_sent_in_try');
					             			if($number_of_sms_to_be_sent_in_try == "") $number_of_sms_to_be_sent_in_try = 10; 
				             			?>
				               			<input name="number_of_sms_to_be_sent_in_try" value="<?php echo $number_of_sms_to_be_sent_in_try;?>"  class="form-control" type="number" min="0">		          
				             			<span><?php echo $this->lang->line('0 means unlimited');?></span><br>
				             			<span class="red"><?php echo form_error('number_of_sms_to_be_sent_in_try'); ?></span>
   			           	           	</div> 

   			           	           	<div class="form-group">
   			           	             	<label class="control-label" for="name"><?php echo $this->lang->line('SMS Sending Report Update Frequency'); ?> 
   			           	             	</label>             			
                             			<?php 
					             			$update_sms_sending_report_after_time = $this->config->item('update_sms_sending_report_after_time');
					             			if($update_sms_sending_report_after_time == "") $update_sms_sending_report_after_time = 5; 
				             			?>
				               			<input name="update_sms_sending_report_after_time" value="<?php echo $update_sms_sending_report_after_time;?>"  class="form-control" type="number" min="1">
				             			<span class="red"><?php echo form_error('update_sms_sending_report_after_time'); ?></span>
   			           	           	</div>
			        			</fieldset>
			        		</div>

			        		<!-- Email Sending Setting -->
			        		<div class="col-xs-12 col-md-6">
			        			<fieldset style="padding:30px; min-height: 250px;">
			        				<legend class="block_title"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('Email Sending Settings'); ?></legend>

   			           	           	<div class="form-group">
   			           	             	<label class="control-label" for="name"><?php echo $this->lang->line('Number of Email Send per Cron Job'); ?> 
   			           	             	</label> 
   			           	             	<?php 
					             			$number_of_email_to_be_sent_in_try = $this->config->item('number_of_email_to_be_sent_in_try');
					             			if($number_of_email_to_be_sent_in_try == "") $number_of_email_to_be_sent_in_try = 10;
				             			?>
				               			<input name="number_of_email_to_be_sent_in_try" value="<?php echo $number_of_email_to_be_sent_in_try;?>"  class="form-control" type="number" min="0">		          
				             			<span><?php echo $this->lang->line('0 means unlimited');?></span><br>
				             			<span class="red"><?php echo form_error('number_of_email_to_be_sent_in_try'); ?></span>            			
                             			
   			           	           	</div> 

   			           	           	<div class="form-group">
   			           	             	<label class="control-label" for="name"><?php echo $this->lang->line('Email Sending Report Update Frequency'); ?> 
   			           	             	</label>
   			           	             	<?php 
					             			$update_email_sending_report_after_time=$this->config->item('update_email_sending_report_after_time');
					             			if($update_email_sending_report_after_time=="") $update_email_sending_report_after_time=5; 
				             			?>
				               			<input name="update_email_sending_report_after_time" value="<?php echo $update_email_sending_report_after_time;?>"  class="form-control" type="number" min="1">
				             			<span class="red"><?php echo form_error('update_email_sending_report_after_time'); ?></span>

                             			
   			           	           	</div>
			        			</fieldset>
			        		</div>
			        	</div> <br>
						
						<div class="row">
							<div class="col-xs-12 col-md-6">
			        			<fieldset style="padding:30px; min-height: 250px;">
			        				<legend class="block_title"><i class="fa fa-plug"></i> <?php echo $this->lang->line('SMS/Email API Access Setting'); ?></legend>

       	           	           			<div class="form-group">
       	           	             			<label><i class="fa fa-send"></i> <?php echo $this->lang->line('Do you want to give SMS API Access to User?');?></label>
       	           	               			<?php	
       	           	               			$sms_api_access = $this->config->item('sms_api_access');
       	           	               			if($sms_api_access == '') $sms_api_access='0';
       	           							echo form_dropdown('sms_api_access',array('0'=>$this->lang->line('no'),'1'=>$this->lang->line('yes')),$sms_api_access,'class="form-control" id="sms_api_access"');  ?>		          
       	           	             			<span class="red"><?php echo form_error('sms_api_access'); ?></span>
       	           	           			</div> 

       	           	           			<div class="form-group">
       	           	             			<label><i class="fa fa-envelope"></i> <?php echo $this->lang->line('Do you want to give Email API Access to User?');?></label>
       	           	               			<?php	
       	           	               			$email_api_access = $this->config->item('email_api_access');
       	           	               			if($email_api_access == '') $email_api_access='0';
       	           							echo form_dropdown('email_api_access',array('0'=>$this->lang->line('no'),'1'=>$this->lang->line('yes')),$email_api_access,'class="form-control" id="email_api_access"');  ?>		          
       	           	             			<span class="red"><?php echo form_error('email_api_access'); ?></span>
       	           	           			</div>
			        			</fieldset>
							</div>
						</div>

			        </div> <!-- /.box-body --> 
   					<div class="modal-footer" style="text-align:center;">
   						<button name="submit" type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> <?php echo $this->lang->line("Save");?></button>
	              		<button  type="button" class="btn btn-default btn-lg" onclick='goBack("setting/configuration",1)'><i class="fa fa-remove"></i> <?php echo $this->lang->line("Cancel");?></button>
   					</div>
   					</form>
   				</div>
   			</div>
   		</div>     	
   </section>
</section>




<!-- Old Section -->
<!-- <section class="content-header">
   <section class="content">
   		<div class="" id="modal-id">
   			<div class="modal-dialog" style="width: 100%;margin:0;">
   				<div class="modal-content" style="color: <?php echo $THEMECOLORCODE; ?>">
   					<div class="modal-header" >
   					   	<h4 class="modal-title"><i class="fa fa-cogs"></i> General Settings</h4>
   					 </div>
		       		
		    <form class="form-horizontal text-c" enctype="multipart/form-data" action="<?php echo site_url().'setting/edit_config';?>" method="POST">
		        <div class="modal-body">
		           	<div class="form-group">
		              	<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('Company Name'); ?> *
		              	</label>
		                	<div class="col-sm-9 col-md-6 col-lg-6">
		               			<input name="institute_name" value="<?php echo $this->config->item('institute_address1');?>"  class="form-control" type="text">		               
		             			<span class="red"><?php echo form_error('institute_name'); ?></span>
		             		</div>
		            </div>
		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="name"> <?php echo $this->lang->line('Company Address'); ?>
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="institute_address" value="<?php echo $this->config->item('institute_address2');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('institute_address'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('Company Email'); ?>
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="institute_email" value="<?php echo $this->config->item('institute_email');?>"  class="form-control" type="email">		          
	             			<span class="red"><?php echo form_error('institute_email'); ?></span>
	             		</div>
		           </div> 		           

		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('Product Name'); ?>
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="product_name" value="<?php echo $this->config->item('product_name');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('product_name'); ?></span>
	             		</div>
		           </div>  


		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('Company Phone / Mobile'); ?>  
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="institute_mobile" value="<?php echo $this->config->item('institute_mobile');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('institute_mobile'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group" >
		             	<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('Logo'); ?>
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
		           			<div class='text-center'  style="background: #357CA5;"><img class="img-responsive" src="<?php echo base_url().'assets/images/logo.png';?>" alt="Logo"/></div>
	               			<?php echo $this->lang->line("Max Dimension");?> : 600 x 300, <?php echo $this->lang->line("Max Size");?> : 200KB,  <?php echo $this->lang->line("Allowed Format");?> : png
	               			<input name="logo" class="form-control" type="file">		          
	             			<span class="red"> <?php echo $this->session->userdata('logo_error'); $this->session->unset_userdata('logo_error'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('Favicon'); ?> 
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	             			<div class='text-center'><img class="img-responsive" src="<?php echo base_url().'assets/images/favicon.png';?>" alt="Favicon"/></div>
	               			<?php echo $this->lang->line("Max Dimension");?> : 32 x 32, <?php echo $this->lang->line("Max Size");?> : 50KB, <?php echo $this->lang->line("Allowed Format");?> : png
	               			<input name="favicon"  class="form-control" type="file">		          
	             			<span class="red"><?php echo $this->session->userdata('favicon_error'); $this->session->unset_userdata('favicon_error'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("language");?>
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">	             			
	               			<?php
							$select_lan="english";
							if($this->config->item('language')!="") $select_lan=$this->config->item('language');
							echo form_dropdown('language',$language_info,$select_lan,'class="form-control" id="language"');  ?>		          
	             			<span class="red"><?php echo form_error('language'); ?></span>
	             		</div>
		           </div>


       	           <div class="form-group">
       	             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("theme");?>
       	             	</label>
                		<div class="col-sm-9 col-md-6 col-lg-6">	             			
			        			<?php 
   	               			$select_theme="skin-black-light";
   							if($this->config->item('theme')!="") $select_theme=$this->config->item('theme');
   							echo form_dropdown('theme',$themes,$select_theme,'class="form-control" id="theme"');  ?>	          
                			<span class="red"><?php echo form_error('theme'); ?></span>
                		</div>
       	           </div>

		           	<div class="form-group">
		             	<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('Time Zone'); ?> 
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">	             			
	               			<?php	$time_zone['']="Time Zone";
							echo form_dropdown('time_zone',$time_zone,$this->config->item('time_zone'),'class="form-control" id="time_zone"');  ?>		          
	             			<span class="red"><?php echo form_error('time_zone'); ?></span>
	             		</div>
		           	</div> 
		         		               
	           </div> 

	           	<div class="modal-footer">
	            	<div class="form-group">
	             		<div class="col-sm-12 text-center">
	               			<button name="submit" type="submit" class="btn btn-warning btn-lg"><i class="fa fa-save"></i> <?php echo $this->lang->line("Save");?></button>  
	              			<button  type="button" class="btn btn-default btn-lg" onclick='goBack("setting/configuration",1)'><i class="fa fa-remove"></i> <?php echo $this->lang->line("Cancel");?></button>
	             		</div>
	           		</div>
	         	</div>         
	        </div>      
		    </form>     
     	</div>
     </div>
 </div>
   </section>
</section> -->



