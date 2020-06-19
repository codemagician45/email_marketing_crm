<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $this->config->item('product_short_name')." | ".$page_title;?></title>
    <?php $this->load->view('include/css_include_back');?>
	  <?php $this->load->view('include/js_include_back');?>
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png">   
  </head>
  <body class="<?php echo $loadthemebody;?> sidebar-mini">
    <div class="wrapper">

      <?php $this->load->view('admin/theme/header');?>

      <!-- for RTL support -->
      <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.2.0-rc2/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" /> -->
      <!-- <link href="<?php echo base_url();?>css/rtl.css" rel="stylesheet" type="text/css" /> -->
       

      <!-- Left side column. contains the logo and sidebar -->
      <?php $this->load->view('admin/theme/sidebar'); ?>

      <!-- Content Wrapper. Contains page content --> 
      <div class="content-wrapper" >
      <?php 
        if(($this->uri->segment(2)=="sms_api" && $this->uri->segment(3)=="add") || ($this->uri->segment(2)=="sms_api" && $this->uri->segment(3)=="edit"))
        { ?>
          <div>
            <!-- Button trigger modal -->
             <br/>
             <center><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#api_instruction_modal">
              <i class="fa fa-book"></i> <?php echo $this->lang->line('Instructions to configure SMS API'); ?>
             </button></center>

            <!-- Modal -->
           
            <div class="modal fade" id="api_instruction_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Instructions to configure SMS API'); ?></h4>
                  </div>
                  <div class="modal-body">

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : Planet IT</h4> <br>
                      <p>Required Fields : Username, Password, Sender</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : Twilio</h4> <br>
                      <p>Required Fields : Account Sid, Auth Token, From</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : Plivo</h4> <br>
                      <p>Required Fields : Auth ID, Auth Token, Sender</p> 
                    </div>

                     <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : Clickatell</h4> <br>
                      <p>Required Fields : API Username, API Password, API ID</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : Clickatell-platform</h4> <br>
                      <p>Required Fields : API ID</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : Nexmo</h4> <br>
                      <p>Required Fields : API Key, API Secret</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : msg91.com</h4> <br>
                      <p>Required Fields : Auth Key, Sender</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : textlocal.in</h4> <br>
                      <p>Required Fields : API Key, Sender</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : sms4connect.com</h4> <br>
                      <p>Required Fields : Account ID, Password, Mask</p> 
                    </div> 

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : telnor.com</h4> <br>
                      <p>Required Fields : MSISDN, Password, From</p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : mvaayoo.com</h4> <br>
                      <p>Required Fields : Admin, Password, Sender ID</p>
                      <p>Password format : email:password <i>[i.e. example@example.com:XXXX]</i> </p> 
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : trio-mobile.com</h4> <br>
                      <p>Required Fields : API Key, Sender ID</p>
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : routesms.com</h4> <br>
                      <p>Required Fields : Username, Password, Sender ID/From</p>
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : sms40.com</h4> <br>
                      <p>Required Fields : Username, Password, Sender ID/From</p>
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : africastalking.com</h4> <br>
                      <p>Required Fields : API Key, Sender ID/From [Use username in Sender ID/From]</p>
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : infobip.com</h4> <br>
                      <p>Required Fields :Username, Password, Sender ID/From</p>
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : smsgateway.me</h4> <br>
                      <p>Required Fields API Token, API ID [Use device ID in API ID]</p>
                    </div>

                    <div class="bs-callout bs-callout-info"> 
                      <h4><i class="fa fa-plug"></i> Gateway : semysms.net</h4> <br>
                      <p>Required Fields :Auth Token, API ID [Use devide ID in API ID]</p>
                    </div>


                  
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Get Started</button>
                  </div>
                </div>
              </div>
            </div>            
          </div>         
        <?php 
        } ?>
    
  		<?php 
        if($crud==1) 
			$this->load->view('admin/theme/theme_crud',$output); 
        else 
			$this->load->view($body);

      ?>  
      <style>
      /*grid overwrite*/
      .grid_container
      {
        width:100% !important;
        height:500px !important;
      }
      .ui-jqgrid-bdiv{
          max-height: 500px !important;
      }
      .datagrid-wrap
      {
        padding-bottom:30px !important;
      }
      
      /*grid overwrite*/
      </style>

      </div><!-- /.content-wrapper -->

      <!-- footer was here -->

      <!-- Control Sidebar -->
      <?php //$this->load->view('theme/control_sidebar');?>
      <!-- /.control-sidebar -->

      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- Footer -->
      <?php $this->load->view('admin/theme/footer');?>
    <!-- Footer -->
    

  </body>
</html>

<?php include('application/views/include/theme_css.php'); ?>
