<?php
require_once("Home.php");

class Setting extends Home
{
    public $user_id;
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('home/login', 'location');
        }
        if ($this->session->userdata('user_type') != 'Admin') {
            redirect('home/login', 'location');
        }

        $this->important_feature();
        $this->periodic_check();
    }


    public function index()
    {
        $this->configuration();
    }

    public function configuration()
    {
        $data['body'] = "admin/config/edit_config";
        $data['page_title'] = 'General Settings';
        $data['time_zone'] = $this->_time_zone_list();
        $data["themes"] = $this->_theme_list();     
        $this->_viewcontroller($data);
    }

    public function edit_config()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if ($_POST) {
            // validation
            $this->form_validation->set_rules('institute_name',       '<b>'.$this->lang->line("Company Name").'</b>',       'trim|required');
            $this->form_validation->set_rules('institute_address',    '<b>'.$this->lang->line("Company Address").'</b>',    'trim');
            $this->form_validation->set_rules('institute_email',      '<b>'.$this->lang->line("Company Email").'</b>',      'trim|valid_email');
            $this->form_validation->set_rules('product_name',      '<b>'.$this->lang->line("Product Name").'</b>',      'trim');
            $this->form_validation->set_rules('institute_mobile',     '<b>'.$this->lang->line("Company Mobile").'</b>',     'trim');
            $this->form_validation->set_rules('time_zone',            '<b>'.$this->lang->line("Time Zone").'</b>',          'trim');
            $this->form_validation->set_rules('language',            '<b>'.$this->lang->line("Language").'</b>',           'trim');
            $this->form_validation->set_rules('theme',            '<b>'.$this->lang->line("Theme").'</b>',           'trim');
            $this->form_validation->set_rules('force_https',  '<b>'.$this->lang->line("Force HTTPS").'</b>','trim');
            $this->form_validation->set_rules('email_sending_option',  '<b>'.$this->lang->line("Email sending option").'</b>','trim');

            $this->form_validation->set_rules('number_of_sms_to_be_sent_in_try','<b>'.$this->lang->line("Number Of SMS Send Per Cron Job").'</b>','trim');
            $this->form_validation->set_rules('update_sms_sending_report_after_time','<b>'.$this->lang->line("SMS Sending Report Update Frequency").'</b>','trim');
            $this->form_validation->set_rules('number_of_email_to_be_sent_in_try','<b>'.$this->lang->line("Number Of Email Send Per Cron Job").'</b>','trim');
            $this->form_validation->set_rules('update_email_sending_report_after_time','<b>'.$this->lang->line("Email Sending Report Update Frequency").'</b>','trim');
            $this->form_validation->set_rules('sms_api_access','<b>'.$this->lang->line("SMS API Access").'</b>','trim');
            $this->form_validation->set_rules('email_api_access','<b>'.$this->lang->line("Email API Access").'</b>','trim');

           

            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) {
                return $this->configuration();
            } else {
                // assign
                $institute_name= addslashes(strip_tags($this->input->post('institute_name', true)));
                $institute_address= addslashes(strip_tags($this->input->post('institute_address', true)));
                $institute_email= addslashes(strip_tags($this->input->post('institute_email', true)));
                $product_name= addslashes(strip_tags($this->input->post('product_name', true)));
                $institute_mobile= addslashes(strip_tags($this->input->post('institute_mobile', true)));
                $time_zone= addslashes(strip_tags($this->input->post('time_zone', true)));                               
                $language=addslashes(strip_tags($this->input->post('language', true)));
                $theme=addslashes(strip_tags($this->input->post('theme', true)));
                $force_https=addslashes(strip_tags($this->input->post('force_https', true)));
                $email_sending_option=addslashes(strip_tags($this->input->post('email_sending_option', true)));
                $sms_api_access=addslashes(strip_tags($this->input->post('sms_api_access', true)));
                $email_api_access=addslashes(strip_tags($this->input->post('email_api_access', true)));

                $number_of_sms_to_be_sent_in_try = addslashes(strip_tags($this->input->post('number_of_sms_to_be_sent_in_try', true)));
                $update_sms_sending_report_after_time = addslashes(strip_tags($this->input->post('update_sms_sending_report_after_time', true)));
                $number_of_email_to_be_sent_in_try = addslashes(strip_tags($this->input->post('number_of_email_to_be_sent_in_try', true)));
                $update_email_sending_report_after_time = addslashes(strip_tags($this->input->post('update_email_sending_report_after_time', true)));

                $base_path=realpath(APPPATH . '../assets/images');

                $this->load->library('upload');

                if ($_FILES['logo']['size'] != 0) {
                    $photo = "logo.png";
                    $config = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '200',
                        'max_width' => '600',
                        'max_height' => '300'
                        );

                    $this->upload->initialize($config);
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('logo')) {
                        $this->session->set_userdata('logo_error', $this->upload->display_errors());
                        return $this->configuration();
                    }
                }

                if ($_FILES['favicon']['size'] != 0) {
                    $photo2 = "favicon.png";
                    $config2 = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo2,
                        'max_size' => '50',
                        'max_width' => '32',
                        'max_height' => '32'
                        );
                    $this->upload->initialize($config2);
                    $this->load->library('upload', $config2);

                    if (!$this->upload->do_upload('favicon')) {
                        $this->session->set_userdata('favicon_error', $this->upload->display_errors());
                        return $this->configuration();
                    }
                }

                // writing application/config/my_config
                $app_my_config_data = "<?php ";
                $app_my_config_data.= "\n\$config['default_page_url'] = '".$this->config->item('default_page_url')."';\n";
                $app_my_config_data.= "\$config['product_name'] = '$product_name';\n";
                $app_my_config_data.= "\$config['product_short_name'] = '".$this->config->item('product_short_name')."' ;\n";
                $app_my_config_data.= "\$config['product_version'] = '".$this->config->item('product_version')." ';\n\n";
                $app_my_config_data.= "\$config['institute_address1'] = '$institute_name';\n";
                $app_my_config_data.= "\$config['institute_address2'] = '$institute_address';\n";
                $app_my_config_data.= "\$config['institute_email'] = '$institute_email';\n";
                $app_my_config_data.= "\$config['institute_mobile'] = '$institute_mobile';\n\n";
                $app_my_config_data.= "\$config['developed_by'] = '".$this->config->item('developed_by')."';\n";
                $app_my_config_data.= "\$config['developed_by_href'] = '".$this->config->item('developed_by_href')."';\n";
                $app_my_config_data.= "\$config['developed_by_title'] = '".$this->config->item('developed_by_title')."';\n";
                $app_my_config_data.= "\$config['developed_by_prefix'] = '".$this->config->item('developed_by_prefix')."' ;\n";
                $app_my_config_data.= "\$config['support_email'] = '".$this->config->item('support_email')."' ;\n";
                $app_my_config_data.= "\$config['support_mobile'] = '".$this->config->item('support_mobile')."' ;\n";
                $app_my_config_data.= "\$config['time_zone'] = '$time_zone';\n";                               
                $app_my_config_data.= "\$config['language'] = '$language';\n";
                $app_my_config_data.= "\$config['force_https'] = '".$force_https."';\n";
                $app_my_config_data.= "\$config['email_sending_option'] = '".$email_sending_option."';\n";
                $app_my_config_data.= "\$config['theme'] = '".$theme."';\n\n";

                if($number_of_sms_to_be_sent_in_try != "")
                    $app_my_config_data.= "\$config['number_of_sms_to_be_sent_in_try'] = $number_of_sms_to_be_sent_in_try;\n";

                if($update_sms_sending_report_after_time != "")
                    $app_my_config_data.= "\$config['update_sms_sending_report_after_time'] = $update_sms_sending_report_after_time;\n\n";

                if($number_of_email_to_be_sent_in_try != "")
                    $app_my_config_data.= "\$config['number_of_email_to_be_sent_in_try'] = $number_of_email_to_be_sent_in_try;\n";

                if($update_email_sending_report_after_time != "")
                    $app_my_config_data.= "\$config['update_email_sending_report_after_time'] = $update_email_sending_report_after_time;\n\n";

                $app_my_config_data.= "\$config['sms_api_access'] = '".$sms_api_access."';\n";
                $app_my_config_data.= "\$config['email_api_access'] = '".$email_api_access."';\n\n";
                $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
                $app_my_config_data.= "\$config['sess_table_name'] = 'ci_sessions';\n";

                file_put_contents(APPPATH.'config/my_config.php', $app_my_config_data, LOCK_EX);
                //writting  application/config/my_config

                $this->session->set_flashdata('success_message', 1);
                redirect('setting/configuration', 'location');
            }
        }
    }

    // ======================= Frontend settings ===============================

    public function frontend_configuration()
    {
        // if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        //     redirect('home/access_forbidden', 'location');
        // }
        
        $data['body'] = "admin/config/frontend_config";
        $data['time_zone'] = $this->_time_zone_list();        
        $data['language_info'] = $this->_language_list();
        $data['page_title'] = $this->lang->line('front-end settings');
        $this->_viewcontroller($data);
    }


    public function frontend_configuration_action()
    {
        if($this->is_demo == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') { redirect('home/access_forbidden', 'location'); }

        if ($_POST) 
        {
            $post=$_POST;
            foreach ($post as $key => $value) 
            {
                $$key = addslashes(strip_tags($this->input->post($key,TRUE)));
            }


            //review section
            $customer_review = array();
            $total_item      = $this->config->item('customer_review');

            $review_string = "array".'('."\n";

            for($i = 1; $i <= count($total_item); $i++) {
                $j = $i-1;
                $var1 = "reviewer".$i;
                $var2 = "designation".$i;
                $var3 = "pic".$i;
                $var4 = "description".$i;

                $customer_review[$j][$var1] = $$var1;
                $customer_review[$j][$var2] = $$var2;
                $customer_review[$j][$var3] = $$var3;
                $customer_review[$j][$var4] = $$var4;

                $review_string.= "   "."'{$j}'=> array(\n"."       "."'".$$var1."',\n"."       "."'".$$var2."',\n"."       "."'".$$var3."',\n"."       "."'".$$var4."',\n"."    "."),\n";
            }

            $review_string.=")";

            
            // writing application/config/my_config
            $app_frontend_config_data = "<?php ";
            $app_frontend_config_data.= "\n\$config['theme_front'] = '".$theme_front."';\n";
            $app_frontend_config_data.= "\$config['display_landing_page'] = '".$display_landing_page."';\n";
            $app_frontend_config_data.= "\$config['facebook'] = '$facebook_link';\n";
            $app_frontend_config_data.= "\$config['twitter'] = '$twitter_link';\n";
            $app_frontend_config_data.= "\$config['linkedin'] = '$linkedin_link';\n";
            $app_frontend_config_data.= "\$config['youtube'] = '$youtube_link';\n";
            $app_frontend_config_data.= "\$config['display_review_block'] = '$display_review_block';\n";
            $app_frontend_config_data.= "\$config['promo_video'] = '$promo_video';\n";
            $app_frontend_config_data.= "\$config['customer_review_video'] = '$customer_review_video';\n";
            $app_frontend_config_data.= "\$config['customer_review'] = ".$review_string.";\n";

            file_put_contents(APPPATH.'config/frontend_config.php', $app_frontend_config_data, LOCK_EX);
            $this->session->set_flashdata('success_message', 1);
            redirect('setting/frontend_configuration', 'location');

        }
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
}
