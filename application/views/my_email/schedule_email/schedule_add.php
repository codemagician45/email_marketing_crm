<?php $this->load->view("include/upload_js"); ?>

<style>
	.form-group{ margin-bottom: 20px; }
	.css-label{padding:12px 0px;background: #ccc;border-radius: 0px;text-align: center;}
	.css-label:hover{background: #ddd;cursor: pointer;}
	.double-label{min-width: 48.5%;}
	#cke_1_contents { height: 150px !important }
</style>


<div class="container-fluid" style="padding: 20px !important;">
	<div class="box box-primary">
	    <div class="box-header ui-sortable-handle" style="cursor: move;padding: 10px 20px !important;">
	        <i class="fa fa-envelope"></i>
	        <h3 class="box-title"><?php echo $this->lang->line("New Email Campaign");  ?></h3>
	        <div class="pull-right box-tools"></div>
	    </div>
	    <div class="box-body" style="padding: 20px !important;">
	        <div class="row">
	        	<form action="" id="email_campaign_form" method="POST">
		            <div class="col-sm-12 col-md-6 col-lg-6">
		               <div class="form-group">
		                    <label><i class="fa fa-bullhorn"></i> <?php echo $this->lang->line('Campaign Name'); ?> </label>
		               		<input id="schedule_name" name="schedule_name" value="<?php echo set_value('schedule_name');?>"  class="form-control" type="text">
		               		<span class="red"><?php echo form_error('schedule_name'); ?></span>
		                </div>

						<div class="form-group">
			             	<label><i class="fa fa-file-text"></i> <?php echo $this->lang->line('Email Subject'); ?>  </label>
			              	<input placeholder="<?php echo $this->lang->line('Email Subject'); ?>" id="subject" name="subject" type="text" class="form-control"/>
			               	<span class="red" id="subject_error"></span>
			           	</div>
		            </div>

		            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
	        			<div class="form-group">
                         	<label><i class="fa fa-plug"></i> <?php echo $this->lang->line('Send As'); ?> </label>
	        				<select  id='from_email' name="from_email" class='form-control'>
								<option value=''><?php echo $this->lang->line('Email API'); ?></option>
								<?php 
									foreach($email_option as $id=>$option)
									{
										echo "<option value='{$id}'>{$option}</option>";
									}
								?>
							</select>
            			</div>

 			            <div class="form-group">
 						  	<label><i class="fa fa-users"></i> <?php echo $this->lang->line('Select Contacts'); ?> </label>
 							<select multiple="multiple" class="form-control" id="contacts_id" name="contacts_id[]">
 								<?php
 									foreach($groups_name as $key=>$value)
 									{
 										echo "<option value='{$key}'>{$value}</option>";
 									}
 								 ?>				
 							</select>
 			         	</div>
		            </div>

					<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
        			    <div class="form-group">
        	             	<label><i class="fa fa-book"></i> <?php echo $this->lang->line('Email Message Template'); ?> 
								<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("SMS Template"); ?>" data-content="<?php echo $this->lang->line("You can use your saved Email template contents as message. Choose your Email template which you want to use. Email Template's Contents will appear in below Message field."); ?>"><i class='fa fa-info-circle'></i> </a> 
        	             	</label>
    					   	<select  id='message_template_schedule' class='form-control'>
    							<option value=''><?php echo $this->lang->line("I want to write new messsage, don't want any template");?></option>
    							<?php 
									foreach($email_template as $info)
									{
										$template_name=$info['template_name'];
										$id=$info['id'];
										echo "<option value='{$id}'>{$template_name}</option>";
									}
    							?>
    						</select>
        				</div>
        			    
		            	<div class="form-group">
	                    	<label><i class="fa fa-envelope"></i> <?php echo $this->lang->line('Message'); ?> 
	        					<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("include lead user first name"); ?>" data-content="<?php echo $this->lang->line("If you want to embed image into html template use Image Icon and paste image url, do not paste image in to the editor directly. You can also use varibales clicking editor's Varibale Icon but remeber that email with variables are quiet slower than simple emails."); ?>"><i class='fa fa-info-circle'></i> </a> 
	        				</label>                     		
                      		<textarea style='height: 120px;' placeholder="<?php echo $this->lang->line("If you don't want any template, type your custom message here");?>" id="message" name="message" class="form-control"></textarea>
	                       		<span class="red"><?php echo form_error('message'); ?></span>
	                   	</div>
		            </div>

		            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
		            	<div class="form-group">
		            	  	<label><i class="fa fa-image"></i> <?php echo $this->lang->line('Attachment'); ?> <?php echo $this->lang->line('(Max 20MB)');?>
		            	 		<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("include lead user first name"); ?>" data-content="<?php echo $this->lang->line("You can attach an attachment up to 20MB size. If you need multiple files to send, compress them in to a zip/rar file. Please remember that, you can not have both email with variables & attachment together.").' '.$this->lang->line("Allowed files are .png, .jpg,.jpeg, .docx, .txt, .pdf, .ppt, .zip, .avi, .mp4, .mkv, .wmv, .mp3"); ?>"><i class='fa fa-info-circle'></i> </a>

		            		</label>
		            	    <div id="fileuploader">Upload</div>
		            	    <span class="red" id="message_error"></span>
		            	</div>

		            	<div class="form-group">
		            	    <label><i class="fa fa-clock"></i> <?php echo $this->lang->line("Schedule"); ?></label><br/>
		            	    <input name="schedule_type" value="now" id="schedule_now" type="radio" style="display: none;"> 
		            	    <label for="schedule_now" class="css-label default-label double-label checked_schedule_time_button" id="now_label"> <i class="checkicon fa fa-check"></i> <?php echo $this->lang->line("Now") ?></label>
		            	    <input name="schedule_type" value="later" id="schedule_later" type="radio" style="display: none;"> 
		            	    <label for="schedule_later" class="css-label double-label schedule_time_button" id="later_label"> <i class="checkicon fa fa-check"></i> <?php echo $this->lang->line("later") ?>
		            	    </label>
		            	</div>
		            </div>

		            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
		            	<div class="row">
		            		<div class="schedule_block_item">
			            		<div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
		        			    	<div class="form-group">
		        						<label><i class="fa fa-clock-o"></i> <?php echo $this->lang->line("schedule time") ?></label>
		        						<input placeholder="<?php echo $this->lang->line("time");?>"  name="schedule_time" id="schedule_time" class="form-control datepicker" type="text"/>
		        			
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
		            </div>
		        </form>
	        </div><hr>

	        <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
	        	 <div class="">
	        	 	<button name="submit" type="button" id="submit_schedule" class="btn btn-primary btn-lg"><i class="fa fa-send"></i> <?php echo $this->lang->line('create campaign'); ?></button> 

	        	 	<button onclick='goBack("my_email/email_campaign",0)' type="button" class="btn btn-default btn-lg"><i class="fa fa-remove"></i> <?php echo $this->lang->line('Cancel'); ?></button>
	        	</div>
	        </div>
	          
	    </div>
	</div>
</div>

<script>
$j("document").ready(function(){

	var base_url="<?php echo site_url(); ?>";

	/*-----------  schedule button  ----------*/
	$(".schedule_block_item").hide();
	$(".checked_schedule_time_button").addClass('dynamic_color').css('color','#fff').siblings().children('.checkicon').hide();
	$(".schedule_time_button").css('color','#000');

	$(document.body).on('click', '#later_label', function() {
	    $("#later_label").removeClass('schedule_time_button');
	    $("#later_label").addClass('checked_schedule_time_button');
	    $("#now_label").addClass('schedule_time_button');
	    $("#now_label").removeClass('checked_schedule_time_button');
	});

	$(document.body).on('click', '#now_label', function() {    
	    $("#now_label").removeClass('schedule_time_button');
	    $("#now_label").addClass('checked_schedule_time_button');
	    $("#later_label").addClass('schedule_time_button');
	    $("#later_label").removeClass('checked_schedule_time_button');
	});


	$(document.body).on('click','.css-label',function(){
	    if($(this).hasClass('dynamic_color')) return false;
	    $(this).siblings().removeClass('dynamic_color').css('color',"#000");
	    $(this).addClass('dynamic_color').css('color',"#fff");
	    $(this).siblings().children('.checkicon').hide();
	    $(this).children('.checkicon').toggle();
	});

	$(document.body).on('change','input[name=schedule_type]',function(){
	    if($("input[name=schedule_type]:checked").val()=="later"){
	        $(".schedule_block_item").show();
	    }
	    else
	    {
	        $("#schedule_time").val("");
	        $("#time_zone").val("");
	        $(".schedule_block_item").hide();
	    }
	});
	/* schedule button block end */

	// datepicker
	var today = new Date();
	var next_date = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
	$j('.datepicker').datetimepicker({
		theme:'light',
		format:'Y-m-d H:i:s',
		formatDate:'Y-m-d H:i:s',
		minDate: today,
		maxDate: next_date
	})

	// tooltip
	$('[data-toggle="popover"]').popover(); 
	$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

	$j("#contacts_id").multipleSelect({
        filter: true,
        multiple: true
    });

	$('#modal_email_send_waiting').on('hidden.bs.modal', function () {
		var link="<?php echo site_url('my_email/send_email'); ?>";
		window.location.assign(link);
    })

	// upload image attachment
   	$("#fileuploader").uploadFile({
		url:base_url+"my_email/upload_attachment_scheduler",
		fileName:"myfile",
		maxFileSize:20*1024*1024,
		showPreview:false,
		returnType: "json",
		dragDrop: true,
		showDelete: true,
		multiple:false,
		acceptFiles:".png,.jpg,.jpeg,docx,.txt,.pdf,.ppt,.zip,.avi,.mp4,.mkv,.wmv,.mp3",
		maxFileCount:1,
		deleteCallback: function (data, pd) {
			var delete_url="<?php echo site_url('my_email/delete_attachment_scheduler');?>";
		    for (var i = 0; i < data.length; i++) {
		        $.post(delete_url, {op: "delete",name: data[i]},
		            function (resp,textStatus, jqXHR) {                
		            });
		    }
	  	}
	});

	// submit email campaign post		
	$("#submit_schedule").click(function(){
		var schedule_name = $("#schedule_name").val();
		var subject       = $("#subject").val();			
		var message       = CKEDITOR.instances.message.getData();
		var contacts_id	  = $("#contacts_id").val();
		// var to_email      = $("#to_emails").val();
		var from_email	  = $("#from_email").val();
		var schedule_type = $("input[name=schedule_type]:checked").val();
		var schedule_time = $("#schedule_time").val();
		var time_zone 	  = $("#time_zone").val();

		// campaign name
		if(schedule_name =='')
		{
			alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("Please give a campaign name"); ?>',function(){ });
			return false;
		}

		// contact group and manual number
		if(contacts_id == null)
		{
			alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("you have not selected any contact group to send"); ?>',function(){ });
			return false;

		}

		// email api select
		if(from_email =='')
		{
			alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("Please select an Email API"); ?>',function(){ });
			return false;
		}

		// campaign name
		if(subject =='')
		{
			alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("Please give email subject"); ?>',function(){ });
			return false;
		}

		// write message
		if(message =='')
		{
			alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("Please write your message"); ?>',function(){ });
			return false;
		}

		// if schedule is later
		if(schedule_type=='later' && (schedule_time=="" || time_zone==""))
    	{
    		alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("Please select schedule time/time zone."); ?>',function(){ });
    		return;
    	}

		$.ajax({
			url:base_url+'my_email/add_schedule_action',
			type:'POST',
			data:{
				schedule_name:schedule_name,
				subject:subject,
				message:message,
				contacts_id:contacts_id,
				// to_email:to_email,
				from_email:from_email,
				schedule_time:schedule_time,
				time_zone:time_zone,
			},
			success:function(respose){
				window.location = base_url+"my_email/email_campaign";
			}
		});
	
	});
			
	CKEDITOR.replace('message');
	$("#message_template_schedule").change(function(){
		var template_id = $(this).val();
		$.ajax({
			url:base_url+'my_email/load_template',
			type:'POST',
			dataType: 'JSON',
			data:{template_id:template_id},
			success:function(response){
				CKEDITOR.instances['message'].setData(response.message);
			}
		});
		
	});
		
		
		
});
</script>
