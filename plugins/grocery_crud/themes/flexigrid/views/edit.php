<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
	$CI2 = &get_instance();	// alamin
?>
<!-- Addded by Al-amin -->
<style type="text/css">
.form-field-box.odd,.form-field-box.even
{
	background: #fff;
	margin: 0;
}
select
{
	width: 100% !important;
	background:#fff !important;
	border:1px solid #ccc !important;
	height:34px !important;
}
input[type=text],input[type=password],input[type=email],input[type=file],textarea
{
	width: 100% !important;
	height:34px;
	/* background:#fff !important; */
	border:1px solid #ccc !important;
}
input[type=text]:focus,input[type=password]:focus,input[type=email]:focus,input[type=file]:focus,textarea:focus,select:focus
{
	border:1px solid #3C8DBC !important;
}
.form-div
{
	padding-top:25px !important;
	background:#f7f7f7 !important;
}
.form-div .control-label
{
	color:<?php echo $CI2->session->userdata('THEMECOLORCODE');?> !important;
	font-size:14px !important;
	font-weight: normal;
}
@media (max-width:767px)
{
	.custom_box{padding:10px !important;}
	input[type=text],input[type=password],input[type=email],input[type=file],textarea
	{
		width: 96% !important;
	}
	select
	{
		width: 96% !important;
	}
	.form-div
	{
		padding-left:15px !important;
	}	
}

.flexigrid .crud-form .mDiv
{
  color:<?php echo $CI2->session->userdata('THEMECOLORCODE');?> !important;
}
</style>
<!-- Addded by Al-amin -->
<div class="flexigrid crud-form" style='padding-top: 1px!important; padding-left:15px !important;padding-right: 15px !important;width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="">
		<div class="modal-dialog" style="width: 100%;margin-left:0 !important;margin-right:0; !important;">
			<div class="modal-content" style="color:<?php echo $CI2->session->userdata('THEMECOLORCODE');?> !important;">
				<div class="modal-header">
					<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
					<h4 class="modal-title"><i class="fa fa-edit"></i> <?php echo $this->l('form_edit'); ?> - <?php echo $subject?></h4>
				</div>
				<div class="modal-body">
					<?php echo form_open( $update_url, 'method="post" id="crudForm" class="form-horizontal" autocomplete="off" enctype="multipart/form-data"'); ?>
						<div class='form-div'>
								<?php
								$counter = 0;
									foreach($fields as $field)
									{
										$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
										$counter++;
								?>
								<div class='form-group <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
									<label class="col-sm-3 control-label" for="name" id="<?php echo $field->field_name; ?>_display_as_box">				
										<?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> 
									</label>
									<div class="col-sm-9 col-md-6 col-lg-6" id="<?php echo $field->field_name; ?>_input_box">
										<?php echo $input_fields[$field->field_name]->input?>
									</div>
									<div class='clear'></div>
								</div>			
							<?php }?>
							<div id="text_count"></div>
							<?php if(!empty($hidden_fields)){?>
							<!-- Start of hidden inputs -->
								<?php
									foreach($hidden_fields as $hidden_field){
										echo $hidden_field->input;
									}
								?>
							<!-- End of hidden inputs -->
							<?php }?>
							<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
							<div id='report-error' class='report-div error red text-center'></div>
							<div id='report-success' class='report-div success green text-center'></div>
						</div>
						<div class="pDiv">
							<!-- <div class='form-button-box'>
								<input id="form-button-save" type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-large btn-info"/>
							</div> -->
							<center>
							
							</center>
							<div class='clear'></div>
						</div>

				</div>
				<div class="modal-footer" style="text-align:center !important;">
					<?php  if(!$this->unset_back_to_list) { ?>
					
						<!-- <input type='button' value='<?php echo $this->l('form_save'); ?>' id="save-and-go-back-button"  class="btn btn-primary btn-lg"/> -->
						<!-- <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-lg btn-default" id="cancel-button" /> -->
						<button id="save-and-go-back-button" type="button" class="btn btn-lg btn-primary"/><i class='fa fa-save'></i> <?php echo $this->l('form_save'); ?></button>
						<button class="btn btn-lg btn-default" type="button" id="cancel-button" /><i class='fa fa-remove'></i> <?php echo $this->l('form_cancel'); ?></button>		
					<?php 	} ?>			
					<span class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></span>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";

	$(document).ready(function($) {

		// tooltip
		$('[data-toggle="popover"]').popover(); 
		$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

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
		
	});
</script>