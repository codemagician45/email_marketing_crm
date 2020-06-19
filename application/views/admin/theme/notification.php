<div class="navbar-custom-menu">
  <ul class="nav navbar-nav">

  <?php 
    if($this->uri->segment(3)!="read") 
    {?>
    <li>     
      <?php
      $select_lan="english";
      if($this->session->userdata("selected_language")=="") $select_lan=$this->config->item("language");
      else $select_lan=$this->session->userdata("selected_language");
      echo form_dropdown('language',$language_info,$select_lan,'class="form-control  pull-right" autocomplete="off" id="language_change" style="width:125px;height:40px;margin-top:5px;"');  
      ?>        
    </li> <?php 
    } ?>
   
  
    <?php 
      $pro_pic=base_url().'assets/images/logo.png';
    ?>
    <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown user user-menu">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
       <i class="fa fa-user"></i>
       
      </a>
      <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">

          <center><img src="<?php echo $pro_pic;?>" class="img-responsive"/></center>
          <p class="text-center">
            <?php echo $this->session->userdata('user_real_name');?>           
          </p>
        </li>
        <!-- Menu Body -->
        <!-- <li class="user-body">
          <div class="col-xs-4 text-center">
            <a href="#">Followers</a>
          </div>
          <div class="col-xs-4 text-center">
            <a href="#">Sales</a>
          </div>
          <div class="col-xs-4 text-center">
            <a href="#">Friends</a>
          </div>
        </li> -->
        <!-- Menu Footer-->
        <li class="user-footer border_gray">
          <div class="pull-left">
            <a href="<?php echo site_url('change_password/reset_password_form') ?>" class="btn btn-outline-info btn-flat"> <i class="fa fa-key"></i> <?php echo $this->lang->line('Change Password'); ?></a>
          </div>
          <div class="pull-right">
            <a href="<?php echo site_url('home/logout') ?>" class="btn btn-outline-primary btn-flat"><i class="fa fa-sign-out"></i> <?php echo $this->lang->line('Sign out'); ?></a>
          </div>
        </li>
      </ul>
    </li>
    <!-- Control Sidebar Toggle Button -->
    <!-- <li>
      <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
    </li> -->
  </ul>
</div>