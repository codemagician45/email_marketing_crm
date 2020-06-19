<div class="row padding-20">
	<div class="col-xs-12 col-md-12 padding-10">
		<div class="box box-primary">
			<div class="box-header ui-sortable-handle  text-center" style="cursor: move;margin-bottom: 0px;">
				<i class="fa fa-edit"></i>
				<h3 class="box-title"><?php echo $this->lang->line("edit").' '.$this->lang->line("message") ?></h3>
				<!-- tools box -->
				<div class="pull-right box-tools"></div>
			</div>

			<div class="box-body">
				<img class="wait_few_seconds center-block" src="<?php echo base_url("assets/pre-loader/Fading squares2.gif");?>" alt="">
				<form action="#" enctype="multipart/form-data" id="email_campaign_form" method="post">

					<input type="hidden" value="<?php echo $xdata[0]["id"];?>" class="form-control"  name="campaign_id" id="campaign_id">
					<div class="form-group">
						<label>
							<?php echo $this->lang->line("message") ?> *
							<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name"); ?>" data-content="<?php echo $this->lang->line("If you want to embed image into html template use Image Icon and paste image url, do not paste image in to the editor directly. You can also use varibales clicking editor's Varibale Icon but remeber that email with variables are quiet slower than simple emails."); ?>"><i class='fa fa-info-circle'></i> </a> 
						</label>

						<div class="clearfix"></div>
						<textarea class="form-control" name="message" id="message" placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"><?php echo $xdata[0]["email_message"];?></textarea>
					</div>
					
					<div class="alert alert-danger text-center" id="alert_div" style="display: none; font-size: 600;"></div>
					
					<div class="clearfix"></div>
					<div class="box-footer clearfix" style="border:none !important;">
						<button style='width:100%;margin-bottom:10px;' class="btn btn-primary center-block btn-lg" id="submit_post" name="submit_post" type="button"><i class="fa fa-edit"></i> <?php echo $this->lang->line("edit") ?>  <?php echo $this->lang->line("message") ?> </button>
					</div>					
				</form>
			</div>
			
		</div>
	</div>
</div>

<?php 
	$pleaypessagsteurlideourlystemcannotendlanessage = $this->lang->line("Please type a message. System can not send blank message.");
	$campaignhavebeenupdatedsuccessfully = $this->lang->line("campaign have been updated successfully.");
	$seereport = $this->lang->line("see report");


 ?>

<script>

 
	$j("document").ready(function(){

	
		var base_url="<?php echo base_url();?>";

		CKEDITOR.replace('message');

		// hiding the preloading image section
		setTimeout(function() {
			$(".loading").hide();
			$(".wait_few_seconds").hide();	
		}, 3000);

		// tooltip loading
		$('[data-toggle="popover"]').popover(); 
		$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

		$(".overlay").hide();

		// submitting the edited message
	    $(document.body).on('click','#submit_post',function(){ 
       		var pleaypessagsteurlideourlystemcannotendlanessage = "<?php echo $pleaypessagsteurlideourlystemcannotendlanessage; ?>";
       		var seereport = "<?php echo $seereport; ?>";
       		var message       = CKEDITOR.instances.message.getData();
       		var id = $("#campaign_id").val();
                  	
    		if(message =="")
    		{
    			alertify.alert('<?php echo $this->lang->line("Alert");?>',pleaypessagsteurlideourlystemcannotendlanessage,function(){ });
    			return;
    		}    
      
        	$("#response_modal_content").removeClass("alert-danger");
        	$("#response_modal_content").removeClass("alert-success");

        	var loading = '<img src="'+base_url+'assets/pre-loader/Fading squares2.gif" class="center-block">';
        	$("#response_modal_content").html(loading);

        	var report_link = base_url+"my_email/email_campaign";
        	
        	var success_message = "<i class='fa fa-check-circle'></i> <?php echo $this->lang->line('Campaign have been submitted successfully.'); ?> <a href='"+report_link+"'><?php echo $this->lang->line('See report'); ?></a>";

        	$("#response_modal_content").removeClass("alert-danger");
         	$("#response_modal_content").addClass("alert-success");
         	$("#response_modal_content").html(success_message);
       	        	
	      	$.ajax({
		       type:'POST' ,
		       url: base_url+"my_email/edit_message_content_action",
		       data:{id:id,message:message},
		       success:function(response)
		       {  
		       }
		    });
		    $("#response_modal").modal();
		    $(this).addClass("disabled");

        });
    });

</script>



<div class="modal fade" id="response_modal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-center"><?php echo $this->lang->line("campaign status") ?></h4>
			</div>
			<div class="modal-body">
				<div class="alert text-center" id="response_modal_content">
					
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view("my_sms/schedule_sms/style");?>