<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar" style="padding-top: 40px;">
    <!-- Sidebar user panel -->  
    
    <ul class="sidebar-menu">
      

        <?php if ($this->session->userdata('user_type')== "Admin")  { ?>
        <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."dashboard/dashboard"; ?>"> <i class="hvr-icon fa fa-dashboard"></i> <span><b><?php echo $this->lang->line("Admin Dashboard"); ?></b></span> </a></li>
        <?php } ?>
        
        <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."dashboard/my_dashboard"; ?>"> <i class="fa fa-dashboard hvr-icon"></i> <span><b><?php echo $this->lang->line('My Dashboard'); ?></b></span> </a></li>
    
        <?php if ($this->session->userdata('user_type')== "Admin")  { ?>
        <li class="treeview">
          <a href="#" class=" hvr-icon-spin">
            <i class="fa fa-gears hvr-icon"></i> <span><b><?php echo $this->lang->line('Settings'); ?></b></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
      
          <ul class="treeview-menu">
            <li><a class=" hvr-icon-spin" href="<?php echo site_url(); ?>setting/configuration"><i class="fa fa-cog hvr-icon"></i><span><?php echo $this->lang->line('General Settings'); ?></span></a></li>
            <li><a class=" hvr-icon-spin" href="<?php echo site_url(); ?>setting/frontend_configuration"><i class="fa fa-newspaper-o hvr-icon"></i><span><?php echo $this->lang->line('Frontend Settings'); ?></span></a></li>
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."admin_config_email/index"; ?>"><i class="fa fa-envelope hvr-icon"></i> <?php echo $this->lang->line('System Email Settings') ?> </a></li>    
          </ul>
        </li> <!-- end settings -->
        <!-- <li> <a href="<?php echo site_url()."setting/configuration"; ?>"> <i class="fa fa-cogs"></i> <span><b><?php echo $this->lang->line('General Settings'); ?></b></span> </a></li>  -->
        
        <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."user"; ?>"> <i class="fa fa-group hvr-icon"></i> <span><b> <?php echo $this->lang->line('User Management'); ?></b></span> </a></li>
        
        <!-- <li class="treeview">
          <a class=" hvr-icon-spin" href="#">
            <i class="fa fa-paypal hvr-icon"></i> <span><b><?php echo $this->lang->line('Payment'); ?></b></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
      
          <ul class="treeview-menu">
            <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."payment/payment_dashboard_admin"; ?>"> <i class="fa fa-dashboard hvr-icon"></i> <?php echo $this->lang->line('Dashboard'); ?> </a></li>   
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."payment/payment_setting_admin"; ?>"><i class="fa fa-gears hvr-icon"></i> <?php echo $this->lang->line('Payment Settings'); ?></a></li>    
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."payment/admin_payment_history"; ?>"><i class="fa fa-history hvr-icon"></i> <?php echo $this->lang->line('Payment History'); ?> </a></li>     
          </ul>
        </li> --> <!-- end my sms --> 

        <?php } else { ?>
        <!-- <li><a class=" hvr-icon-spin" href="<?php echo site_url()."payment/member_payment_history"; ?>"><i class="fa fa-paypal hvr-icon"></i> <span><b><?php echo $this->lang->line('Payment'); ?></b></span></a></li>  -->
        <?php } ?>
        
        
        <li class="treeview">
          <a class=" hvr-icon-spin" href="#">
            <i class="fa fa-book hvr-icon"></i> <span><b> <?php echo $this->lang->line('Contacts');  ?></b></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."phonebook/contact_list"; ?>"> <i class="fa fa-phone hvr-icon"></i> <?php echo $this->lang->line('Contact');  ?>  </a></li>
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."phonebook/contact_group"; ?>"><i class="fa fa-sitemap hvr-icon"></i> <?php echo $this->lang->line('Contact Group');  ?> </a></li> 
               
          </ul>
        </li> <!-- end my sms --> 

        <!-- <li class="treeview">
          <a class=" hvr-icon-spin" href="#">
            <i class="fa fa-send hvr-icon"></i> <span><b><?php echo $this->lang->line('My SMS'); ?></b></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_sms/sms_api"; ?>"><i class="fa fa-plug hvr-icon"></i> <?php echo $this->lang->line('SMS API'); ?></a></li>    
            <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."my_sms/sms_template"; ?>"> <i class="fa fa-instagram hvr-icon"></i> <?php echo $this->lang->line('SMS Template'); ?></a></li>   
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_sms/sms_campaign"; ?>"><i class="fa fa-send hvr-icon"></i> <?php echo $this->lang->line('SMS Campaign'); ?></a></li>   
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_sms/birthday_sms"; ?>"><i class="fa fa-birthday-cake  hvr-icon"></i> <?php echo $this->lang->line('Birthday Wish SMS'); ?></a></li>   
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_sms/sms_history"; ?>"><i class="fa fa-list-ol hvr-icon"></i> <?php echo $this->lang->line('SMS History'); ?></a></li>   
          </ul>
        </li> --> <!-- end my sms -->  

        <li class="treeview">
          <a class=" hvr-icon-spin" href="#">
            <i class="fa fa-envelope hvr-icon"></i> <span><b><?php echo $this->lang->line('Email'); ?></b></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a class=" hvr-icon-spin" href="#"><i class="fa fa-plug hvr-icon"></i><?php echo $this->lang->line('Email API'); ?> <i class="fa fa-angle-left pull-right "></i></a>
              <ul class="treeview-menu">
                <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/email_smtp_settings"; ?>"><i class="fa fa-plug hvr-icon"></i><?php echo $this->lang->line('SMTP API'); ?>  </a></li>
                <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/email_mandrill_settings"; ?>"><i class="fa fa-plug hvr-icon"></i><?php echo $this->lang->line('Mandrill API'); ?> </a></li>     
                <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/email_sendgrid_settings"; ?>"><i class="fa fa-plug hvr-icon"></i><?php echo $this->lang->line('SendGrid API'); ?>  </a></li>     
                <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/email_mailgun_settings"; ?>"><i class="fa fa-plug hvr-icon"></i><?php echo $this->lang->line('Mailgun API'); ?>  </a></li>     
              </ul>
            </li>               
            <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/email_template"; ?>"> <i class="fa fa-instagram hvr-icon"></i><?php echo $this->lang->line('Email Template'); ?>  </a></li> 
            <!-- <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/send_email"; ?>"><i class="fa fa-send hvr-icon"></i><?php echo $this->lang->line('Send Email'); ?> </a></li>  -->
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/email_campaign"; ?>"><i class="fa fa-envelope-o hvr-icon"></i><?php echo $this->lang->line('Email Campaign'); ?> </a></li>   
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/birthday_email"; ?>"><i class="fa fa-birthday-cake hvr-icon"></i><?php echo $this->lang->line('Birthday Wish Email'); ?> </a></li>   
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."my_email/email_history"; ?>"><i class="fa fa-list-ol hvr-icon"></i><?php echo $this->lang->line('Email History'); ?> </a></li>   
          </ul>
        </li> <!-- end administrator --> 

        <li class="treeview">
          <a class=" hvr-icon-spin" href="#">
            <i class="fa fa-list hvr-icon"></i> <span><b><?php echo $this->lang->line('Report'); ?></b></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>

          <?php if ($this->session->userdata('user_type')== "Admin")  { ?>
          <ul class="treeview-menu">
            <li>
                <a class=" hvr-icon-spin" href="#"><i class="fa fa-user hvr-icon"></i><?php echo $this->lang->line('My Report'); ?> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a class=" hvr-icon-spin" href="<?php echo site_url()."report/sms_report_contactwise"; ?>"><i class="fa fa-envelope hvr-icon"></i><?php echo $this->lang->line('My SMS Report'); ?> </a></li>
                  <li><a class=" hvr-icon-spin" href="<?php echo site_url()."report/email_report_contactwise"; ?>"><i class="fa fa-envelope-o hvr-icon"></i><?php echo $this->lang->line('My Email Report'); ?></a></li>                    
                </ul>
            </li>
            <li>
                <a class=" hvr-icon-spin" href="#"><i class="fa fa-group hvr-icon"></i> <?php echo $this->lang->line("Users' Report"); ?><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a class=" hvr-icon-spin" href="<?php echo site_url()."report/sms_report_userwise"; ?>"><i class="fa fa-envelope hvr-icon"></i><?php echo $this->lang->line("Users' SMS Report"); ?> </a></li>
                  <li><a class=" hvr-icon-spin" href="<?php echo site_url()."report/email_report_userwise"; ?>"><i class="fa fa-envelope-o hvr-icon"></i><?php echo $this->lang->line("Users' Email Report"); ?> </a></li>                    
                </ul>
            </li>                 
          </ul>
          <?php } 
          else { ?>
          <ul class="treeview-menu">            
            <!-- <li><a class=" hvr-icon-spin" href="<?php echo site_url()."report/sms_report_contactwise"; ?>"><i class="fa fa-envelope hvr-icon"></i><?php echo $this->lang->line('My SMS Report'); ?> </a></li> -->
            <li><a class=" hvr-icon-spin" href="<?php echo site_url()."report/email_report_contactwise"; ?>"><i class="fa fa-envelope-o hvr-icon"></i><?php echo $this->lang->line('Email Report'); ?> </a></li>                    
          </ul>
          <?php } ?>

        </li> <!-- end administrator --> 

        <?php if($this->session->userdata("user_type") =='Admin') : ?>
          <li><a class=" hvr-icon-spin" href="<?php echo site_url()."ssem_api/get_api"; ?>"> <i class="fa fa-plug hvr-icon"></i> <span><b><?php echo $this->lang->line('Native API & Cron Job'); ?></b></span> </a></li> 
        <?php endif; ?>

        <?php if($this->session->userdata("user_type") =='Admin') : ?>
          <li> <a class=" hvr-icon-spin" href="<?php echo site_url()."update_system/index"; ?>"> <i class="hvr-icon fa fa-angle-double-up"></i> <span><b><?php echo $this->lang->line("Check Update"); ?></b></span> </a></li>
        <?php endif; ?>
        <li style="margin-bottom:200px">&nbsp;</li>
      

    </ul>  <!-- end menu -->
  </section> <!-- /.sidebar -->
</aside>
<?php 
$all_links = Array
(
 base_url("#"),base_url()
);
$all_links=array_unique($all_links);

// echo "<pre style='padding-left:300px;'>";
// print_r($all_links);
// exit;
$unsetkey = array_search (base_url().'#', $all_links); 
if($unsetkey!=FALSE)
unset($all_links[$unsetkey]); // removing links without a real url

/* 
links that are not in database [custom link = sibebar parent]
No need to add a custom link if it's parent is controller/index
*/
$custom_links=array
(
  base_url("admin_config_email")=>base_url("admin_config_email/index"),
  base_url("user/index/add")=>base_url("user"),
  base_url("user/index/edit")=>base_url("user"),
  base_url("user/change_user_password")=>base_url("user"),
  base_url("payment/payment_setting_admin/edit")=>base_url("payment/payment_dashboard_admin"),
  base_url("phonebook/contact_group/add")=>base_url("phonebook/contact_group"),
  base_url("phonebook/contact_group/edit")=>base_url("phonebook/contact_group"),
  base_url("phonebook/add_contact")=>base_url("phonebook/contact_list"),
  base_url("phonebook/update_contact")=>base_url("phonebook/contact_list"),
  base_url("my_sms/sms_api/add")=>base_url("my_sms/sms_api"),
  base_url("my_sms/sms_api/edit")=>base_url("my_sms/sms_api"),
  base_url("my_sms/sms_template/add")=>base_url("my_sms/sms_template"),
  base_url("my_sms/sms_template/read")=>base_url("my_sms/sms_template"),
  base_url("my_sms/sms_template/edit")=>base_url("my_sms/sms_template"),
  base_url("my_sms/add_schedule")=>base_url("my_sms/scheduled_sms"),
  base_url("my_sms/birthday_sms/add")=>base_url("my_sms/birthday_sms"),
  base_url("my_sms/birthday_sms/read")=>base_url("my_sms/birthday_sms"),
  base_url("my_sms/birthday_sms/edit")=>base_url("my_sms/birthday_sms"),
  base_url("my_email/email_smtp_settings/add")=>base_url("my_email/email_smtp_settings"),
  base_url("my_email/email_smtp_settings/edit")=>base_url("my_email/email_smtp_settings"),
  base_url("my_email/email_mandrill_settings/add")=>base_url("my_email/email_mandrill_settings"),
  base_url("my_email/email_mandrill_settings/edit")=>base_url("my_email/email_mandrill_settings"),
  base_url("my_email/email_sendgrid_settings/add")=>base_url("my_email/email_sendgrid_settings"),
  base_url("my_email/email_sendgrid_settings/edit")=>base_url("my_email/email_sendgrid_settings"),
  base_url("my_email/email_mailgun_settings/add")=>base_url("my_email/email_mailgun_settings"),
  base_url("my_email/email_mailgun_settings/edit")=>base_url("my_email/email_mailgun_settings"),
  base_url("my_email/email_template/add")=>base_url("my_email/email_template"),
  base_url("my_email/email_template/edit")=>base_url("my_email/email_template"),
  base_url("my_email/add_schedule")=>base_url("my_email/scheduled_email"),
  base_url("my_email/birthday_email/add")=>base_url("my_email/birthday_email"),
  base_url("my_email/birthday_email/read")=>base_url("my_email/birthday_email"),
  base_url("my_email/birthday_email/edit")=>base_url("my_email/birthday_email"),

);
$custom_links_assoc_str="{";
$loop=0;
foreach ($custom_links as $key => $value) 
{
  $loop++;
  array_push($all_links, $key); // adding custom urls in all urls array

  /* making associative link -> parent array for js, js dont support special chars */
  $custom_links_assoc_str.=str_replace(array('/',':','-','.'), array('FORWARDSLASHES','COLONS','DASHES','DOTS'), $key).":'".$value."'";
  if($loop!=count($custom_links)) $custom_links_assoc_str.=',';
}
$custom_links_assoc_str.="}";
// echo "<pre style='padding-left:300px;'>";
// print_r($all_links);
// exit;
?>

<script type="text/javascript">

  var all_links_JS = [<?php echo '"'.implode('","', $all_links).'"' ?>]; // all urls includes database & custom urls
  var custom_links_JS= [<?php echo '"'.implode('","', array_keys($custom_links)).'"' ?>]; // only custom urls
  var custom_links_assoc_JS = <?php echo $custom_links_assoc_str?>; // custom urls associative array link -> parent
  
  var sideBarURL = window.location;
  sideBarURL=String(sideBarURL).trim();
  sideBarURL=sideBarURL.replace('#_=_',''); // redirct from facebook login return extra chars with url

  function removeUrlLastPart(the_url)   // function that remove last segment of a url
  {
      var theurl = String(the_url).split('/');
      theurl.pop();      
      var answer=theurl.join('/');
      return answer;
  }

  // get parent url of a custom url
  function matchCustomUrl(find)
  {
    var parentUrl='';
    var tempu1=find.replace(/\//g, 'FORWARDSLASHES'); // decoding special chars that was encoded to make js array
    tempu1=tempu1.replace(/:/g, 'COLONS');
    tempu1=tempu1.replace(/-/g, 'DASHES');
    tempu1=tempu1.replace(/\./g, 'DOTS');

    if(typeof(custom_links_assoc_JS[tempu1])!=='undefined')
    parentUrl=custom_links_assoc_JS[tempu1]; // getting parent value of custom link

    return parentUrl;
  }

  if(jQuery.inArray(sideBarURL, custom_links_JS) !== -1) // if the current link match custom urls
  {    
    sideBarURL=matchCustomUrl(sideBarURL);
  } 
  else if(jQuery.inArray(sideBarURL, all_links_JS) !== -1) // if the current link match known urls, this check is done later becuase all_links_JS also contains custom urls
  {
     sideBarURL=sideBarURL;
  }
  else // url does not match any of known urls
  {  
    var remove_times=1;
    var temp_URL=sideBarURL;
    var temp_URL2="";
    var tempu2="";
    while(true) // trying to match known urls by remove last part of url or adding /index at the last
    {
      temp_URL=removeUrlLastPart(temp_URL); // url may match after removing last
      temp_URL2=temp_URL+'/index'; // url may match after removing last part and adding /index

      if(jQuery.inArray(temp_URL, custom_links_JS) !== -1) // trimmed url match custom urls
      {
        sideBarURL=matchCustomUrl(temp_URL);
        break;
      }
      else if(jQuery.inArray(temp_URL, all_links_JS) !== -1) //trimmed url match known links
      {
        sideBarURL=temp_URL;
        break;
      }
      else // trimmed url does not match known urls, lets try extending url by adding /index
      {
        if(jQuery.inArray(temp_URL2, custom_links_JS) !== -1) // extended url match custom urls
        {
          sideBarURL=matchCustomUrl(temp_URL2);
          break;
        }
        else if(jQuery.inArray(temp_URL2, all_links_JS) !== -1)  // extended url match known urls
        {
          sideBarURL=temp_URL2;
          break;
        }
      }
      remove_times++;
      if(temp_URL.trim()=="") break;
    }    
  }

  $('ul.sidebar-menu a').filter(function() {
     return this.href == sideBarURL;
  }).parent().addClass('active');
  $('ul.treeview-menu a').filter(function() {
     return this.href == sideBarURL;
  }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
</script>