<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add Contact'); ?></h3>
			</div><!-- /.box-header -->
			<!-- form start -->
			<form class="form-horizontal" action="<?php echo site_url().'phonebook/add_contact_action';?>" enctype="multipart/form-data" method="POST">
				<div class="box-body">					

					<div class="form-group">
						<label class="col-sm-3 control-label" for="name"><?php echo $this->lang->line('First Name'); ?>
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="first_name" value="<?php echo set_value('first_name');?>"  class="form-control" type="text">		          
							<span class="red"><?php echo form_error('first_name'); ?></span>
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Last Name'); ?>  
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="last_name" value="<?php echo set_value('last_name');?>"  class="form-control" type="text">		          
							<span class="red"><?php echo form_error('last_name'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Date of Birth'); ?>
						</label>

						<div class="col-sm-9 col-md-6 col-lg-6">
		                    <input id="from_date"  value="<?php echo set_value('from_date');?>" name="from_date" class="form-control datepicker" size="22" placeholder="<?php echo $this->lang->line('mm/dd/yyyy');?>">
		                    <span class="red"><?php echo form_error('from_date'); ?></span>
		                </div>    
                	</div>

                	<!-- <div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Date of Meeting'); ?>
						</label>

						<div class="col-sm-9 col-md-6 col-lg-6">
		                    <input id="meeting_date"  value="<?php echo set_value('meeting_date');?>" name="meeting_date" class="form-control datepicker" size="22" placeholder="<?php echo $this->lang->line('mm/dd/yyyy');?>">
		                    <span class="red"><?php echo form_error('meeting_date'); ?></span>
		                </div>    
                	</div> -->

                	<!-- <div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line("Meeting Time") ?>
						</label>

						<div class="col-sm-9 col-md-6 col-lg-6">
		                    <input placeholder="<?php echo $this->lang->line("time");?>"  name="meeting_time" id="meeting_time" class="form-control datepicker_time" type="text"/>
		                    <span class="red"><?php echo form_error('meeting_time'); ?></span>
		                </div>    
                	</div> -->

                	<!-- <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
		            	<div class="row">
		            		<div class="schedule_block_item">
			            		<div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
		        			    	<div class="form-group">
		        						<label><i class="fa fa-clock-o"></i> <?php echo $this->lang->line("schedule time") ?></label>
		        						<input placeholder="<?php echo $this->lang->line("time");?>"  name="schedule_time" id="schedule_time" class="form-control datepicker_time" type="text"/>
		        			
		        					</div>
			            		</div>
			            		<div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
	        						<div class="form-group">				
	        			            	<label><i class="fa fa-bookmark"></i> <?php echo $this->lang->line('Time Zone'); ?> *</label>
	        							<?php
	        								$time_zone[''] = $this->lang->line("please select");
	        								echo form_dropdown('time_zone',$time_zone,$this->config->item('time_zone'),' class="form-control" id="time_zone" required');
	        							?>
	        			           </div>
			            		</div>
		            		</div>
		            	</div>
		            </div> -->

            

					

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Email'); ?> 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="email" value="<?php echo set_value('email');?>"  class="form-control" type="text">		               
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
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Mobile Number'); ?>
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="phone_number" value="<?php echo set_value('phone_number');?>"  class="form-control" type="text">		          
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
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('Contact Group'); ?> *
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">							
							<?php if(isset($group_checkbox)) echo $group_checkbox; ?>
							<span class="red">
							<?php 
								if($this->session->userdata('group_type_error')==1){
									echo '<b>'.$this->lang->line('Contact Group').'</b>'.str_replace("%s","", $this->lang->line("required"));
								$this->session->unset_userdata('group_type_error');
								}
							?>
							</span>
						</div>
					</div>	
				</div>
				<div class="box-footer">
					<div class="form-group">
						<div class="col-sm-12 text-center">
							
							<button name="submit" type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> <?php echo $this->lang->line('Save');?></button> 
							<button onclick='goBack("phonebook/contact_list")' type="button" class="btn btn-default btn-lg"><i class="fa fa-remove"></i> <?php echo $this->lang->line('Cancel');?></button>
							  
						</div>
					</div>
				</div><!-- /.box-footer -->         
			</div><!-- /.box-info -->       
		</form>     
	</div>
</section>
</section>

<script type="text/javascript">
	
	 $j(function() {
    $( ".datepicker" ).datepicker();

    var today = new Date();
	var next_date = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
	$j('.datepicker_time').datetimepicker({
		theme:'light',
		format:'Y-m-d H:i:s',
		formatDate:'Y-m-d H:i:s',
		minDate: today,
		maxDate: next_date
	})
  }); 
</script>