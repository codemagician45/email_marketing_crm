<br>
<style>#recovery_form{text-align:center;}</style>
  <div class="row row-centered">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-8 col-centered border_gray grid_content padded background_white">
    <h6 class="column-title"><i class="fa fa-cog fa-2x blue"> Install <?php echo $this->config->item('product_short_name');?> Package</i></h6>
    
    <?php 
    if($this->session->userdata('mysql_error')!="")
      {
        echo "<pre style='margin:0 auto;color:red;text-align:center;'><h3 style='color:red;'>";
        echo $this->session->userdata('mysql_error');
        $this->session->unset_userdata('mysql_error');
        echo "</h3></pre><br/>"; 
      }
    ?>

    <?php 
      if(validation_errors())
      {
        echo "<pre style='margin:0 auto;color:red;text-align:center;'>";
        print_r(validation_errors()); 
        echo "</pre><br/>"; 
      }
    ?>
    <div class="account-wall" id='recovery_form' style='text-align:left; padding:0 15px;'> 
      <form class="form-horizontal" action="<?php echo site_url().'home/installation_action';?>" method="POST">
        <div class="form-group">
           <label>Host Name *</label>
           <input type="text" value="localhost" name="host_name" required class="form-control col-xs-12"  placeholder="Host Name *">          
        </div>
        <div class="form-group">
           <label>Database Name *</label>
           <input type="text" value="<?php echo set_value('database_name'); ?>" name="database_name" required class="form-control col-xs-12"  placeholder="Database Name *">          
        </div>
        
        <div class="form-group">
           <label>Database Username *</label>
           <input type="text" value="<?php echo set_value('database_username'); ?>" name="database_username" required class="form-control col-xs-12"  placeholder="Database Username *">          
        </div>
        <div class="form-group">
           <label>Database Password</label>
           <input type="password" name="database_password" class="form-control col-xs-12"  placeholder="Database Password *">          
        </div>

         <div class="form-group">
           <label><?php echo $this->config->item('product_short_name') ?> Admin Panel Login Username*</label>
           <input type="text" value="<?php echo set_value('app_username'); ?>" name="app_username" required class="form-control col-xs-12"  placeholder="Application Username *">          
        </div>
        <div class="form-group">
           <label><?php echo $this->config->item('product_short_name') ?> Admin Panel Login Password *</label>
           <input type="password" name="app_password" required class="form-control col-xs-12"  placeholder="Application Password *">          
        </div>
        <div class="form-group">
           <label>Company Name *</label>
           <input type="text" value="<?php echo set_value('institute_name'); ?>" name="institute_name" required class="form-control col-xs-12"  placeholder="Company Name *">          
        </div>
        <div class="form-group">
           <label>Company Address </label>
           <input type="text" value="<?php echo set_value('institute_address'); ?>" name="institute_address"  class="form-control col-xs-12"  placeholder="Company Address ">          
        </div>
         <div class="form-group">
           <label>Company Email </label>
           <input type="text" value="<?php echo set_value('institute_email'); ?>" name="institute_email"  class="form-control col-xs-12"  placeholder="Company Email ">          
        </div>
        <div class="form-group">
           <label>Company Phone / Mobile </label>
           <input type="text" value="<?php echo set_value('institute_mobile'); ?>" name="institute_mobile"  class="form-control col-xs-12"  placeholder="Company Phone / Mobile ">          
        </div>   
 
       
        <div class="form-group text-center">
          <button type="submit" style="margin-top:20px" class="btn btn-warning btn-lg"><i class="fa fa-check"></i> Install <?php echo $this->config->item('product_short_name');?> Now</button><br/><br/> 
        </div>  
      </form>    
    </div>
  </div>
  </div>
