<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Home extends CI_Controller
{

    public $user_id;
    public $language;
    public $is_rtl;

    // new
    public $is_demo;
    public $app_product_id;
    public $APP_VERSION;
    // end new

    public function __construct()
    {
        parent::__construct();

        set_time_limit(0);
        $this->load->helper('my_helper');
        $this->load->model('basic');

        $this->is_rtl=FALSE;
        $this->language="";
        $this->_language_loader();
        $this->load->helper('security');

        //new
        $this->app_product_id=26; // this is SSEM product of our update system
        $this->APP_VERSION="";
        //end new 

        // $seg = $this->uri->segment(2);
        // if ($seg!="installation" && $seg!= "installation_action") {
        //     if (file_exists(APPPATH.'install.txt')) {
        //         redirect('home/installation', 'location');
        //     }
        // }

        // if (!file_exists(APPPATH.'install.txt')) {
        //     $this->load->database();
        //     $this->load->model('basic');
        //     $this->_time_zone_set();
			
        //     // MySQL goe way fix
        //     $q= "SET SESSION wait_timeout=50000";
        //     $this->db->query($q);
        //     /**Disable STRICT_TRANS_TABLES mode if exist on mysql ***/
        //     $query="SET SESSION sql_mode = ''";
        //     $this->db->query($query);
        //     if(function_exists('ini_set')) ini_set('memory_limit', '-1');           
			
        //     $this->user_id=$this->session->userdata("user_id");
        //     $this->upload_path = realpath(APPPATH . '../upload');
        //     $this->session->unset_userdata('set_custom_link');


        //     // new 
        //     $version_data=$this->basic->get_data("version",array("where"=>array("current"=>"1")));
        //     $appversion=isset($version_data[0]['version']) ? $version_data[0]['version'] : "";
        //     $this->APP_VERSION=$appversion;
        //     //end new
        // }

        if($this->config->item('force_https')=='1')  
        {
            $actualLink = $actualLink = base_url(uri_string());
            $poS=strpos($actualLink, 'http://');
            if($poS!==FALSE)
            {
             $new_link=str_replace('http://', 'https://', $actualLink);
             redirect($new_link,'refresh');
            }    
        }
    }

    public function _scanAll($myDir)
    {
        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);

        $i=0;
        foreach (new RecursiveIteratorIterator($di) as $filename) {

            $dir = str_replace($myDir, '', dirname($filename));
            // $dir = str_replace('/', '>', substr($dir,1));

            $org_dir=str_replace("\\", "/", $dir);

            if($org_dir)
                $file_path = $org_dir. "/". basename($filename);
            else
                $file_path = basename($filename);

            $file_full_path=$myDir."/".$file_path;
            $file_size= filesize($file_full_path);
            $file_modification_time=filemtime($file_full_path);

            $dirTree[$i]['file'] = $file_full_path;
            $i++;
        }
        return $dirTree;
    }

     // delete any direcory with it childs even it is not empty
    protected function delete_directory($dirPath="") 
    {
        if (!is_dir($dirPath)) 
        return false;

        if(substr($dirPath, strlen($dirPath) - 1, 1) != '/') $dirPath .= '/';
        
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach($files as $file) 
        {
            if(is_dir($file)) $this->delete_directory($file);             
            else @unlink($file);            
        }
        rmdir($dirPath);
    }

     public function _language_loader()
    {       

        if(!$this->config->item("language") || $this->config->item("language")=="")
        $this->language="english";
        else $this->language=$this->config->item('language');

        if($this->session->userdata("selected_language")!="")
        $this->language = $this->session->userdata("selected_language");
        else if(!$this->config->item("language") || $this->config->item("language")=="") 
        $this->language="english";
        else $this->language=$this->config->item('language');

        if($this->language=="arabic")
        $this->is_rtl=TRUE;

        // created files
        
               

        if (file_exists(APPPATH.'language/'.$this->language.'/email_api_lang.php'))
        $this->lang->load('email_api', $this->language);        

        if (file_exists(APPPATH.'language/'.$this->language.'/front_lang.php'))
        $this->lang->load('front', $this->language);
       
        if (file_exists(APPPATH.'language/'.$this->language.'/sms_api_lang.php'))
        $this->lang->load('sms_api', $this->language);

        if (file_exists(APPPATH.'language/'.$this->language.'/contact_lang.php'))
        $this->lang->load('contact', $this->language);     
        
        if (file_exists(APPPATH.'language/'.$this->language.'/payment_lang.php'))
        $this->lang->load('payment', $this->language);

        if (file_exists(APPPATH.'language/'.$this->language.'/user_management_lang.php'))
        $this->lang->load('user_management', $this->language);   

        if (file_exists(APPPATH.'language/'.$this->language.'/general_settings_lang.php'))
        $this->lang->load('general_settings', $this->language);

        if (file_exists(APPPATH.'language/'.$this->language.'/admin_dashboard_lang.php'))
        $this->lang->load('admin_dashboard', $this->language);      

        if (file_exists(APPPATH.'language/'.$this->language.'/sidebar_lang.php'))
        $this->lang->load('sidebar', $this->language);      

        if (file_exists(APPPATH.'language/'.$this->language.'/users_sms_report_lang.php'))
        $this->lang->load('users_sms_report', $this->language); 

        if (file_exists(APPPATH.'language/'.$this->language.'/misc_lang.php'))
        $this->lang->load('misc', $this->language);    

        if (file_exists(APPPATH.'language/'.$this->language.'/message_lang.php'))
        $this->lang->load('message', $this->language);

      
        // load your files common files
        if (file_exists(APPPATH.'language/'.$this->language.'/calendar_lang.php'))
        $this->lang->load('calendar', $this->language);

        if (file_exists(APPPATH.'language/'.$this->language.'/common_lang.php'))
        $this->lang->load('common', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/date_lang.php'))
        $this->lang->load('date', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/db_lang.php'))
        $this->lang->load('db', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/email_lang.php'))
        $this->lang->load('email', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/form_validation_lang.php'))
        $this->lang->load('form_validation', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/ftp_lang.php'))
        $this->lang->load('ftp', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/imglib_lang.php'))
        $this->lang->load('imglib', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/migration_lang.php'))
        $this->lang->load('migration', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/number_lang.php'))
        $this->lang->load('number', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/pagination_lang.php'))
        $this->lang->load('pagination', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/profiler_lang.php'))
        $this->lang->load('profiler', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/unit_test_lang.php'))
        $this->lang->load('unit_test', $this->language);
        
        if (file_exists(APPPATH.'language/'.$this->language.'/upload_lang.php'))
        $this->lang->load('upload', $this->language);

        if (file_exists(APPPATH.'language/'.$this->language.'/v_4_0_lang.php'))
        $this->lang->load('v_4_0', $this->language);  
    }


     public function language_changer()
    {
        $language=$this->input->post("language");
        $this->session->set_userdata("selected_language",$language);
    }

    


    public function _email_send_function($config_id_prefix="", $message_org="", $to_emails="", $subject="", $attachement='', $fileName='',$user_id='')
    {
        $message_org = preg_replace('/data-cke-saved-src="(.+?)"/', '', $message_org);
        $message_org = preg_replace('/_moz_resizing="(.+?)"/', '', $message_org);
        
        $message = '<!DOCTYPE HTML>'.
        '<head>'.
        '<meta http-equiv="content-type" content="text/html">'.
        '<title>'.$subject.'</title>'.
        '</head>'.
        '<body>'.
        '<div id="outer" style="width: 90%;margin: 0 auto;margin-top: 10px;">'.$message_org.'</div>'.
        '</body>';


        if ($config_id_prefix=="" || $message=="" || $to_emails=="" || $subject=="") {
            return false;
        }

        if ($fileName=="0") {
            $fileName="";
            $attachement="";
        }

        if (!is_array($to_emails)) {
            $to_emails=array($to_emails);
        }
            
        $status="";
        
        /*****get the email configuration value*****/
        $from_email=$config_id_prefix;
        $from_email_separate=explode("_", $from_email);
        $config_type=$from_email_separate[0];
        $config_id=$from_email_separate[1];
        
        if ($config_type=='smtp') {
            $table_name="email_config";
        } elseif ($config_type=='mandrill') {
            $table_name="email_mandrill_config";
        } elseif ($config_type=='sendgrid') {
            $table_name="email_sendgrid_config";
        } elseif ($config_type=='mailgun') {
            $table_name="email_mailgun_config";
        } else {
            $table_name="";
        }
        
                    
        $where2=array("where"=>array('id'=>$config_id));
        $email_config_details=$this->basic->get_data($table_name, $where2, $select='', $join='', $limit='', $start='', $group_by='', $num_rows=0);

        $userid = $user_id;

        if (count($email_config_details)==0) {
            $status =  "Opps !!! Sorry no configuration is found";
            return $status;
        }

        if ($config_type=='smtp') 
        {
            foreach ($email_config_details as $send_info) 
            {
                $send_email = trim($send_info['email_address']);
                $smtp_host= trim($send_info['smtp_host']);
                $smtp_port= trim($send_info['smtp_port']);
                $smtp_user=trim($send_info['smtp_user']);
                $smtp_password= trim($send_info['smtp_password']);
                $smtp_type = trim($send_info['smtp_type']);
            }
            
            /*****Email Sending Code ******/
                $config = array(
                  'protocol' => 'smtp',
                  'smtp_host' => "{$smtp_host}",
                  'smtp_port' => $smtp_port,
                  'smtp_user' => "{$smtp_user}", // change it to yours
                  'smtp_pass' => "{$smtp_password}", // change it to yours
                  'mailtype' => 'html',
                  'charset' => 'utf-8',
                  'newline' =>"\r\n",
                  'smtp_timeout'=>'30'
                );

            if($smtp_type != 'Default')
                $config['smtp_crypto'] = $smtp_type;

            $this->load->library('email', $config);
            $this->email->from($send_email); 
            
            if(is_array($to_emails) && count($to_emails)>1)
            {
                $no_reply_arr=explode("@",$send_email);
                if(isset($no_reply_arr[1]))
                $no_reply="do-not-reply@".$no_reply_arr[1];
                else $no_reply=$to_emails[0];
                $this->email->to($no_reply);
                $this->email->bcc($to_emails);
            }
            else $this->email->to($to_emails);

            $this->email->subject($subject);
            $this->email->message($message);
              
            if ($attachement) 
            {
                $this->email->attach($attachement);
            }

            try 
            {
                if($this->email->send())
                $response_smtp = "success";
                else $response_smtp = "error";                
            } 
            catch (Exception $e) 
            {
                $response_smtp = "error";
            }
              
            if($response_smtp!="error") 
            {
                $sent_time=date('Y-m-d H:i:s');
                foreach ($to_emails as $to_email) 
                {
                    $insert_data[]=
                        array
                        (
                            'user_id'=>$userid,
                            'configure_table_name'    =>$table_name,
                            'api_id'                =>$config_id,
                            'to_email'                =>$to_email,
                            'sent_time'             =>$sent_time,
                            'email_message'        =>$message,
                            'attachment'            =>$fileName,
                            'subject'               => $subject
                        );
                }
                    
                /***insert into database table email_history**/
                
                $this->db->insert_batch('email_history', $insert_data);
                $status = "Submited";
            } 
            else 
            {
                $status = "error in configuration";
            }
        }
        
        
        /***  End of Email sending by SMTP  ***/
        
        
        /***  If option is mandrill   ***/
        
        if ($config_type=='mandrill') 
        {
            foreach ($email_config_details as $send_info) 
            {
                $send_email= $send_info['email_address'];
                $api_id=$send_info['api_key'];
                $send_name=$send_info['your_name'];
            }
            $this->load->library('email_manager');
            $result = $this->email_manager->send_madrill_email($send_email, $send_name, $to_emails, $subject,$message, $api_id, $attachement, $fileName);
            
            if ($result!='error') 
            {
                $sent_time=date('Y-m-d H:i:s');
                foreach ($to_emails as $to_email) 
                {
                    $insert_data[]=
                        array
                        (
                            'user_id'=>$userid,
                            'configure_table_name'    =>$table_name,
                            'uid'                    =>$result[$to_email]['id'],
                            'api_id'                =>$config_id,
                            'to_email'                =>$to_email,
                            'send_status'            =>$result[$to_email]['status'],
                            'sent_time'             =>$sent_time,
                            'email_message'        =>$message,
                            'attachment'            =>$fileName,
                            'subject'               => $subject
                        );
                }

                /***insert into database table email_history**/
                
                $this->db->insert_batch('email_history', $insert_data);
                $status = "Submited";
            } 
            else 
            {
                $status ="error in configuration";
            }
        }
        
        
        
        /***** if gateway is sendgrid *****/
        if ($config_type=='sendgrid') 
        {
            $this->load->library('email_manager');
            foreach ($email_config_details as $send_info) 
            {
                $sendgrid_from_email= $send_info['email_address'];
                $this->email_manager->sendgrid_username=$send_info['username'];
                $this->email_manager->sendgrid_password=$send_info['password'];
            }
            
            $result = $this->email_manager->sendgrid_email_send($sendgrid_from_email, $to_emails, $subject, $message, $attachement, $fileName);
            
            if ($result['status']!='error') 
            {
                $sent_time=date('Y-m-d H:i:s');
                foreach ($to_emails as $to_email) 
                {
                    $insert_data[]=
                        array
                        (
                            'user_id'                =>$userid,
                            'configure_table_name'    =>$table_name,
                            'api_id'                =>$config_id,
                            'to_email'                =>$to_email,
                            'send_status'           =>$result['status'],
                            'sent_time'                =>$sent_time,
                            'email_message'        =>$message,
                            'attachment'            =>$fileName,
                            'subject'               => $subject
                        );
                }

                /***insert into database table email_history**/                
                $this->db->insert_batch('email_history', $insert_data);
                $status = "Submited";
            } 
            else 
            {
                $status ="error in configuration";
            }
        }
        
    
    
        if ($config_type=='mailgun') 
        {
            $this->load->library('email_manager');
            foreach ($email_config_details as $send_info) 
            {
                $send_email=$send_info['email_address'];
                $this->email_manager->mailgun_api_key=$send_info['api_key'];
                $this->email_manager->mailgun_domain=$send_info['domain_name'];
            }

            // echo "<pre>"; print_r($attachement); exit();
            
            $result = $this->email_manager->mailgun_email_send($send_email, $to_emails, $subject, $message, $attachement);
            
            if ($result['status']!='error') 
            {
                $sent_time=date('Y-m-d H:i:s');
                foreach ($to_emails as $to_email) 
                {
                    $insert_data[]=
                    array(
                            'user_id'                =>$userid,
                            'configure_table_name'   =>$table_name,
                            'api_id'                 =>$config_id,
                            'uid'                    =>$result['id'],
                            'to_email'               =>$to_email,
                            'send_status'            =>$result['status'],
                            'sent_time'              =>$sent_time,
                            'email_message'          =>$message,
                            'attachment'             =>$fileName,
                            'subject'                => $subject
                        );
                }

                /***insert into database table email_history**/
                
                $this->db->insert_batch('email_history', $insert_data);
                $status = "Submited";
            } 
            else 
            {
                $status ="error in configuration";
            }
        }
        
        return $status;
    }






    public function _time_zone_set()
    {
        $time_zone = $this->config->item('time_zone');
        if ($time_zone== '') {
            $time_zone="Europe/Dublin";
        }
        date_default_timezone_set($time_zone);
    }

    public function installation()
    {
        if (!file_exists(APPPATH.'install.txt')) {
            redirect('home/login', 'location');
        }

        $data = array("body" => "page/install", "page_title" => "Install Package");
        $this->_front_viewcontroller($data);
    }

 
    public function installation_action()
    {
        if (!file_exists(APPPATH.'install.txt')) {
            redirect('home/login', 'location');
        }

        if ($_POST) {
            // validation
            $this->form_validation->set_rules('host_name',                '<b>Host Name</b>',                   'trim|required|xss_clean');
            $this->form_validation->set_rules('database_name',            '<b>Database Name</b>',               'trim|required|xss_clean');
            $this->form_validation->set_rules('database_username',        '<b>Database Username</b>',           'trim|required|xss_clean');
            $this->form_validation->set_rules('database_password',        '<b>Database Password</b>',           'trim|xss_clean');
            $this->form_validation->set_rules('app_username',             '<b>Admin Panel Login Username</b>',  'trim|required|xss_clean');
            $this->form_validation->set_rules('app_password',             '<b>Admin Panel Login Password</b>',  'trim|required|xss_clean');
            $this->form_validation->set_rules('institute_name',           '<b>Institute Name</b>',              'trim|required|xss_clean');
            $this->form_validation->set_rules('institute_address',        '<b>Institute Address</b>',           'trim|xss_clean');
            $this->form_validation->set_rules('institute_email',          '<b>Institute Email</b>',             'trim|valid_email|xss_clean');
            $this->form_validation->set_rules('institute_mobile',         '<b>Institute Phone / Mobile</b>',    'trim|xss_clean');
            $this->form_validation->set_rules('language',                '<b>Language</b>',                    'trim');


            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) {
                return $this->installation();
            } else {
                $host_name =  addslashes(strip_tags($this->input->post('host_name', true)));
                $database_name =  addslashes(strip_tags($this->input->post('database_name', true)));
                $database_username =  addslashes(strip_tags($this->input->post('database_username', true)));
                $database_password =  addslashes(strip_tags($this->input->post('database_password', true)));
                $app_username =  addslashes(strip_tags($this->input->post('app_username', true)));
                $app_password =  addslashes(strip_tags($this->input->post('app_password', true)));
                $institute_name =  addslashes(strip_tags($this->input->post('institute_name', true)));
                $institute_address =  addslashes(strip_tags($this->input->post('institute_address', true)));
                $institute_mobile =  addslashes(strip_tags($this->input->post('institute_mobile', true)));
                $institute_email =  addslashes(strip_tags($this->input->post('institute_email', true)));
                $language = addslashes(strip_tags($this->input->post('language', true)));

                
                $con=@mysqli_connect($host_name, $database_username, $database_password);
                if (!$con) {
                    $this->session->set_userdata('mysql_error', "Could not conenect to MySQL.");
                    return $this->installation();
                }
                if (!@mysqli_select_db($con,$database_name)) {
                    $this->session->set_userdata('mysql_error', "Database not found.");
                    return $this->installation();
                }
                mysqli_close($con);


                // writing application/config/my_config
                $app_my_config_data = "<?php ";
                $app_my_config_data.= "\n\$config['default_page_url'] = '".$this->config->item('default_page_url')."';\n";
                $app_my_config_data.= "\$config['product_name'] = '".$this->config->item('product_name')."';\n";
                $app_my_config_data.= "\$config['product_short_name'] = '".$this->config->item('product_short_name')."' ;\n";
                $app_my_config_data.= "\$config['product_version'] = '".$this->config->item('product_version')." ';\n\n";
                $app_my_config_data.= "\$config['institute_address1'] = '$institute_name';\n";
                $app_my_config_data.= "\$config['institute_address2'] = '$institute_address';\n";
                $app_my_config_data.= "\$config['institute_email'] = '$institute_email';\n";
                $app_my_config_data.= "\$config['institute_mobile'] = '$institute_mobile';\n";
                $app_my_config_data.= "\$config['developed_by'] = '".$this->config->item('developed_by')."';\n";
                $app_my_config_data.= "\$config['developed_by_href'] = '".$this->config->item('developed_by_href')."';\n";
                $app_my_config_data.= "\$config['developed_by_title'] = '".$this->config->item('developed_by_title')."';\n";
                $app_my_config_data.= "\$config['developed_by_prefix'] = '".$this->config->item('developed_by_prefix')."' ;\n";
                $app_my_config_data.= "\$config['support_email'] = '".$this->config->item('support_email')."' ;\n";
                $app_my_config_data.= "\$config['support_mobile'] = '".$this->config->item('support_mobile')."' ;\n";
                $app_my_config_data.= "\$config['time_zone'] = '' ;\n";
                $app_my_config_data.= "\$config['language'] = '$language';\n";
                $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
                $app_my_config_data.= "\$config['sess_table_name'] = 'ci_sessions';\n";
                file_put_contents(APPPATH.'config/my_config.php', $app_my_config_data, LOCK_EX);
                //writting  application/config/my_config

                //writting application/config/database
                $database_data = "";
                $database_data.= "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n
                \$active_group = 'default';
                \$active_record = true;
                \$db['default']['hostname'] = '$host_name';
                \$db['default']['username'] = '$database_username';
                \$db['default']['password'] = '$database_password';
                \$db['default']['database'] = '$database_name';
                \$db['default']['dbdriver'] = 'mysqli';
                \$db['default']['dbprefix'] = '';
                \$db['default']['pconnect'] = TRUE;
                \$db['default']['db_debug'] = TRUE;
                \$db['default']['cache_on'] = FALSE;
                \$db['default']['cachedir'] = '';
                \$db['default']['char_set'] = 'utf8';
                \$db['default']['dbcollat'] = 'utf8_general_ci';
                \$db['default']['swap_pre'] = '';
                \$db['default']['autoinit'] = TRUE;
                \$db['default']['stricton'] = FALSE;";
                file_put_contents(APPPATH.'config/database.php', $database_data, LOCK_EX);
                //writting application/config/database

                // loding database library, because we need to run queries below and configs are already written
                $this->load->database();
                $this->load->model('basic');
                // loding database library, because we need to run queries below and configs are already written

                // dumping sql
                $dump_file_name = 'initial_db.sql';
                $dump_sql_path = 'assets/backup_db/'.$dump_file_name;
                $this->basic->import_dump($dump_sql_path);
                // dumping sql

                //generating hash password for admin and updaing database
                $app_password = md5($app_password);
                $this->basic->update_data($table = "users", $where = array("user_type" => "Admin"), $update_data = array("username"=>$app_username, "mobile" => $institute_mobile, "email" => $institute_email, "password" => $app_password, "first_name" => $institute_name, "status" => "1", "deleted" => "0"));
                //generating hash password for admin and updaing database

                //deleting the install.txt file,because installation is complete
                if (file_exists(APPPATH.'install.txt')) {
                    unlink(APPPATH.'install.txt');
                }
                //deleting the install.txt file,because installation is complete

                redirect('home/login');
            }
        }
    }


    public function index()
    {
        $this->login();
    }


        
    public function access_forbidden()
    {
        $data=array("title"=>"Access Forbidden","message"=>"Access Forbidden.");
        $this->load->view('page/message_page', $data);
    }


    public function page_not_found()
    {
        $data=array("title"=>"Page Not Found","message"=>"404 ! Page Not Found");
        $this->load->view('page/message_page', $data);
    }


    public function _front_viewcontroller($data=array())
    {
        if (!isset($data['body'])) {
            $data['body']=$this->config->item('default_page_url');
        }

        $loadthemebody="blue";
        if($this->config->item('theme_front')!="") $loadthemebody=$this->config->item('theme_front');

        $themecolorcode="#1193D4";

        if($loadthemebody=='white')        { $themecolorcode="#303F42";}
        if($loadthemebody=='black')        { $themecolorcode="#1A2226";}
        if($loadthemebody=='green')        { $themecolorcode="#00A65A";}
        if($loadthemebody=='purple')       { $themecolorcode="#545096";}
        if($loadthemebody=='red')          { $themecolorcode="#E55053";}
        if($loadthemebody=='yellow')       { $themecolorcode="#F39C12";}

        $data['THEMECOLORCODE']=$themecolorcode;

        $this->load->view('front/theme_front', $data);
    }

    public function _viewcontroller($data=array())
    {
        $system_currency="USD";
        $payment_config_data=$this->basic->get_data("payment_config");
        if(count($payment_config_data)>0) $system_currency=$payment_config_data[0]["currency"];

        $data["system_currency"]=$system_currency;

        if (!isset($data['body'])) {
            $data['body']=$this->config->item('default_page_url');
        }
    
        if (!isset($data['page_title'])) {
            $data['page_title']="Admin Panel";
        }

        if (!isset($data['crud'])) {
            $data['crud']=0;
        }

        $data["themes"] = $this->_theme_list();
        $data["themes_front"] = $this->_theme_list_front();
        $loadthemebody="skin-black-light";
        if($this->config->item('theme')!="") $loadthemebody=$this->config->item('theme');

        $data['loadthemebody']=$loadthemebody;

        $themecolorcode="#607D8B";
        $color1="#999999";
        $color2="#607D8B";
        $color3="#607D77";
        $color4="#504C43";

        if($loadthemebody=='skin-black')        { $themecolorcode="#1A2226"; $color1="#6C7A7D"; $color2="#55676A"; $color3="#303F42"; $color4="#222D32"; }

        if($loadthemebody=='skin-blue-light')   { $themecolorcode="#397CA5"; $color1="#6497B1"; $color2="#005B96"; $color3="#03396C"; $color4="#011F4B"; }
        if($loadthemebody=='skin-blue')         { $themecolorcode="#397CA5"; $color1="#6497B1"; $color2="#005B96"; $color3="#03396C"; $color4="#011F4B"; }

        if($loadthemebody=='skin-green-light')  { $themecolorcode="#00A65A"; $color1="#49AB81"; $color2="#419873"; $color3="#398564"; $color4="#317256"; }
        if($loadthemebody=='skin-green')        { $themecolorcode="#00A65A"; $color1="#49AB81"; $color2="#419873"; $color3="#398564"; $color4="#317256"; }

        if($loadthemebody=='skin-purple-light') { $themecolorcode="#545096"; $color1="#572985"; $color2="#402985"; $color3="#292985"; $color4="#22226E"; }
        if($loadthemebody=='skin-purple')       { $themecolorcode="#545096"; $color1="#572985"; $color2="#402985"; $color3="#292985"; $color4="#22226E"; }

        if($loadthemebody=='skin-red-light')    { $themecolorcode="#DD4B39"; $color1="#FF5733"; $color2="#E53935"; $color3="#C70039"; $color4="#9E1B08"; }
        if($loadthemebody=='skin-red')          { $themecolorcode="#DD4B39"; $color1="#FF5733"; $color2="#E53935"; $color3="#C70039"; $color4="#9E1B08"; }

        if($loadthemebody=='skin-yellow-light') { $themecolorcode="#F39C12"; $color1="#FFCF75"; $color2="#FFB38A"; $color3="#FF9248"; $color4="#FDA63A"; }
        if($loadthemebody=='skin-yellow')       { $themecolorcode="#F39C12"; $color1="#FFCF75"; $color2="#FFB38A"; $color3="#FF9248"; $color4="#FDA63A"; }

        $data['THEMECOLORCODE']=$themecolorcode;
        $this->session->set_userdata('THEMECOLORCODE',$themecolorcode);
        $data['COLOR1']=$color1;
        $data['COLOR2']=$color2;
        $data['COLOR3']=$color3;
        $data['COLOR4']=$color4;
        $data['BOXSHADOW']='-webkit-box-shadow: 0px 0px 16px -2px rgba(143,141,143,0.61) !important;
        -moz-box-shadow: 0px 0px 16px -2px rgba(143,141,143,0.61) !important;
        box-shadow: 0px 0px 16px -2px rgba(143,141,143,0.61) !important;';

        $data['language_info'] = $this->_language_list();
        
        $this->load->view('admin/theme/theme', $data);
    }


    public function login() //loads home view page after login (this )
    {
        if ($this->session->userdata('logged_in')==1) {
            redirect('dashboard/index', 'location');
        }
        
        $this->form_validation->set_rules('username', '<b>'.$this->lang->line('Username').'</b>', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', '<b>'.$this->lang->line('Password').'</b>', 'trim|required|xss_clean');
                
        if ($this->form_validation->run() == false) {
            $this->load->view('page/login');
        } //if validation test becomes false,reloads it

        else {
            $username= strip_tags($this->input->post("username", true));
            $password=md5(strip_tags($this->input->post("password", true)));


            $table='users';
            $where_simple=array('username'=>$username,'password'=>$password,'users.status'=>"1");
            $where=array('where'=>$where_simple);
            $select = array('users.*');
            $info=$this->basic->get_data($table, $where, $select, $join='', $limit='', $start='', $order_by='', $group_by='', $num_rows=1);
         
            $count=$info['extra_index']['num_rows'];

            if ($count==0) {
                $this->session->set_flashdata('login_msg', 'Invalid username or password');
                redirect(uri_string());
            } else {
                unset($info['extra_index']);
                
                foreach ($info as $row) {
                    $user_id=$row['id'];
                    $username=$row['username'];
                    $user_type=$row['user_type'];
                    $first_name=$row['first_name'];
                    $last_name=$row['last_name'];
                }
    
                $this->session->set_userdata('logged_in', 1);
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('user_type', $user_type);
                $this->session->set_userdata('user_real_name', $first_name." ".$last_name);

                redirect('dashboard/index', 'location');
            }
        }
    }



    public function logout()
    {
        $this->session->sess_destroy();
        redirect('home/login', 'location');
    }

    

    function _mail_sender($from = '', $to = '', $subject = '', $message = '', $mask = "", $html = 0, $smtp = 1)
    {
        if ($to!= '' && $subject!='' && $message!= '') 
        { 
            if($this->config->item('email_sending_option') == '') $email_sending_option = 'smtp';
            else $email_sending_option = $this->config->item('email_sending_option');

            if($from!="")
                $message=$message."<br><br> The email was sent by : ".$from;

            if($email_sending_option == "smtp")
            {
                if ($smtp == '1') {
                    $where2 = array("where" => array('status' => '1','deleted' => '0'));
                    $email_config_details = $this->basic->get_data("forget_password_config", $where2, $select = '', $join = '', $limit = '', $start = '',
                                                            $group_by = '', $num_rows = 0);

                    if (count($email_config_details) == 0) {
                        $this->load->library('email');
                    } else {
                        foreach ($email_config_details as $send_info) {
                            $send_email = trim($send_info['email_address']);
                            $smtp_host = trim($send_info['smtp_host']);
                            $smtp_port = trim($send_info['smtp_port']);
                            $smtp_user = trim($send_info['smtp_user']);
                            $smtp_password = trim($send_info['smtp_password']);
                            $smtp_type = trim($send_info['smtp_type']);
                        }

                        /*****Email Sending Code ******/
                        $config = array(
                          'protocol' => 'smtp',
                          'smtp_host' => "{$smtp_host}",
                          'smtp_port' => "{$smtp_port}",
                          'smtp_user' => "{$smtp_user}", // change it to yours
                          'smtp_pass' => "{$smtp_password}", // change it to yours
                          'mailtype' => 'html',
                          'charset' => 'utf-8',
                          'newline' =>  "\r\n",
                          'smtp_timeout' => '30'
                         );
                        if($smtp_type != 'Default')
                            $config['smtp_crypto'] = $smtp_type;

                        $this->load->library('email', $config);
                    }
                } /*** End of If Smtp== 1 **/

                if (isset($send_email) && $send_email!= "") {
                    $from = $send_email;
                }
                $this->email->from($from, $mask);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                if ($html == 1) {
                    $this->email->set_mailtype('html');
                }

                if ($this->email->send()) {
                    return true;
                } else {
                    return false;
                }
            }

            if($email_sending_option == 'php_mail')
            {
                $from=$this->config->item('institute_email');
                if($from=="")
                {
                    $from = get_domain_only(base_url());
                    $from = "support@".$from;
                }
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= "From: {$from}" . "\r\n";
                if(mail($to, $subject, $message, $headers))
                    return true;
                else
                    return false;
            }

        } else {
            return false;
        }
    }


    public function decode_url()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return false;
        }
        echo urldecode($this->input->post("message"));
    }

    public function decode_html()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return false;
        }
        echo html_entity_decode($this->input->post("message"));
    }

    public function decode_html_send_as() // used in from email show (not used for message diplay anymore)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            exit();
        }

        $str=$this->input->post("message");
        $api_id=$this->input->post("api_id");
        $configure_table_name=$this->input->post("configure_table_name");

        $return_array=array();
        if ($str=="" || $api_id=="" || $configure_table_name=="") {
            $return_array["decode"]="";
            $return_array["send_as"]="";
            echo json_encode($return_array);
            exit();
        }

        $return_array['decode']=html_entity_decode($str);

        if ($configure_table_name=="email_mailgun_config") {
            $type="Mailgun";
        } elseif ($configure_table_name=="email_mandrill_config") {
            $type="Mandrill";
        } elseif ($configure_table_name=="email_sendgrid_config") {
            $type="SendGrid";
        } else {
            $type="SMTP";
        }

        $temp=$this->basic->get_data($configure_table_name, $where=array("where"=>array("id"=>$api_id)), $select=array("email_address"));
        $send_as=$type." : ". $temp[0]['email_address'];
        $return_array['send_as']=$send_as;
        echo json_encode($return_array);
    }
        
    public function _random_number_generator($length=6)
    {
        $rand = substr(uniqid(mt_rand(), true), 0, $length);
        return $rand;
    }

    public function forgot_password()
    {
        $data['body']='page/forgot_password';
        $data['page_title']="Password Recovery";
        $this->_front_viewcontroller($data);
    }

    public function code_genaration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $email= strip_tags(trim($this->input->post('email', true)));
        $result=$this->basic->get_data('users', array('where'=>array('email'=>$email)), array('count(*) as num'));
        
        if ($result[0]['num']==1) {
            //entry to forget_password table
            $expiration=date("Y-m-d H:i:s", strtotime('+1 day', time()));
            $code=$this->_random_number_generator();
            $url=site_url().'home/password_recovery';

            $table='forget_password';
            $info=array(
                'confirmation_code'=>$code,
                'email'=>$email,
                'expiration'=>$expiration
                );
            if ($this->basic->insert_data($table, $info)) {
                //email to user
                $message="<p>".$this->lang->line("to reset your password please perform the").":</p>
                            <ol>
                                <li>".$this->lang->line("go to this url").": ".$url."</li>
                                <li>".$this->lang->line("enter this code").": ".$code."</li>
                                <li>".$this->lang->line("Reset Password")."</li>
                            <ol>
                            <h4>".$this->lang->line("link and code will be expired after 24 hours")."</h4>";

                
                $from=$this->config->item('institute_email');
                $to=$email;
                $subject=$this->config->item('product_name')." | ".$this->lang->line("Reset Password");
                $mask=$subject;
                $html=1;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html);
            }
        } else {
            echo 0;
        }
    }

    public function password_recovery()
    {
        $data['body']='page/password_recovery';
        $data['page_title']="Password Recovery";
        $this->_front_viewcontroller($data);
    }


    public function recovery_check()
    {
        if ($_POST) {
            $code= strip_tags(trim($this->input->post('code', true)));
            $newp=md5(strip_tags(trim($this->input->post('newp', true))));
            $conf=md5(strip_tags(trim($this->input->post('conf', true))));

            $table='forget_password';
            $where['where']=array('confirmation_code'=>$code,'success'=>0);
            $select=array('email','expiration');

            $result=$this->basic->get_data($table, $where, $select);

            if (empty($result)) {
                echo 0;
            } else {
                foreach ($result as $row) {
                    $email=$row['email'];
                    $expiration=$row['expiration'];
                }

                $now=time();
                $exp=strtotime($expiration);

                if ($now>$exp) {
                    echo 1;
                } else {
                    $this->basic->update_data('users', array('email'=>$email), array('password'=>$newp));
                    $this->basic->update_data('forget_password', array('confirmation_code'=>$code), array('success'=>1));
                    echo 2;
                }
            }
        }
    }


    public function time_zone_drop_down($datavalue = '', $primary_key = null) // return HTML select
    {
        $all_time_zone = $this->_time_zone_list();
                                        
        $str = "<select name='time_zone' id='time_zone' class='form-control'>";
        $str.= "<option value=>Please Select a Time Zone</option>";

        foreach ($all_time_zone as $zone_name=>$value) {
            if ($primary_key!= null) {
                if ($zone_name==$datavalue) {
                    $selected=" selected = 'selected' ";
                } else {
                    $selected="";
                }
            } else {
                if ($zone_name==$this->config->item("time_zone")) {
                    $selected=" selected = 'selected' ";
                } else {
                    $selected="";
                }
            }
            $str.= "<option ".$selected." value='$zone_name'>{$zone_name}</option>";
        }
        $str.= "</select>";
        return $str;
    }


    public function _time_zone_list() // return only options
    {
        $all_time_zone = array(
            'Kwajalein'                     => 'GMT -12.00 Kwajalein',
            'Pacific/Midway'                 => 'GMT -11.00 Pacific/Midway',
            'Pacific/Honolulu'                 => 'GMT -10.00 Pacific/Honolulu',
            'America/Anchorage'             => 'GMT -9.00  America/Anchorage',
            'America/Los_Angeles'             => 'GMT -8.00  America/Los_Angeles',
            'America/Denver'                 => 'GMT -7.00  America/Denver',
            'America/Tegucigalpa'             => 'GMT -6.00  America/Tegucigalpa',
            'America/New_York'                 => 'GMT -5.00  America/New_York',
            'America/Caracas'                 => 'GMT -4.30  America/Caracas',
            'America/Halifax'                 => 'GMT -4.00  America/Halifax',
            'America/St_Johns'                 => 'GMT -3.30  America/St_Johns',
            'America/Argentina/Buenos_Aires' => 'GMT +-3.00 America/Argentina/Buenos_Aires',
            'America/Sao_Paulo'             =>' GMT -3.00  America/Sao_Paulo',
            'Atlantic/South_Georgia'         => 'GMT +-2.00 Atlantic/South_Georgia',
            'Atlantic/Azores'                 => 'GMT -1.00  Atlantic/Azores',
            'Europe/Dublin'                 => 'GMT        Europe/Dublin',
            'Europe/Belgrade'                 => 'GMT +1.00  Europe/Belgrade',
            'Europe/Minsk'                     => 'GMT +2.00  Europe/Minsk',
            'Asia/Kuwait'                     => 'GMT +3.00  Asia/Kuwait',
            'Asia/Tehran'                     => 'GMT +3.30  Asia/Tehran',
            'Asia/Muscat'                     => 'GMT +4.00  Asia/Muscat',
            'Asia/Yekaterinburg'             => 'GMT +5.00  Asia/Yekaterinburg',
            'Asia/Kolkata'                     => 'GMT +5.30  Asia/Kolkata',
            'Asia/Katmandu'                 => 'GMT +5.45  Asia/Katmandu',
            'Asia/Dhaka'                     => 'GMT +6.00  Asia/Dhaka',
            'Asia/Rangoon'                     => 'GMT +6.30  Asia/Rangoon',
            'Asia/Krasnoyarsk'                 => 'GMT +7.00  Asia/Krasnoyarsk',
            'Asia/Brunei'                     => 'GMT +8.00  Asia/Brunei',
            'Asia/Seoul'                     => 'GMT +9.00  Asia/Seoul',
            'Australia/Darwin'                 => 'GMT +9.30  Australia/Darwin',
            'Australia/Canberra'             => 'GMT +10.00 Australia/Canberra',
            'Asia/Magadan'                     => 'GMT +11.00 Asia/Magadan',
            'Pacific/Fiji'                     => 'GMT +12.00 Pacific/Fiji',
            'Pacific/Tongatapu'             => 'GMT +13.00 Pacific/Tongatapu'
        );
        return $all_time_zone;
    }


    public function get_contact_types()
    {
        $where=array('where'=>array('contact_type.user_id'=>$this->user_id));
        $contact_types=$this->basic->get_data('contact_type', $where, $select='', $join='', $limit='', $start='', $order_by='contact_type.type', $group_by='', $num_rows=0);
        
        $contact_info=array();
        foreach ($contact_types as $details) {
            $contact_type_id=$details['id'];
            $contact_type_name= $details['type'];
            $contact_info[$contact_type_id]=$contact_type_name;
        }
        return $contact_info;
    }

    public function attachment_downloader($file_name="")
    {
        if ($file_name=="") {
            redirect('home/access_forbidden', 'location');
        }
        $file_name='upload/attachment/'.$file_name;
        $data['file_name']=$file_name;
        $this->load->view("page/download", $data);
    }

    public function sign_up()
    {
        // $this->load->view('page/sign_up');
        $data['body'] = 'page/sign_up';
        $data['page_title']="Sign Up";
        $this->_front_viewcontroller($data);
    }

    public function sign_up_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if($_POST) {
  
            $this->form_validation->set_rules('name', '<b>'.$this->lang->line("name").'</b>', 'trim|required');
            $this->form_validation->set_rules('username', '<b>'.$this->lang->line("username").'</b>', 'trim|required|is_unique[users.username]');
            $this->form_validation->set_rules('email', '<b>'.$this->lang->line("email").'</b>', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('mobile', '<b>'.$this->lang->line("mobile").'</b>', 'trim|required|is_unique[users.mobile]');
            $this->form_validation->set_rules('password', '<b>'.$this->lang->line("password").'</b>', 'trim|required');
            $this->form_validation->set_rules('confirm_password', '<b>'.$this->lang->line("confirm password").'</b>', 'trim|required|matches[password]');

            if($this->form_validation->run() == FALSE){
                $this->sign_up();
            } else {
                $name = $this->input->post('name', TRUE);
                $username = $this->input->post('username', TRUE);
                $email = $this->input->post('email', TRUE);
                $mobile = $this->input->post('mobile', TRUE);
                $password = $this->input->post('password', TRUE);

                // $this->db->trans_start();

                $code = $this->_random_number_generator();
                $data = array(
                    'first_name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'username' => $username,
                    'password' => md5($password),
                    'user_type' => 'Member',
                    'status' => '0',
                    'activation_code' => $code
                    );

                if ($this->basic->insert_data('users', $data)) {
                    //email to user
                    $url = site_url()."home/account_activation";
                    $message = "<p>".$this->lang->line("to activate your account please perform the following steps")."</p>
                                <ol>
                                    <li>".$this->lang->line("go to this url").": ".$url."</li>
                                    <li>".$this->lang->line("enter this code").": ".$code."</li>
                                    <li>".$this->lang->line("activate your account")."</li>
                                <ol>";


                    $from = $this->config->item('institute_email');
                    $to = $email;
                    $subject = $this->config->item('product_name')." | ".$this->lang->line("account activation");
                    $mask = $subject;
                    $html = 1;
                    $this->_mail_sender($from, $to, $subject, $message, $mask, $html);

                    $this->session->set_userdata('reg_success',1);
                    return $this->sign_up();

                }

            }

        }
    }

    public function account_activation()
    {
        $data['body']='page/account_activation';
        $data['page_title']="Account Activation";
        $this->_front_viewcontroller($data);
    }

    public function account_activation_action()
    {
        if ($_POST) {
            $code=trim($this->input->post('code', true));
            $email=$this->input->post('email', true);

            $table='users';
            $where['where']=array('activation_code'=>$code,'email'=>$email);
            $select=array('id');

            $result=$this->basic->get_data($table, $where, $select);

            if (empty($result)) {
                echo 0;
            } else {
                foreach ($result as $row) {
                    $user_id=$row['id'];
                }

                $this->basic->update_data('users', array('id'=>$user_id), array('status'=>'1'));
                echo 2;
                
            }
        }
    }


    // ***************************************************************** //

    function get_general_content($url,$proxy=""){
            
            
            $ch = curl_init(); // initialize curl handle
           /* curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method

         
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
            
            $content = curl_exec($ch); // run the whole process
            
            curl_close($ch);
            
            return $content;
            
    }
    

    public function important_feature(){

            //  if(file_exists(APPPATH.'config/licence.txt') && file_exists(APPPATH.'core/licence.txt')){
            //     $config_existing_content = file_get_contents(APPPATH.'config/licence.txt');
            //     $config_decoded_content = json_decode($config_existing_content, true);

            //     $core_existing_content = file_get_contents(APPPATH.'core/licence.txt');
            //     $core_decoded_content = json_decode($core_existing_content, true);

            //     if($config_decoded_content['is_active'] != md5($config_decoded_content['purchase_code']) || $core_decoded_content['is_active'] != md5(md5($core_decoded_content['purchase_code']))){
            //       redirect("home/credential_check", 'Location');
            //     }
                
            // } else {
            //     redirect("home/credential_check", 'Location');
            // }


    }


    public function credential_check()
    {
        if($this->session->userdata("user_type")!="Admin")redirect('home/access_forbidden', 'location');

        $data['body'] = 'front/credential_check';
        $data['page_title'] = "Credential Check";
        $this->_front_viewcontroller($data);
    }

    public function credential_check_action()
    {
        $domain_name = $this->input->post("domain_name",true);
        $purchase_code = $this->input->post("purchase_code",true);
        $only_domain = get_domain_only($domain_name);
        // $only_domain = "xeroneit.ne";
       
       $response=$this->code_activation_check_action($purchase_code,$only_domain);

       echo $response;

    }


    

    public function code_activation_check_action($purchase_code,$only_domain){

         $url = "http://xeroneit.net/development/envato_license_activation/purchase_code_check.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=ssem";

        $credentials = $this->get_general_content($url);
        $decoded_credentials = json_decode($credentials);
        if($decoded_credentials->status == 'success'){
            $content_to_write = array(
                'is_active' => md5($purchase_code),
                'purchase_code' => $purchase_code,
                'item_name' => $decoded_credentials->item_name,
                'buy_at' => $decoded_credentials->buy_at,
                'licence_type' => $decoded_credentials->license,
                'domain' => $only_domain,
                'checking_date'=>date('Y-m-d')
                );
            $config_json_content_to_write = json_encode($content_to_write);
            file_put_contents(APPPATH.'config/licence.txt', $config_json_content_to_write, LOCK_EX);

            $content_to_write['is_active'] = md5(md5($purchase_code));
            $core_json_content_to_write = json_encode($content_to_write);
            file_put_contents(APPPATH.'core/licence.txt', $core_json_content_to_write, LOCK_EX);

            return json_encode("success");

        } else {
            if(file_exists(APPPATH.'core/licence.txt')) unlink(APPPATH.'core/licence.txt');
            return json_encode($decoded_credentials);
        }
    }

    public function periodic_check(){

        $today= date('d');

        if($today%7==0){

          if(file_exists(APPPATH.'config/licence.txt') && file_exists(APPPATH.'core/licence.txt')){
                $config_existing_content = file_get_contents(APPPATH.'config/licence.txt');
                $config_decoded_content = json_decode($config_existing_content, true);
                $last_check_date= $config_decoded_content['checking_date'];
                $purchase_code  = $config_decoded_content['purchase_code'];
                $base_url = base_url();
                $domain_name    = get_domain_only($base_url);

                if( strtotime(date('Y-m-d')) != strtotime($last_check_date)){
                    $this->code_activation_check_action($purchase_code,$domain_name);         
                }
        }
     }
  }



    function _language_list() 
     {
        
        //$img_tag = '<img style="height: 15px; width: 20px;" src="'.$url.'BN.png" alt="flag" />';
         $language = array
         (
            "bengali"=>'Bengali',            
            "dutch"=>'Dutch',
            "english"=>"English",
            "french"=>"French",
            "german"=>"German",
            "greek"=>"Greek",
            "italian"=>"Italian",            
            "portuguese"=>"Portuguese",
            "russian"=>"Russian",
            "spanish"=>"Spanish",
            "turkish" => "Turkish"
         );
         // print_r($language);
         return $language;
     }

     public function _theme_list()
     {
         $myDir = 'css/skins';
         $file_list = $this->_scanAll($myDir);
         $theme_list=array();
         foreach ($file_list as $file) {
             $i = 0;
             $one_list[$i] = $file['file'];
             $one_list[$i]=str_replace("\\", "/",$one_list[$i]);
             $one_list_array = explode("/",$one_list[$i]);
             $theme=array_pop($one_list_array);
             $pos=strpos($theme, '.min.css');
             if($pos!==FALSE) continue; // only loading unminified css
             if($theme=="_all-skins.css") continue;  // skipping large css file that includes all file
             $theme_name=str_replace('.css','', $theme);
             $theme_display=str_replace(array('skin-','.css','-'), array('','',' '), $theme);
             if($theme_display=="black light") $theme_display='white';
             $theme_list[$theme_name]=ucwords($theme_display);
         }
         return $theme_list;
         
     }

     public function _theme_list_front()
     {
         return array
         (
             "white"=>"Light",
             "black"=>"Dark",
             "blue"=>"Blue",
             "green"=>"Green",
             "purple"=>"Purple",
             "red"=>"Red",
             "yellow"=>"Yellow"
         );
     }



    public function allow_cookie()
    {
         $this->session->set_userdata('allow_cookie','yes');
    }

    public function privacy_policy()
    {
         $data['page_title'] = 'Privacy Policy';
         $data['body'] = 'front/privacy_policy';
         $this->_front_viewcontroller($data);
    }

    public function terms_use()
    {
         $data['page_title'] = 'Terms of Use';
         $data['body'] = 'front/terms_use';
         $this->_front_viewcontroller($data);
    }


    

    public function unsubscribe($contact_id,$email)
    {
        if($contact_id == '' || $email == '') exit;
        
        $data = array();
        $data['contact_id'] = isset($contact_id) ? $contact_id:"";
        $data['email_address'] = isset($email) ? urldecode($email):"";
        $info = $this->basic->get_data("contacts", array('where'=>array("id"=>$contact_id, "email"=>urldecode($email))));

        if(isset($info) && !empty($info))
        {
            if(isset($info) && isset($info[0]['unsubscribed']) && $info[0]['unsubscribed'] =="0")
            {
                $data['status'] = "0";
            } else
            {
                $data['status'] = "1";
            }

            $this->load->view("my_email/schedule_email/unsubscribed_message",$data);

        } else {
            redirect('home/access_forbidden', 'location');
        }
    }



    public function unsubscribe_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $result = array();

        $contactid = trim($this->input->post("contactid",true));
        $email_address = trim($this->input->post("email",true));
        $btntype = trim($this->input->post("btntype",true));

        if(isset($btntype) && !empty($btntype) && $btntype == "unsub")
        {
            if($this->basic->update_data("contacts", array("id"=>$contactid,"email"=>$email_address,"deleted"=>"0"), array("unsubscribed"=>"1")))
            {
                echo "1";
            } else
            {
                echo "0";
            }

        } else if(isset($btntype) && !empty($btntype) && $btntype == "sub")
        {
            if($this->basic->update_data("contacts", array("id"=>$contactid,"email"=>$email_address,"deleted"=>"0"), array("unsubscribed"=>"0")))
            {
                echo "1";

            } else
            {
                echo "0";
            }

        }
        
    }





  


}


