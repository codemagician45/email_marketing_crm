
<div class="container-fluid" style="padding: 20px !important;">
  <div class="box box-primary">
    <div class="box-header ui-sortable-handle" style="cursor: move;padding: 10px 20px !important;">
        <i class="fa fa-upload"></i>
        <h3 class="box-title"><?php echo $this->lang->line("Import Contact (CSV)");  ?></h3>
        <span class="pull-right">
          <a href="#" data-placement="bottom" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("CSV Sample"); ?>" data-content="<?php echo $this->lang->line("You can download our sample CSV file to get the idea about CSV format and open with text editor to view original formatting."); ?>"><i class='fa fa-info-circle'></i> </a>
          <a target="_BLANK" class="btn btn-sm btn-primary" href="<?php echo base_url("assets/sample/contact_import_sample.csv"); ?>"><i class="fa fa-cloud-download"></i> <?php echo $this->lang->line('Download Sample CSV'); ?></a>
        </span> 
        <div class="pull-right box-tools"></div>
    </div>
    <div class="box-body" style="padding: 20px !important;">
      <form action="" method="POST" id="csv_import_form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                   <label><?php echo $this->lang->line("Contact Group"); ?>
                     <a href="#" data-placement="top"  data-toggle="popover" title="<?php echo $this->lang->line("Contact Group"); ?>" data-content="<?php echo $this->lang->line("You have to select Contact Group in which group you want to add imported contacts."); ?>"><i class='fa fa-info-circle'></i> </a> 
                   </label>
                    <select multiple="multiple" class="form-control" id="contact_type" name="contact_type[]">
                     <?php 
                        foreach($contact_info as $key=>$value)
                        {
                          echo "<option value='{$key}'>{$value}</option>";
                        }

                      ?>
                    </select>
                     <span class="red contact_type_error"><?php echo form_error('contact_type'); ?></span>
                 </div>
            </div>  
            <div class="col-xs-6">
                <div class="form-group">
                  <label><?php echo $this->lang->line('Browse CSV'); ?>
                    <a href="#" data-placement="bottom"  data-toggle="popover" title="<?php echo $this->lang->line("Upload CSV"); ?>" data-content="<?php echo $this->lang->line("Upload your CSV file. You can see the original format of importing CSV file by downloading our Sample CSV file."); ?>"><i class='fa fa-info-circle'></i> </a>
                  </label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control" value="<?php echo set_value('csv_file'); ?>">
                    <span class="red csv_file_error"><?php echo form_error('csv_file'); ?></span>
                </div>
            </div>  
        </div><hr>
        
        <div class="col-xs-12">
            <div class="form-group">
              <div class="text-center">              
                <button name="submit" type="button" id="import_submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> <?php echo $this->lang->line('Save'); ?></button> 
                <button onclick='goBack("phonebook/contact_list",0)' type="button" class="btn btn-default btn-lg"><i class="fa fa-remove"></i> <?php echo $this->lang->line('Cancel'); ?></button>      
              </div>
            </div>
        </div>
      </form><br>

      <div class="col-xs-12">
          <div class="alert alert-success">
            <strong><?php echo $this->lang->line("If you used Microsoft Excel or any other spreadsheet program to fill up your contact CSV then please make sure the values were saved properly by opening the file with notepad or any other text editor. See the below image please."); ?></strong><br><br>
            <img src="<?php echo base_url("assets/sample/sample.png") ?>" alt="sample_image">
          </div>
      </div>
    </div>
  </div>
</div>

<script>
$j("document").ready(function(){

  // tooltip
  $('[data-toggle="popover"]').popover(); 
  $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

  $j("#contact_type").multipleSelect({
         filter: true,
         multiple: true
     });

	$("#import_submit").click(function(){ 

    var site_url="<?php echo site_url();?>";
    var contact_type=$("#contact_type").val();

    // contact group and manual number
    if(contact_type == null)
    {
      alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("you have not selected any group. Please select atleast one group."); ?>',function(){ });
      return false;

    }

    var fileval=$("#csv_file").val();
    if(fileval=="")  
      $(".csv_file_error").html("<?php echo $this->lang->line('You have not selected any contact.');?>");
    else $(".csv_file_error").html("");
  
    console.log(fileval);
    var queryString = new FormData($("#csv_import_form")[0]);

      $(this).html('<?php echo $this->lang->line("please wait"); ?>');
      $.ajax({
          url: site_url+'phonebook/import_contact_action_ajax',
          type: 'POST',
          data: queryString,
          dataType:'json',
          async: false,
          cache: false,
          contentType: false,
          processData: false,
          success: function (response)                
          {
          	 $("#import_submit").html('Import');         
          	 if(response.status=='ok')
          	 {    
                var total = response.count;
                alertify.alert('<?php echo $this->lang->line("Alert");?>',total+' '+'<?php echo $this->lang->line("contacts has been imported from csv was successfully"); ?>',function(){ 
                  var link="<?php echo site_url('phonebook/contact_list');?>";
                  window.location.assign(link);
                });
    		        
          	 }
          	 else
          	 {
          	 	 var error=response.status.replace(/<\/?[^>]+(>|$)/g, "");
               alertify.alert('<?php echo $this->lang->line("Error");?>',error,function(){ });
          	 }
          }
            
        });
              
         
  });	
		
});
</script>
