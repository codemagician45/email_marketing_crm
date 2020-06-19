<?php require_once("Home.php"); // including home controller

class Site extends Home
{
    
    /**
    * load constructor
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);

    }

    public function index()
    {
        $config_data=array();
        $data=array();
        $price=0;
        $config_data=$this->basic->get_data("payment_config","","monthly_fee");
        if(array_key_exists(0,$config_data)) $price=$config_data[0]['monthly_fee'];
        $data['price']=$price;      
        $data['language_info'] = $this->_language_list();

        $data['contact_num1']=$this->_random_number_generator(2);
        $data['contact_num2']=$this->_random_number_generator(1);
        $contact_captcha= $data['contact_num1']+ $data['contact_num2'];
        $this->session->set_userdata("contact_captcha",$contact_captcha);

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

        $display_landing_page=$this->config->item('display_landing_page');

        if($display_landing_page=='') $display_landing_page='0';

        if($display_landing_page=='0')
            $this->login();
        else 
            $this->load->view('website/index',$data);

        
    }


    public function email_contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if ($_POST)
        {
            $redirect_url=site_url("site#contact");

            $this->form_validation->set_rules('email',                    '<b>'.$this->lang->line("email").'</b>',              'trim|required|valid_email');
            $this->form_validation->set_rules('subject',                  '<b>'.$this->lang->line("message subject").'</b>',            'trim|required');
            $this->form_validation->set_rules('message',                  '<b>'.$this->lang->line("message").'</b>',            'trim|required');
            $this->form_validation->set_rules('captcha',                  '<b>'.$this->lang->line("captcha").'</b>',            'trim|required|integer');

            if ($this->form_validation->run() == false)
            {
                return $this->index();
            }
            else
            {
                $captcha = $this->input->post('captcha', TRUE);

                if($captcha!=$this->session->userdata("contact_captcha"))
                {
                    $this->session->set_userdata("contact_captcha_error",$this->lang->line("invalid captcha"));
                    redirect($redirect_url, 'location');
                    exit();
                }


                $email = $this->input->post('email', true);
                $subject = $this->config->item("product_name")." | ".$this->input->post('subject', true);
                $message = $this->input->post('message', true);

                $this->_mail_sender($from = $email, $to = $this->config->item("institute_email"), $subject, $message, $mask = $from,$html=1);
                $this->session->set_userdata('mail_sent', 1);

                redirect($redirect_url, 'location');
            }
        }
    }

}
