<style>
	/*.dynamic_color { background:  }*/
	.form-group{ margin-bottom: 20px; }
	.css-label{padding:8px 0px;background: #ccc;border-radius: 0px;text-align: center;}
	.css-label:hover{background: #ddd;cursor: pointer;}
	.double-label{min-width: 48.5%;}
	.input-group-btn:last-child>.btn, .input-group-btn:last-child>.btn-group {
	    z-index: 2;
	    margin-left: -1px;
	}
	.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc !important;
	}

	.dropdown-toggle {
	    border: solid 1px #ccc !important;
	}

	#item-1::before {
		content: "\f055";
	}

</style>


<div class="container-fluid" style="padding: 20px !important;">
	<div class="box box-primary">
	    <div class="box-header ui-sortable-handle" style="cursor: move;padding: 10px 20px !important;">
	        <i class="fa fa-send"></i>
	        <h3 class="box-title"><?php echo $this->lang->line("New SMS Campaign");  ?></h3>
	        <!-- tools box -->
	        <div class="pull-right box-tools"></div><!-- /. tools -->
	    </div>
	    <div class="box-body" style="padding: 20px !important;">
	        <div class="row">
	        	<form action="" id="sms_campaign_form" method="POST">
		            <div class="col-sm-12 col-md-6 col-lg-6">
		               <div class="form-group">
		                    <label><i class="fa fa-bullhorn"></i> <?php echo $this->lang->line('Campaign Name'); ?> </label>
		               		<input id="schedule_name" name="schedule_name" value="<?php echo set_value('schedule_name');?>"  class="form-control" type="text">
		               		<span class="red"><?php echo form_error('schedule_name'); ?></span>
		                </div>
                     	
		            </div>

		            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
	        			<div class="form-group">
                         	<label><i class="fa fa-plug"></i> <?php echo $this->lang->line('Send As'); ?> </label>
	        				<select name='from_sms' id='from_sms' class='form-control'>
	        					<option value=''><?php echo $this->lang->line('SMS API');?></option>
	        					<?php 
	        						foreach($sms_option as $id=>$option)
	        						{
	        							echo "<option value='{$id}'>{$option}</option>";
	        						}
	        					?>
	        				</select>
            			</div>

		            </div>

		            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
        			    <div class="form-group">
        	             	<label><i class="fa fa-book"></i> <?php echo $this->lang->line('Message Template'); ?> 
								<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("SMS Template"); ?>" data-content="<?php echo $this->lang->line("You can use your saved SMS template contents as message. Choose your SMS template which you want to use. SMS Template's Contents will appear in below Message field."); ?>"><i class='fa fa-info-circle'></i> </a> 
        	             	</label>
    					   	<select  id='message_template_schedule' class='form-control'>
    							<option value=''><?php echo $this->lang->line("I want to write new messsage, don't want any template");?></option>
    							<?php 
    								foreach($sms_template as $info){
    									$template_name=$info['template_name'];
    									$id = $info['id'];
    									echo "<option value='{$id}'>{$template_name}</option>";
    								}

    							?>
    						</select>
        				</div>

		            	<div class="form-group">
	                    	<label><i class="fa fa-envelope"></i> <?php echo $this->lang->line('Message'); ?> 
	        					<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("include lead user first name"); ?>" data-content="<?php echo $this->lang->line("You can include #CONTACT_FIRST_NAME#, #CONTACT_LAST_NAME#, #CONTACT_MOBILE_NUMBER#, #CONTACT_EMAIL_ADDRESS# as variable inside your message. The variable will be replaced by corresponding real values when we will send it."); ?>"><i class='fa fa-info-circle'></i> </a> 
	        				</label>
							<lable class="pull-right">
								<a title="<?php echo $this->lang->line("include contact first name"); ?>" class='btn btn-default btn-sm' id="contact_first_name"><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>&nbsp;&nbsp;

								<a title="<?php echo $this->lang->line("include contact last name"); ?>" class='btn btn-default btn-sm' id="contact_last_name"><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>&nbsp;&nbsp;

								<a title="<?php echo $this->lang->line("include contact mobile number"); ?>" class='btn btn-default btn-sm' id="contact_mobile_number"><i class='fa fa-phone-square'></i> <?php echo $this->lang->line("mobile") ?></a>&nbsp;&nbsp;
								
								<a title="<?php echo $this->lang->line("include contact email address"); ?>" class='btn btn-default btn-sm' id="contact_email_address"><i class='fa fa-envelope'></i> <?php echo $this->lang->line("email") ?></a>
							</lable>
                     		
                      		<textarea style='height:120px' placeholder="<?php echo $this->lang->line("If you don't want any template, type your custom message here");?>" id="message" name="message" class="form-control"></textarea>
	                       		<span class="red"><?php echo form_error('schedule_name'); ?></span>
	                   	</div>
		            </div>

		            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
    		            <div class="form-group">
    					  	<label><i class="fa fa-users"></i> <?php echo $this->lang->line('Select Contacts'); ?> </label>
    						<select multiple="multiple"  class="form-control" id="contacts_id" name="contacts_id[]">
    							<?php
    								foreach($groups_name as $key=>$value)
    								{
    									echo "<option value='{$key}'>{$value}</option>";
    								}
    							 ?>					
    						</select>
    		         	</div>


 	    			    <div class="form-group">
 	    		            <label><i class="fa fa-phone-square"></i> <?php echo $this->lang->line('Numbers To Send');?> 
								<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("include lead user first name"); ?>" data-content="<?php echo $this->lang->line("Beside contact groups If you also want to send messages to manual numbers, you can simply put your numbers in below field with comma separated. System will send message to both your contact numbers and also to your manual numbers."); ?>"><i class='fa fa-info-circle'></i> </a>
 	    		            </label>
 	    		            <span class="pull-right">
 	    		            	<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("include lead user first name"); ?>" data-content="<?php echo $this->lang->line("If you want to upload numbers from your CSV file, you can upload your CSV file. You will see your uploaded files number at the below box."); ?>"><i class='fa fa-info-circle'></i> </a>
 	    		            	<a style="border-radius: 0" id="import_from_csv" data-toggle="modal" href='#csv_import_modal' class="btn btn-outline-primary btn-sm"><i class="fa fa-upload"></i> <?php echo $this->lang->line('Upload CSV');?></a>
 	    		            </span>
 			            	<textarea style='height:120px' placeholder="<?php echo $this->lang->line('placeholder_1_sms') ;?>" id="to_numbers" name="to_numbers" class="form-control"></textarea>
 	    	            </div>
		            </div>


		            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
						<div class="form-group">
						    <label><i class="fa fa-clock"></i> <?php echo $this->lang->line("Schedule"); ?></label><br/>
						    <input name="schedule_type" value="now" id="schedule_now" type="radio" style="display: none;"> 
						    <label for="schedule_now" class="css-label default-label double-label checked_schedule_time_button" id="now_label"> <i class="checkicon fa fa-check"></i> <?php echo $this->lang->line("Now") ?>
						    </label>
						    <input name="schedule_type" value="later" id="schedule_later" type="radio" style="display: none;"> 
						    <label for="schedule_later" class="css-label double-label schedule_time_button" id="later_label"> <i class="checkicon fa fa-check"></i> <?php echo $this->lang->line("later") ?>
						    </label>
						</div>
		            </div>
		            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
		            	<label><i class="fa fa-qrcode"></i> <?php echo $this->lang->line('Country Code'); ?>
		            		<a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("Add or remove country code"); ?>" data-content="<?php echo $this->lang->line("If you want to add your country code to your contact numbers, then simply put you contry code here, then select add option from dropdown. Country Code will be added into your every contact number. You can also remove country code by selecting remove option from dropdown menu."); ?>"><i class='fa fa-info-circle'></i> </a>
		            	</label>
		            	<div class="input-group">
			            	<input type="text" class="form-control" id="country_code" name="country_code" placeholder="country code">
			            	<div class="input-group-btn">
				            	<select name="country_code_action" id="country_code_action" class="btn btn-default dropdown-toggle">
				            		<option value="">Action</option>
				            		<option value="1" id="item-1"><?php echo $this->lang->line("Add"); ?></option>
				            		<option value="0" id="item-2"><?php echo $this->lang->line("Remove"); ?></option>
				            	</select>	
			            	</div>
		            	</div>
		            </div>
		            <input type="hidden" value="" id="country_code_add" name="country_code_add">
		            <input type="hidden" value="" id="country_code_remove" name="country_code_remove">

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
		            <div id="text_count" class="col-xs-12"></div>
	        </div><hr>

	        <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
	        	 <div class="">
	        	 	<button name="submit" type="button" id="submit_schedule" class="btn btn-primary btn-lg"><i class="fa fa-send"></i> <?php echo $this->lang->line('create campaign'); ?></button> 

	        	 	<button onclick='goBack("my_sms/sms_campaign",0)' type="button" class="btn btn-default btn-lg"><i class="fa fa-remove"></i> <?php echo $this->lang->line('Cancel'); ?></button>
	        	</div>
	        </div>
	          
	    </div>
	</div>
</div>


<!-- CSV file upload modal -->
<div class="modal fade" id="csv_import_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-file-text"></i> <?php echo $this->lang->line('Import numbers from CSV'); ?></h4>
			</div>
			<div class="modal-body">
				<form action="" id="csv_import_form" method="POST" enctype="multipart/form-data">
					<?php echo $this->lang->line('Browse CSV');?> <input type="file" name="csv_file" id="csv_file"/>
					<input type="hidden" id="hidden_import_type" name="hidden_import_type" value="sms"/>
				</form>
				
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $this->lang->line('Cancel');?></button>
				<button type="button" id="import_submit" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $this->lang->line('Import');?></button>
			</div>
		</div>
	</div>
</div>

<script>
$j("document").ready(function(){

	/*-----------  schedule button  ----------*/
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

	$(document.body).on('change', '#country_code_action', function(event) {
		event.preventDefault();
		/* Act on the event */
		var action = $(this).val();
		var country_code = $("#country_code").val();

		console.log(country_code);

		if(action == "1")
		{
			$("#country_code_add").val(country_code);
			if($("#country_code_remove").val() !='')
			{
				$("#country_code_remove").val("");
			}
		}
		if(action=='0')
		{
			$("#country_code_remove").val(country_code);
			if($("#country_code_add").val() !='')
			{
				$("#country_code_add").val("");
			}
		}

	});
	

	// tooltip
	$('[data-toggle="popover"]').popover(); 
	$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

	$(".schedule_block_item").hide();

	var today = new Date();
	var next_date = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
	$j('.datepicker').datetimepicker({
		theme:'light',
		format:'Y-m-d H:i:s',
		formatDate:'Y-m-d H:i:s',
		minDate: today,
		maxDate: next_date
	})	


	var base_url="<?php echo base_url(); ?>";
	
	 	$j("#contacts_id").multipleSelect({
            filter: true,
            multiple: true
        });
		
		
		$("#submit_schedule").click(function(){

			var schedule_name = $("#schedule_name").val();
			var message       = $("#message").val();
			var contacts_id	  = $("#contacts_id").val();
			var to_numbers    = $("#to_numbers").val().trim();
			var from_sms 	  = $("#from_sms").val();
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
			if(contacts_id == null && to_numbers =='')
			{
				alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("you have not given any numbers"); ?>',function(){ });
				return false;

			}

			// sms api select
			if(from_sms =='')
			{
				alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("Please select a SMS API"); ?>',function(){ });
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

        	var queryString = new FormData($("#sms_campaign_form")[0]);
			
			$.ajax({
				url:base_url+'my_sms/add_schedule_action',
				type:'POST',
				data: queryString,
				cache: false,
				contentType: false,
				processData: false,
				success:function(respose)
				{
					window.location=base_url+"my_sms/sms_campaign";
				}
			});
		
		});

		/** Including variables on click **/
        $(document.body).on('click','#contact_first_name',function(){ 
 			var $txt = $("#message");
         	var caretPos = $txt[0].selectionStart;

	        var textAreaTxt = $txt.val();
	        var txtToAdd = " #FIRST_NAME# ";
	        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		});

		$(document.body).on('click','#contact_last_name',function(){ 
 			var $txt = $("#message");
         	var caretPos = $txt[0].selectionStart;
	        var textAreaTxt = $txt.val();
	        var txtToAdd = " #LAST_NAME# ";
	        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		});

		$(document.body).on('click','#contact_mobile_number',function(){  
 			var $txt = $("#message");
         	var caretPos = $txt[0].selectionStart;
	        var textAreaTxt = $txt.val();
	        var txtToAdd = " #MOBILE# ";
	        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		});

		$(document.body).on('click','#contact_email_address',function(){  
 			var $txt = $("#message");
         	var caretPos = $txt[0].selectionStart;
	        var textAreaTxt = $txt.val();
	        var txtToAdd = " #EMAIL_ADDRESS# ";
	        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		});
		/** End of Including variables by click **/


		// import csv section
      	$("#import_submit").click(function(){    
		      
		    var site_url="<?php echo site_url();?>";    
		 
		    var queryString = new FormData($("#csv_import_form")[0]);
		    var fileval=$("#csv_file").val();
		    if(fileval=="")
		    	alertify.alert('<?php echo $this->lang->line("Error");?>','<?php echo $this->lang->line("No file selected, Please upload a file.");?>',function(){ });
		    else
		    {  
		        
		    	$(this).html('<?php echo $this->lang->line("please wait");?>');
		      	$.ajax({
					url: site_url+'my_sms/csv_upload',
					type: 'POST',
					data: queryString,
					dataType:'json',
					async: false,
					cache: false,
					contentType: false,
					processData: false,
		          	success: function (response)                
		          	{
		          		$("#import_submit").html('<?php echo $this->lang->line("import");?>');        
						if(response.status=='ok')
						{          	 	
							var file_content=response.file;
							var to_numbers=$("#to_numbers").val().trim();
							if(to_numbers!="") file_content=','+file_content; 	
							file_content=to_numbers+file_content;
							$("#to_numbers").val(file_content);
							$("#csv_import_modal").modal('hide');
							alertify.success('<?php echo $this->lang->line("import from csv was successful")?>');
						}
						else
						{
							var error=response.status.replace(/<\/?[^>]+(>|$)/g, "");
							alertify.alert('<?php echo $this->lang->line("Error");?>',error,function(){ });
						}
		          	}
		            
		        });
		    }         
		         
		});
			

		$("#message_template_schedule").change(function(){
			var template_id = $(this).val();
			$.ajax({
				url:base_url+'my_sms/load_template',
				type:'POST',
				dataType: 'JSON',
				data:{template_id:template_id},
				success:function(response){
					$("#message").val(response.message);
				}
			});
		});


		$(document.body).on('click', '#add_code', function(event) {
			event.preventDefault();

			var contacts_id	  = $("#contacts_id").val();
			var to_numbers    = $("#to_numbers").val().trim();
			if(contacts_id == null && to_numbers =='')
			{
				alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("you have not selected any contact groups or have not given any numbers"); ?>',function(){ });
				return false;

			}

		});

		$(document.body).on('click', '#rmv_code', function(event) {
			event.preventDefault();

			var contacts_id	  = $("#contacts_id").val();
			var to_numbers    = $("#to_numbers").val().trim();
			if(contacts_id == null && to_numbers =='')
			{
				alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("you have not selected any contact groups or have not given any numbers"); ?>',function(){ });
				return false;

			}

		});


		$(document.body).on('change','input[name=schedule_type]',function(){    
			if($("input[name=schedule_type]:checked").val()=="later")
				$(".schedule_block_item").show();
			else 
			{
				$("#schedule_time").val("");
				$("#time_zone").val("");
				$(".schedule_block_item").hide();
			}
		}); 
		
		
});
</script>
