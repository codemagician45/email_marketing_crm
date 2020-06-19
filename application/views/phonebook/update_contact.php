<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('Edit Contact'); ?></h3>
			</div><!-- /.box-header -->
			<!-- form start -->
			<form class="form-horizontal" action="<?php echo site_url().'phonebook/update_contact_action/'.$info['id'];?>" enctype="multipart/form-data" method="POST">
				<div class="box-body">

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('First Name'); ?> 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="first_name" value="<?php if(set_value('first_name'))echo set_value('first_name');else{if(isset($info['first_name']))echo $info['first_name'];}?>"  class="form-control" type="text">		          
							<span class="red"><?php echo form_error('first_name'); ?></span>
						</div>
					</div> 

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Last Name'); ?>  
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="last_name" value="<?php if(set_value('last_name'))echo set_value('last_name');else {if(isset($info['last_name']))echo $info['last_name'];}?>"  class="form-control" type="text">		          
							<span class="red"><?php echo form_error('last_name'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Mobile Number'); ?> *
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="phone_number" value="<?php if(set_value('phone_number'))echo set_value('phone_number');else{if(isset($info['phone_number']))echo $info['phone_number'];}?>"  class="form-control" type="text">		          
							<span class="red">
								<?php 
									if($this->session->userdata('phone_number_email_error')==1)
									{
										echo "<b>".$this->lang->line("Please provide a mobile number or an email address.")."</b>";
										$this->session->unset_userdata('phone_number_email_error');
									}
								?>
							</span>
						</div>
					</div> 

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Email'); ?> *
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="email" value="<?php if(isset($info['email'])) echo $info['email']; else echo set_value('email');?>"  class="form-control" type="text">		               
							<span class="red">
								<?php 
									if($this->session->userdata('phone_number_email_error')==1)
									{
										echo "<b>".$this->lang->line("Please provide a mobile number or an email address.")."</b>";
										$this->session->unset_userdata('phone_number_email_error');
									}
								?>
							</span>
						</div>
					</div>				

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Date of Birth'); ?> 
						</label>

						<div class="col-sm-9 col-md-6 col-lg-6">
							<?php $info['date_birth']=date("m/d/Y",strtotime($info['date_birth'])); ?>
		                    <input id="from_date" name="from_date" class="form-control datepicker" size="22" placeholder="Date Format (MM/DD/YYYY)"   value="<?php if(set_value('date_birth'))echo set_value('date_birth');else{if(isset($info['date_birth']))echo $info['date_birth'];}?>">
		                    <span class="red"><?php echo form_error('from_date'); ?></span>
		                </div>    
                	</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Contact Group'); ?> *
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">							
							<?php if(isset($group_checkbox)) echo $group_checkbox; ?>
							<span class="red"><?php echo $this->session->flashdata("reset_success").'</div>';; ?></span>
						</div>
					</div>						

	            </div>
	            <div class="box-footer">
	             	<div class="form-group">
	             		<div class="col-sm-12 text-center">
	             			<input name="submit" type="submit" class="btn btn-primary btn-lg" value="<?php echo $this->lang->line('Update'); ?>"/>  
	             			<input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line('Cancel'); ?>" onclick='goBack("phonebook/contact_list",0)'/>  
	             		</div>
	             	</div>
	            </div>        
		         </div>      
		     </form>     
		 </div>
		</section>
	</section>

<script type="text/javascript">
	
	 $j(function() {
    $( ".datepicker" ).datepicker();
  }); 
</script>


