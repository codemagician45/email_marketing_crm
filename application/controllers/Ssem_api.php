<?php
require_once("Home.php");

class Ssem_api extends Home
{
    public $user_id;
    
    public function __construct()
    {
        parent::__construct();   
        $this->user_id=$this->session->userdata("user_id");
        
    }


    public function index()
    {
       $this->get_api();
    }

    public function _api_membership_validity($user_id="")
    {
        if($user_id=="") $user_id=$this->session->userdata("user_id");

        if($user_id=="") return false;

        $where['where'] = array('id'=>$user_id);
        $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date','user_type'));

        if(empty($user_expire_date)) return false;
       
        $user_type = $user_expire_date[0]['user_type'];
        if($user_type=="Admin") return true;

        $expire_date = strtotime($user_expire_date[0]['expired_date']);
        $current_date = strtotime(date("Y-m-d"));
        $payment_config=$this->basic->get_data("payment_config");
        $monthly_fee=$payment_config[0]["monthly_fee"];


        if ($expire_date < $current_date && $monthly_fee>0) return false;
        else return true;
    
    }

    public function _api_key_generator()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login', 'location');
        $val=$this->session->userdata("user_id")."-".substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 7 ).time()
        .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789') , 0 , 7 );
        return $val;
    }

    public function get_api()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login', 'location');

        $this->_api_membership_validity();

        $data['body'] = "api/ssem_api";
        $data['page_title'] = 'API';
        $api_data=$this->basic->get_data("ssem_api",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $data["api_key"]="";
        if(count($api_data)>0) $data["api_key"]=$api_data[0]["api_key"];

        if($this->is_demo =="1")
            $data['api_key'] = "xxxxxxxxxxxxxxxxxxxxx";
        
        $this->_viewcontroller($data);
    }

    public function get_api_action()
    { 
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login', 'location');

        $api_key=$this->_api_key_generator(); 
        if($this->basic->is_exist("ssem_api",array("api_key"=>$api_key)))
        $this->get_api_action();

        $user_id=$this->session->userdata("user_id");        
        if($this->basic->is_exist("ssem_api",array("user_id"=>$user_id)))
        $this->basic->update_data("ssem_api",array("user_id"=>$user_id),array("api_key"=>$api_key));
        else $this->basic->insert_data("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id));
            
        redirect('ssem_api/get_api', 'location');
    }


    public function api_key_check($api_key="")
    {
        $user_id = "";
        if($api_key != "")
        {
            $explde_api_key = explode('-',$api_key);
            $user_id="";
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }

        if($api_key=="")
        {        
            echo "API Key is required.";    
            exit();
        }

        if(!$this->basic->is_exist("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
           echo "API Key does not match with any user.";
           exit();
        }

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any authentic user.";
            exit();
        }              
    }


    public function ssem_sms_sending_command($api_key="")
    {
        $this->load->library('Sms_manager');

        $this->api_key_check($api_key);
        $number_of_sms_to_be_sent_in_try = $this->config->item("number_of_sms_to_be_sent_in_try");

        if($number_of_sms_to_be_sent_in_try == "") 
            $number_of_sms_to_be_sent_in_try = 10; // default 10
        else if($number_of_sms_to_be_sent_in_try == 0) 
            $number_of_sms_to_be_sent_in_try = ""; // 0 means unlimited

        $update_sms_sending_report_after_time = $this->config->item("update_sms_sending_report_after_time"); 

        if($update_sms_sending_report_after_time == "" || $update_sms_sending_report_after_time == 0) 
            $update_sms_sending_report_after_time = 5;

        $number_of_campaign_to_be_processed = 1; // max number of campaign that can be processed by this cron job
        // $number_of_message_tob_be_sent = 50000;  // max number of message that can be sent in an hour

        $where['or_where'] = array('posting_status'=>"0","is_try_again"=>"1");

        /****** Get all campaign from database where status=0 means pending ******/
        $join = array('users'=>'sms_sending_campaign.user_id=users.id,left');
        $campaign_info = $this->basic->get_data("sms_sending_campaign",$where,$select=array("sms_sending_campaign.*","users.deleted as user_deleted","users.status as user_status"),$join,$limit=50, $start=0, $order_by='schedule_time ASC');


        $campaign_id_array = array();  // all selected campaign id array
        $campaign_info_fildered = array(); // valid for process, campign info array

        $valid_campaign_count = 1;
        foreach($campaign_info as $info)
        {
            if($info['user_deleted'] == '1' || $info['user_status']=="0")
            {
                $this->db->where("id",$info['id']);
                $this->db->update("sms_sending_campaign",array("posting_status"=>"1","is_try_again"=>"0"));
                continue;
            } 

            $user_id       = $info["user_id"];
            $sms_api       = $info['api_id'];
            $campaign_id   = $info['id'];
            $time_zone     = $info['time_zone'];
            $schedule_time = $info['schedule_time']; 
            $total_thread  = $info["total_thread"];       

            if($time_zone) date_default_timezone_set($time_zone);            
            $now_time = date("Y-m-d H:i:s");

            if((strtotime($now_time) < strtotime($schedule_time)) && $time_zone!="") continue; 
            if($valid_campaign_count > $number_of_campaign_to_be_processed) break; 

           
            // valid campaign info and campig ids
            $campaign_info_fildered[] = $info;
            $campaign_id_array[] = $info['id']; 
            $valid_campaign_count++;      
        }

        if(count($campaign_id_array) == 0) exit();        

        $this->db->where_in("id",$campaign_id_array);
        $this->db->update("sms_sending_campaign",array("posting_status"=>"1","is_try_again"=>"0"));

        foreach($campaign_info_fildered as $info)
        {
            $i = 0;

            $campaign_id       = $info['id'];
            $user_id           = $info["user_id"]; 
            $sms_api           = $info['api_id'];
            $campaign_message  = $info['campaign_message'];  
            $successfully_sent = $info["successfully_sent"];
            $manual_phones     = explode(",",$info['manual_phone']);

            $report = json_decode($info["report"],true); // get json contact list from database and decode it
            $send_report = $report;

            // $campaign_contacts_join = array("contacts"=>"sms_sending_campaign_send.contact_id=contacts.id,left");
            // $campaign_contacts_select = array('sms_sending_campaign_send.*','contacts.id AS contactid');
            $campaign_contacts = $this->basic->get_data("sms_sending_campaign_send",array("where"=>array("campaign_id"=>$campaign_id,"processed"=>"0")),'','',$number_of_sms_to_be_sent_in_try);


            foreach ($campaign_contacts as $contacts_details) 
            {
                $send_table_id      = $contacts_details['id'];
                $contact_first_name = isset($contacts_details['contact_first_name']) ? $contacts_details['contact_first_name']:"";
                $contact_last_name  = isset($contacts_details['contact_last_name']) ? $contacts_details['contact_last_name']:"";
                $contact_email      = isset($contacts_details['contact_email']) ? $contacts_details['contact_email']:"";
                $contact_mobile     = isset($contacts_details['contact_phone_number']) ? $contacts_details['contact_phone_number']:"";
                $contact_phone      = $contacts_details['contact_phone_number'];

                $campaign_message_send   = $campaign_message;
                $campaign_message_send   = str_replace(array("#FIRST_NAME#","#firstname#"),$contact_first_name,$campaign_message_send);
                $campaign_message_send   = str_replace(array("#LAST_NAME#","#lastname#"),$contact_last_name,$campaign_message_send);
                $campaign_message_send   = str_replace(array("#MOBILE#","#mobile#"),$contact_mobile,$campaign_message_send);
                $campaign_message_send   = str_replace(array("#EMAIL_ADDRESS#","#email#"),$contact_email,$campaign_message_send);

                $message_sent_id = "";

                $this->sms_manager->set_credentioal($sms_api,$user_id);

                try
                {
                    $campaign_message_send = addslashes($campaign_message_send);
                    $response = $this->sms_manager->send_sms($campaign_message_send, $contact_phone);

                    if(isset($response['id']) && !empty($response['id']))
                    {   
                        $message_sent_id = $response['id']; 
                        $successfully_sent++; 
                    }
                    else 
                    {   if(isset($response['status']) && !empty($response['status']))
                            $message_sent_id = $response["status"];
                    }           
                    
                }
                catch(Exception $e) 
                {
                   $message_sent_id = $error_msg;
                }

                // generating new report with send message info
                $now_sent_time = date("Y-m-d H:i:s");
                $send_report[$contact_phone] = array( 
                    'sms_api_id'          => $contacts_details['sms_api_id'],
                    'contact_id'          => $contacts_details['contact_id'],
                    'contact_first_name'  => $contacts_details['contact_first_name'],
                    'contact_last_name'   => $contacts_details['contact_last_name'],
                    'contact_email'       => $contacts_details['contact_email'],
                    'contact_phone_number'=> $contact_phone,
                    'delivery_id'         => $message_sent_id,
                    'sent_time'           => $now_sent_time,
                );

                $i++;  
                // after 10 send update report in database
                if($i%$update_sms_sending_report_after_time==0)
                {
                    $send_report_json= json_encode($send_report);
                    $this->basic->update_data("sms_sending_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,'successfully_sent'=>$successfully_sent));
                }
                
                // updating a contact, marked as processed
                $this->basic->update_data("sms_sending_campaign_send",array("id"=>$send_table_id),array('processed'=>'1',"sent_time"=>$now_sent_time,"delivery_id"=>$message_sent_id));
            }

            // one campaign completed, now update database finally
            $send_report_json = json_encode($send_report);

            if((count($campaign_contacts) < $number_of_sms_to_be_sent_in_try) || $number_of_sms_to_be_sent_in_try == "")
            {
                $complete_update = array("report"=>$send_report_json,"posting_status"=>'2','successfully_sent'=>$successfully_sent,'completed_at'=>date("Y-m-d H:i:s"),"is_try_again"=>"0");                
                $this->basic->update_data("sms_sending_campaign",array("id"=>$campaign_id),$complete_update);
            }
            else // suppose update_sms_sending_report_after_time=20 but there are 19 message to sent, need to update report in that case
            { 
                $this->basic->update_data("sms_sending_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,'successfully_sent'=>$successfully_sent,"is_try_again"=>"1"));
            }
        }          
    
    }


    public function ssem_email_sending_command($api_key="")
    {
        $this->api_key_check($api_key);
        $number_of_email_to_be_sent_in_try = $this->config->item("number_of_email_to_be_sent_in_try");

        if($number_of_email_to_be_sent_in_try == "") 
            $number_of_email_to_be_sent_in_try = 10; // default 10
        else if($number_of_email_to_be_sent_in_try == 0) 
            $number_of_email_to_be_sent_in_try = ""; // 0 means unlimited

        $update_email_sending_report_after_time = $this->config->item("update_email_sending_report_after_time"); 

        if($update_email_sending_report_after_time == "" || $update_email_sending_report_after_time == 0) 
            $update_email_sending_report_after_time = 5;

        $number_of_campaign_to_be_processed = 1; // max number of campaign that can be processed by this cron job
        // $number_of_message_tob_be_sent = 50000;  // max number of message that can be sent in an hour

        $where['or_where'] = array('posting_status'=>"0","is_try_again"=>"1");

        /****** Get all campaign from database where status=0 means pending ******/
        $join = array('users'=>'email_sending_campaign.user_id=users.id,left');
        $campaign_info = $this->basic->get_data("email_sending_campaign",$where,$select=array("email_sending_campaign.*","users.deleted as user_deleted","users.status as user_status"),$join,$limit=50, $start=0, $order_by='schedule_time ASC');


        $campaign_id_array = array();  // all selected campaign id array
        $campaign_info_fildered = array(); // valid for process, campign info array

        $valid_campaign_count = 1;
        foreach($campaign_info as $info1)
        {
            if($info1['user_deleted'] == '1' || $info1['user_status']=="0")
            {
                $this->db->where("id",$info1['id']);
                $this->db->update("email_sending_campaign",array("posting_status"=>"1","is_try_again"=>"0"));
                continue;
            } 

            $campaign_id   = $info1['id'];
            $user_id       = $info1["user_id"];           
            $time_zone     = $info1['time_zone'];
            $schedule_time = $info1['schedule_time']; 
            $total_thread  = $info1["total_thread"];

            if($time_zone) date_default_timezone_set($time_zone);            
            $now_time = date("Y-m-d H:i:s");

            if((strtotime($now_time) < strtotime($schedule_time)) && $time_zone!="") continue; 
            if($valid_campaign_count > $number_of_campaign_to_be_processed) break;
           
            // valid campaign info and campig ids
            $campaign_info_fildered[] = $info1;
            $campaign_id_array[] = $info1['id']; 
            $valid_campaign_count++;      
        }


        if(count($campaign_id_array) == 0) exit();        

        $this->db->where_in("id",$campaign_id_array);
        $this->db->update("email_sending_campaign",array("posting_status"=>"1","is_try_again"=>"0"));

        foreach($campaign_info_fildered as $info2)
        {
            $i = 0;

            $campaign_id       = $info2['id'];
            $user_id           = $info2["user_id"];
            $configure_email_table = $info2['configure_email_table'];
            $email_api     = $info2['api_id'];

            $subject = $info2['email_subject'];

            $from_email = "";

            if ($configure_email_table == "email_config") 
            {
                $from_email = "smtp_".$info2["api_id"];

            } elseif ($configure_email_table == "email_mandrill_config") 
            {
                $from_email = "mandrill_".$info2["api_id"];

            } elseif ($configure_email_table == "email_sendgrid_config") 
            {
                $from_email = "sendgrid_".$info2["api_id"];

            } elseif ($configure_email_table == "email_mailgun_config") 
            {
                $from_email = "mailgun_".$info2["api_id"];
            }

            $output_dir = FCPATH."upload/attachment";
            $filename = $info2['email_attachment'];

            if($filename == "0") 
                $filename = "";
            
            if($filename != "")
                $attachement = $output_dir.'/'.$filename;
            else 
                $attachement = "";


            $campaign_message  = $info2['email_message'];  
            $successfully_sent = $info2["successfully_sent"];

            $report = json_decode($info2["report"],true); // get json contact list from database and decode it
            $send_report = $report;

            // $campaign_contacts_join = array("contacts"=>"email_sending_campaign_send.contact_id=contacts.id,left");
            // $campaign_contacts_select = array('email_sending_campaign_send.*','contacts.id AS contactid','contacts.unsubscribed');
            $where1['where']           = array("campaign_id"=>$campaign_id,"processed"=>"0");
            $campaign_contacts = $this->basic->get_data("email_sending_campaign_send",$where1,'','',$number_of_email_to_be_sent_in_try);

            foreach ($campaign_contacts as $contacts_details) 
            {
                $send_table_id      = $contacts_details['id'];
                $contactid          = $contacts_details['contact_id'];
                $contact_first_name = isset($contacts_details['contact_first_name']) ? $contacts_details['contact_first_name']:"";
                $contact_last_name  = isset($contacts_details['contact_last_name']) ? $contacts_details['contact_last_name']:"";
                $contact_email      = isset($contacts_details['contact_email']) ? $contacts_details['contact_email']:"";
                $contact_mobile     = isset($contacts_details['contact_phone']) ? $contacts_details['contact_phone']:"";
                $unscubscribe_btn   = base_url("home/unsubscribe/").$contactid.'/'.urlencode($contact_email);

                $campaign_message_send   = $campaign_message;
                $campaign_message_send   = str_replace(array("#FIRST_NAME#","#firstname#"),$contact_first_name,$campaign_message_send);
                $campaign_message_send   = str_replace(array("#LAST_NAME#","#lastname#"),$contact_last_name,$campaign_message_send);
                $campaign_message_send   = str_replace(array("#MOBILE#","#mobile#"),$contact_mobile,$campaign_message_send);
                $campaign_message_send   = str_replace(array("#EMAIL_ADDRESS#","#email#"),$contact_email,$campaign_message_send);
                $campaign_message_send   = str_replace("#UNSUBSCRIBE_LINK#",$unscubscribe_btn,$campaign_message_send);

                $message_sent_id = "";

                try
                {
                    $response = $this->_email_send_function($from_email, $campaign_message_send, $contact_email, $subject, $attachement, $filename,$user_id);

                    if(isset($response) && !empty($response) && $response == "Submited")
                    {   
                        $message_sent_id = $response; 
                        $successfully_sent++;
                    }
                    else 
                    {   
                        $message_sent_id = $response;
                    }           
                }
                catch(Exception $e) 
                {
                   $message_sent_id = $e->get_message();
                }

                // generating new report with send message info
                $now_sent_time = date("Y-m-d H:i:s");
                $send_report[$contact_email] = array( 
                    'email_table_name'    => $contacts_details['email_table_name'],
                    'email_api_id'        => $contacts_details['email_api_id'],
                    'contact_id'          => $contacts_details['contact_id'],
                    'contact_first_name'  => isset($contacts_details['contact_first_name']) ? $contacts_details['contact_first_name']:"",
                    'contact_last_name'   => isset($contacts_details['contact_last_name']) ? $contacts_details['contact_last_name']:"",
                    'contact_email'       => isset($contacts_details['contact_email']) ? $contacts_details['contact_email']:"",
                    'contact_phone_number'=> isset($contacts_details['contact_phone']) ? $contacts_details['contact_phone']:"",
                    'delivery_id'         => $message_sent_id,
                    'sent_time'           => $now_sent_time,
                );

                $i++;  
                // after 10 send update report in database
                if($i%$update_email_sending_report_after_time==0)
                {
                    $send_report_json= json_encode($send_report);
                    $this->basic->update_data("email_sending_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,'successfully_sent'=>$successfully_sent));
                }
                
                // updating a contact, marked as processed
                $this->basic->update_data("email_sending_campaign_send",array("id"=>$send_table_id),array('processed'=>'1',"sent_time"=>$now_sent_time,"delivery_id"=>$message_sent_id));
            }


            // one campaign completed, now update database finally
            $send_report_json = json_encode($send_report);

            if((count($campaign_contacts) < $number_of_email_to_be_sent_in_try) || $number_of_email_to_be_sent_in_try == "")
            {
                $complete_update = array("report"=>$send_report_json,"posting_status"=>'2','successfully_sent'=>$successfully_sent,'completed_at'=>date("Y-m-d H:i:s"),"is_try_again"=>"0");                
                $this->basic->update_data("email_sending_campaign",array("id"=>$campaign_id),$complete_update);
            }
            else // suppose update_email_sending_report_after_time=20 but there are 19 message to sent, need to update report in that case
            { 
                $this->basic->update_data("email_sending_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,'successfully_sent'=>$successfully_sent,"is_try_again"=>"1"));
            }
        }     
    }

    /*
    status => 0 = failed, 1 = success
    response_code => 1100  = valid-user+recieved but failed, 1101 = valid-user+recieved and updates, 1110 = valid-user+recieved and inserted , 1000 = valid-user+required data missing, 0000 = invalid user+required field missing
    [bit 1 = user status ,bit 2 = recieve status , bit 3 = update status, bit 4 = insert status]
    details => detailes of response
    */
    public function sync_contact()
    { 
        $return=array("status"=>"unknown","response_code"=>"unknown","details"=>"unknown");
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            echo json_encode($return);
            exit(); 
        }

        $api_key=$_POST["api_key"];
        $first_name=$_POST["first_name"];
        $last_name=$_POST["last_name"];
        $mobile=$_POST["mobile"];
        $email=$_POST["email"];
        $date_birth=$_POST["date_birth"];
        $contact_group_id=$_POST["contact_group_id"];

        $user_id="";        
        if (strpos($api_key, '-') !== false) 
        {
            $explde_api_key=explode('-',$api_key);
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];        
        }
        else $user_id=substr($api_key, 0, 1);

        if($api_key=="" || $user_id=="" || $first_name=="" || $email=="" || $contact_group_id=="")
        {
            $return["status"]="0";            
            $return["response_code"]="1000";
            $return["details"]="API Key, First Name, Email and Contact Group ID are required.";
            echo json_encode($return);
            exit();
        }

        if(!$this->basic->is_exist("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            $return["status"]="0";            
            $return["response_code"]="0000";
            $return["details"]="API Key does not match with any user.";
            echo json_encode($return);
            exit();
        }

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0")))
        {
            $return["status"]="0";            
            $return["response_code"]="0000";
            $return["details"]="API Key does not match with any user.";
            echo json_encode($return);
            exit();
        }  

        if(!$this->_api_membership_validity($user_id))
        {
            $return["status"]="0";            
            $return["response_code"]="0000";
            $return["details"]="Membership expired.";
            echo json_encode($return);
            exit();
        }
        
        $return["status"]="0";
        $return["response_code"]="1100";
        $return["details"]="Contact has been recieved but failed to sync.";

        $insert_update_data=array("first_name"=>$first_name,"email"=>$email,"contact_type_id"=>$contact_group_id, "user_id"=>$user_id);
        if($date_birth!="") $insert_update_data["date_birth"]=date("Y-m-d",strtotime($date_birth));
        if($mobile!="")     $insert_update_data["phone_number"]=$mobile;
        if($last_name!="")  $insert_update_data["last_name"]=$last_name;

        if($this->basic->is_exist("contacts",array("email"=>$email,"user_id"=>$user_id))) // if exist then update
        {
            if($this->basic->update_data("contacts",array("email"=>$email,"user_id"=>$user_id),$insert_update_data))
            {
                $return["status"]="1";
                $return["response_code"]="1101";
                $return["details"]="Contact has been recieved and updated successfully."; 
            }
        }
        else  // if does not exist insert
        {
            if($this->basic->insert_data("contacts",$insert_update_data))
            {
                $return["status"]="1";
                $return["response_code"]="1110";
                $return["details"]="Contact has been recieved and inserted successfully."; 
            }
        }  

        echo json_encode($return);    
    }


    public function send_sms_api()
    {        
        $return=array("status"=>"unknown","details"=>"unknown");
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            echo json_encode($return);
            exit(); 
        }   
       
        $api_key=$_POST["api_key"];           
        $mobile=$_POST["mobile"]; // comma seperated string
        $config_id=$_POST["reference_id"];
        $message=$_POST["message"];
        
        $user_id="";        
        if (strpos($api_key, '-') !== false) 
        {
            $explde_api_key=explode('-',$api_key);
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];        
        }
        else $user_id=substr($api_key, 0, 1);

        if($api_key=="" || $user_id=="" || $mobile=="" || $config_id=="" || $message=="")
        {      
            $return["status"]="0";
            $return["details"]="API Key, Mobile No., Message and Reference ID are required.";
            echo json_encode($return);
            exit();
        }

        if(!$this->basic->is_exist("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            $return["status"]="0";
            $return["details"]="API Key does not match with any user.";
            echo json_encode($return);
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0")))
        {
            $return["status"]="0";          
            $return["details"]="API Key does not match with any user.";
            echo json_encode($return);
            exit();
        }  

        if(!$this->_api_membership_validity($user_id))
        {
            $return["status"]="0";            
            $return["details"]="Membership expired.";
            echo json_encode($return);
            exit();
        }

        if(!$this->basic->is_exist("sms_api_config",array("user_id"=>$user_id,"id"=>$config_id)))
        {
            $return["status"]="0";
            $return["details"]="This Reference ID is not associated with you.";
            echo json_encode($return);
            exit();
        } 

        $to_numbers=explode(',',$mobile);         
        
        $this->session->set_userdata("user_id",$user_id);
        $this->user_id=$user_id;

        $this->load->library('Sms_manager');
        $this->sms_manager->set_credentioal($config_id,$user_id);
        foreach ($to_numbers as $phone_number) 
        {            
            $this->sms_manager->send_sms($message, $phone_number);
        }
        $return["status"]="1";
        $return["details"]="Submitted successfully.";
        echo json_encode($return);
    }


    // gateways smtp,mandrill,sendgrid,mailgun
    public function send_email_api()
    { 
        $return=array("status"=>"unknown","details"=>"unknown");
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            echo json_encode($return);
            exit(); 
        }              
      
        $api_key=$_POST["api_key"];           
        $email=$_POST["email"]; // comma seperated string
        $config_id=$_POST["reference_id"];
        $subject=$_POST["subject"]; 
        $message=$_POST["message"];
        $gateway_name=$_POST["gateway_name"];

        $user_id="";        
        if (strpos($api_key, '-') !== false) 
        {
            $explde_api_key=explode('-',$api_key);
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];        
        }
        else $user_id=substr($api_key, 0, 1);

        if($api_key=="" || $user_id=="" || $email=="" || $config_id=="" || $message=="" || $subject=="" || $gateway_name=="")
        {      
            $return["status"]="0";
            $return["details"]="API Key, Email, Subject, Message and Gateway Name Reference ID are required.";
            echo json_encode($return);
            exit();
        }

        if(!$this->basic->is_exist("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            $return["status"]="0";
            $return["details"]="API Key does not match with any user.";
            echo json_encode($return);
            exit();
        }  

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0")))
        {
            $return["status"]="0";          
            $return["details"]="API Key does not match with any user.";
            echo json_encode($return);
            exit();
        }  

        if(!$this->_api_membership_validity($user_id))
        {
            $return["status"]="0";            
            $return["details"]="Membership expired.";
            echo json_encode($return);
            exit();
        }

        $to_emails=explode(',',$email);         
        
        $this->session->set_userdata("user_id",$user_id);
        $this->user_id=$user_id;

        $from_email="";
        if ($gateway_name=="smtp")
        $from_email="smtp_".$config_id;
        else if ($gateway_name=="mandrill") 
        $from_email="mandrill_".$config_id;
        elseif ($gateway_name=="sendgrid")
        $from_email="sendgrid_".$config_id;
        elseif ($gateway_name=="mailgun") 
        $from_email="mailgun_".$config_id;

        if ($gateway_name=="mandrill") 
        $config_table="email_mandrill_config";
        else if ($gateway_name=="sendgrid")
        $config_table="email_sendgrid_config";
        else if ($gateway_name=="mailgun") 
        $config_table="email_mailgun_config";
        else $config_table="email_config";

        if(!$this->basic->is_exist($config_table,array("user_id"=>$user_id,"id"=>$config_id)))
        {
            $return["status"]="0";
            $return["details"]="This Reference ID is not associated with you.";
            echo json_encode($return);
            exit();
        }  
        
        $this->_email_send_function($from_email, $message, $to_emails, $subject,$user_id);

        $return["status"]="1";
        $return["details"]="Submitted successfully.";
        echo json_encode($return);
    }


    public function scheduler_email($api_key="")
    {
        $number_of_day = 0;

        if ($api_key=="") exit();

        $user_id="";        
        if (strpos($api_key, '-') !== false) 
        {
            $explde_api_key=explode('-',$api_key);
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];        
        }
        else $user_id=substr($api_key, 0, 1);
        
        $this->session->set_userdata("user_id",$user_id);
        $this->user_id=$user_id;

        if(!$this->basic->is_exist("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any admin user.";
            exit();
        }  

        $this->load->library('Sms_manager');
        $output_dir = FCPATH."upload/attachment";

        //=================================================================================
        // scheduled email start
        //=================================================================================
        $scheduled_email_info=$this->basic->get_data($table="schedule_email", $where=array("where"=>array("is_sent"=>"0")),$select='',$join='',$limit=1,$start=0,$order_by='schedule_time asc');
        foreach ($scheduled_email_info as $row) 
        {
            $scheduled_time=$row["schedule_time"];
            $user_id=$row["user_id"];
            $this->user_id=$user_id;
            $time_zone=$row["time_zone"];
            if ($time_zone== '') {
                $time_zone=$this->config->item("time_zone");
                if ($time_zone== '') {
                    $time_zone="Europe/Dublin";
                }
            }
            date_default_timezone_set($time_zone);
            $cur_datetime=date("Y-m-d H:i:s");            

            if (strtotime($scheduled_time) > strtotime($cur_datetime)) 
            {
                continue;
            }

            $scheduler_id=$row["id"];
            $message=html_entity_decode($row["message"]);
            $subject=$row["subject"];
            $filename=$row["attachment"];
            if($filename=="0") $filename="";
            if($filename!="")
            $attachement=$output_dir.'/'.$filename;
            else $attachement="";

            // make is pending
            $this->basic->update_data("schedule_email", array("id"=>$scheduler_id), array("is_sent"=>"2"));


            $configure_table_name=$row["configure_table_name"];
            $from_email="";
            if ($configure_table_name=="email_config") 
            {
                $from_email="smtp_".$row["api_id"];
            } elseif ($configure_table_name=="email_mandrill_config") 
            {
                $from_email="mandrill_".$row["api_id"];
            } elseif ($configure_table_name=="email_sendgrid_config") 
            {
                $from_email="sendgrid_".$row["api_id"];
            } elseif ($configure_table_name=="email_mailgun_config") 
            {
                $from_email="mailgun_".$row["api_id"];
            }
            
            $contact_id_str=$row["contact_ids"];
            $contact_ids=explode(",", $contact_id_str);

            // if message contains no veriable then send bulk
            if (strpos($message, '#firstname#')== false && strpos($message, '#lastname#')== false && strpos($message, '#mobile#')== false && strpos($message, '#email#')== false) 
            {
                $sent_email=array();
                foreach ($contact_ids as $contact_id) 
                {
                    $contact_info=array();
                    $contact_info=$this->basic->get_data($table="contacts", $where=array("where"=>array("user_id"=>$user_id, "id"=>$contact_id)));
                    if(empty($contact_info)) continue;
                    $sent_email[]=$contact_info[0]['email'];
                }
                if (!empty($sent_email)) 
                {
                    /***** Send email of contact list (bulk) ******/
                    $sent_email=array_unique($sent_email);
                    $this->_email_send_function($from_email, $message, $sent_email, $subject, $attachement, $filename,$user_id)."<br/>";
                }    
            } 
            else 
            {
                 $sent_email=array();      
                 foreach ($contact_ids as $contact_id) 
                 {
                    $contact_info=array();
                    $contact_info=$this->basic->get_data($table="contacts", $where=array("where"=>array("user_id"=>$user_id, "id"=>$contact_id)));
                    if(empty($contact_info)) continue;

                    $first_name=$contact_info[0]['first_name'];
                    $last_name=$contact_info[0]['last_name'];
                    $email=$contact_info[0]['email'];
                    $mobile=$contact_info[0]['phone_number'];

                    $message_replaced=$message;
                    $message_replaced=str_replace("#firstname#", $first_name, $message_replaced);
                    $message_replaced=str_replace("#lastname#", $last_name, $message_replaced);
                    $message_replaced=str_replace("#mobile#", $mobile, $message_replaced);
                    $message_replaced=str_replace("#email#", $email, $message_replaced);

                    if(in_array($email,$sent_email)) continue;
                    $sent_email[]=$email;

                    $email_array=array($email); // making single email an array     
                    $this->_email_send_function($from_email, $message_replaced, $email_array, $subject, $attachement, $filename,$user_id)."<br/>"; /***** Send email of contact list (bulk) ******/
                }
            }

           $this->basic->update_data("schedule_email", array("id"=>$scheduler_id), array("is_sent"=>"1", "sent_time"=>date("Y-m-d H:i:s")));
        }
        //=================================================================================
        // scheduled email end
        //=================================================================================            
       
    }

    public function scheduler_sms($api_key="")
    {
        $number_of_day = 0;

        if ($api_key=="") exit();

        $user_id="";        
        if (strpos($api_key, '-') !== false) 
        {
            $explde_api_key=explode('-',$api_key);
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];        
        }
        else $user_id=substr($api_key, 0, 1);
        
        $this->session->set_userdata("user_id",$user_id);
        $this->user_id=$user_id;

        if(!$this->basic->is_exist("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any admin user.";
            exit();
        }  

        $this->load->library('Sms_manager');
        $output_dir = FCPATH."upload/attachment";
       
        //=================================================================================
        // scheduled SMS start
        //=================================================================================
        $scheduled_sms_info=$this->basic->get_data($table="schedule_sms", $where=array("where"=>array("is_sent"=>"0")),$select='',$join='',$limit=1,$start=0,$order_by='schedule_time asc');
        
        foreach ($scheduled_sms_info as $row) 
        {
            $scheduled_time=$row["schedule_time"];
            $user_id=$row["user_id"];
            $this->user_id=$user_id;
            $time_zone=$row["time_zone"];
            if ($time_zone== '') {
                $time_zone=$this->config->item("time_zone");
                if ($time_zone== '') {
                    $time_zone="Europe/Dublin";
                }
            }
            date_default_timezone_set($time_zone);
            $cur_datetime=date("Y-m-d H:i:s");


            if (strtotime($scheduled_time) > strtotime($cur_datetime)) {
                continue;
            }

            $scheduler_id=$row["id"];
            $message=urldecode($row["message"]);
            $config_id=$row["api_id"];
            $this->sms_manager->set_credentioal($config_id,$user_id);
            $contact_id_str=$row["contact_ids"];
            $contact_ids=explode(",", $contact_id_str);

            // make is pending
            $this->basic->update_data("schedule_sms", array("id"=>$scheduler_id), array("is_sent"=>"2"));
                                        
            $sent_number=array();            

            foreach ($contact_ids as $contact_id) 
            {
                $contact_info=array();
                $contact_info=$this->basic->get_data($table="contacts", $where=array("where"=>array("user_id"=>$user_id, "id"=>$contact_id)));
                
                $first_name=$contact_info[0]['first_name'];
                $last_name=$contact_info[0]['last_name'];
                $phone_number=$contact_info[0]['phone_number'];
                $email=$contact_info[0]['email'];

                $message_replaced=$message;
                $message_replaced=str_replace("#firstname#", $first_name, $message_replaced);
                $message_replaced=str_replace("#lastname#", $last_name, $message_replaced);
                $message_replaced=str_replace("#mobile#", $phone_number, $message_replaced);
                $message_replaced=str_replace("#email#", $email, $message_replaced);

                if(in_array($phone_number,$sent_number)) continue;              
                $sent_number[]=$phone_number;

                $this->sms_manager->send_sms($message_replaced, $phone_number);                
            }

            $this->basic->update_data("schedule_sms", array("id"=>$scheduler_id), array("is_sent"=>"1", "sent_time"=>date("Y-m-d H:i:s")));
        }
        //=================================================================================
        // scheduled SMS end
        //=================================================================================
       
    }

    public function birthday_scheduler($api_key="")
    {
        $number_of_day = 0;

        if ($api_key=="") exit();

        $user_id="";        
        if (strpos($api_key, '-') !== false) 
        {
            $explde_api_key=explode('-',$api_key);
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];        
        }
        else $user_id=substr($api_key, 0, 1);

        // echo $user_id; exit;
        
        $this->session->set_userdata("user_id",$user_id);
        $this->user_id=$user_id;

        if(!$this->basic->is_exist("ssem_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any admin user.";
            exit();
        }  

        $this->load->library('Sms_manager');
        $output_dir = FCPATH."upload/attachment";


        //=================================================================================
        // Birthday Wish email start
        //=================================================================================
        $birthday_email_info = $this->basic->get_data($table="birthday_reminder_email", $where=array("where"=>array("status"=>"1")));
    
        foreach ($birthday_email_info as $row) 
        {
            $user_id=$row["user_id"];
            $this->user_id=$user_id;
            $scheduler_id=$row["id"];
            $message=html_entity_decode($row["message"]);
            $subject=$row["subject"];
            $filename="";
            $attachement="";

            $configure_table_name=$row["configure_table_name"];
            $from_email="";
            if ($configure_table_name=="email_config") {
                $from_email="smtp_".$row["api_id"];
            } elseif ($configure_table_name=="email_mandrill_config") {
                $from_email="mandrill_".$row["api_id"];
            } elseif ($configure_table_name=="email_sendgrid_config") {
                $from_email="sendgrid_".$row["api_id"];
            } elseif ($configure_table_name=="email_mailgun_config") {
                $from_email="mailgun_".$row["api_id"];
            }
                        
            $time_zone=$row["time_zone"];
            if ($time_zone== '') {
                $time_zone=$this->config->item("time_zone");
                if ($time_zone== '') {
                    $time_zone="Europe/Dublin";
                }
            }
            date_default_timezone_set($time_zone);
            $cur_date=date("Y-m-d");
            $cur_year=date("Y");

            //mostofa 6/15/16
            $new_date_variable = date("m-d", strtotime("$cur_date + $number_of_day days"));


            $contact_info_2d = array();
            $where_date_birth = array("where"=>array("user_id"=>$user_id,"email_last_wished_year !="=>$cur_year,"DATE_FORMAT(date_birth,'%m-%d')"=>$new_date_variable));
            $contact_info_2d = $this->basic->get_data($table="contacts", $where_date_birth);
             
            // if message contains no veriable then send bulk
            if (strpos($message, '#FIRST_NAME#')== false && strpos($message, '#LAST_NAME#')== false && strpos($message, '#MOBILE#')== false && strpos($message, '#EMAIL_ADDRESS#')== false) {
                $sent_email=array();
                foreach ($contact_info_2d as $contact_info) 
                {
                    $sent_email[] = $contact_info['email'];
                }

                if (!empty($sent_email)) 
                {
                    $sent_email = array_unique($sent_email);
                    /***** Send email of contact list (bulk) ******/
                    $this->_email_send_function($from_email, $message, $sent_email, $subject, $attachement, $filename,$user_id)."<br/>";
                }    
            } 
            else 
            {
                $sent_email=array();                
                foreach ($contact_info_2d as $contact_info) 
                {
                    if($contact_info['email'] =="") continue;

                    $first_name=$contact_info['first_name'];
                    $last_name=$contact_info['last_name'];
                    $email=$contact_info['email'];                    
                    $mobile=$contact_info['phone_number'];

                    $message_replaced=$message;
                    $message_replaced=str_replace("#FIRST_NAME#", $first_name, $message_replaced);
                    $message_replaced=str_replace("#LAST_NAME#", $last_name, $message_replaced);
                    $message_replaced=str_replace("#MOBILE#", $mobile, $message_replaced);
                    $message_replaced=str_replace("#EMAIL_ADDRESS#", $email, $message_replaced);

                    if(in_array($email,$sent_email)) continue;
                    $sent_email[]=$email;

                    $email_array=array($email); // making single email an array     
                    $this->_email_send_function($from_email, $message_replaced, $email_array, $subject, $attachement, $filename,$user_id)."<br/>"; /***** Send email of contact list (bulk) ******/
                }
            }

            if(!empty($sent_email))
            {
                $this->db->where("user_id",$user_id);
                $this->db->where_in("email",$sent_email);
                $this->db->update("contacts",array("email_last_wished_year"=>$cur_year));
            }
            
        }
        //=================================================================================
        // Birthday Wish email end
        //=================================================================================


        //=================================================================================
        // Birthday Wish SMS start
        //=================================================================================
        $birthday_sms_info=$this->basic->get_data($table="birthday_reminder", $where=array("where"=>array("status"=>"1")));
        // echo "<pre>"; print_r($birthday_sms_info); exit();
    
        foreach ($birthday_sms_info as $row) 
        {
            $scheduler_id=$row["id"];
            $user_id=$row["user_id"];
            $this->user_id=$user_id;
            $message=html_entity_decode($row["message"]);
            $config_id=$row["api_id"];
            $this->sms_manager->set_credentioal($config_id,$user_id);
                        
            $time_zone=$row["time_zone"];
            if ($time_zone== '') {
                $time_zone=$this->config->item("time_zone");
                if ($time_zone== '') {
                    $time_zone="Europe/Dublin";
                }
            }
            date_default_timezone_set($time_zone);
            $cur_date=date("Y-m-d");
            $cur_year=date("Y");

            //mostofa 6/15/16
            $new_date_variable = date("m-d", strtotime("$cur_date + $number_of_day days"));

            $contact_info_2d=array();
            $where_date_birth=array("where"=>array("user_id"=>$user_id,"sms_last_wished_year !="=>$cur_year,"DATE_FORMAT(date_birth,'%m-%d')"=>$new_date_variable));
            $contact_info_2d=$this->basic->get_data($table="contacts", $where_date_birth);
            
            $sent_number=array();       
            foreach ($contact_info_2d as $contact_info) 
            {
                if($contact_info['phone_number'] =="") continue;

                $first_name=$contact_info['first_name'];
                $last_name=$contact_info['last_name'];
                $email=$contact_info['email'];
                $mobile=$contact_info['phone_number'];

                $message_replaced=$message;
                $message_replaced=str_replace("#FIRST_NAME#", $first_name, $message_replaced);
                $message_replaced=str_replace("#LAST_NAME#", $last_name, $message_replaced);
                $message_replaced=str_replace("#MOBILE#", $mobile, $message_replaced);
                $message_replaced=str_replace("#EMAIL_ADDRESS#", $email, $message_replaced);

                if(in_array($mobile,$sent_number)) continue;                
                $sent_number[]=$mobile;

                $this->sms_manager->send_sms($message_replaced, $mobile);
            }

            if(!empty($sent_number))
            {
                $this->db->where("user_id",$user_id);
                $this->db->where_in("phone_number",$sent_number);
                $this->db->update("contacts",array("sms_last_wished_year"=>$cur_year));
            }

            
        }
        //=================================================================================
        // Birthday Wish SMS end
        //=================================================================================
    }


    // public function call_sync_contact()
    // {  
    //     $url = 'http://konok-pc/xeroneit/sms_reseller/ssem_api/sync_contact';
    //     $data=array
    //     (
    //         "api_key"           => "1n2U51455446947iBlwn",
    //         "first_name"        => "alamin",
    //         "last_name"         => "Jwel",
    //         "mobile"            => "01723309003",
    //         "email"             => "jwel.cse@gmail.com",
    //         "contact_group_id"  => "1",
    //         "date_birth"        => "1989-12-10"
    //     );
         
    //     $ch=curl_init($url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
    //     curl_setopt($ch, CURLOPT_HEADER, 0);  
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
    //     curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
    //     curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    //     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
    //     $response = curl_exec( $ch );
    //     curl_close($ch);        
    //     $response=json_decode($response,TRUE);
    //     print_r($response);
    // }


    // public function call_send_email_api()
    // {  
    //     $url = 'http://konok-pc/xeroneit/sms_reseller/ssem_api/send_email_api';
        
    //     $data=array
    //     (
    //         "api_key"       => "1n2U51455446947iBlwn",
    //         "email"         => "jwel.cse@gmail.com",
    //         "reference_id"  => "1","gateway_name"=>"smtp",
    //         "subject"       => "test subject",
    //         "message"       => "this is a test email"
    //     ); 
         
    //     $ch=curl_init($url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
    //     curl_setopt($ch, CURLOPT_HEADER, 0);  
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
    //     curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
    //     curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    //     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
    //     $response = curl_exec( $ch );
    //     curl_close($ch);        
    //     $response=json_decode($response,TRUE);
    //     print_r($response);
    // }

    // public function call_send_sms_api()
    // {  
    //     $url = 'http://konok-pc/xeroneit/sms_reseller/ssem_api/send_sms_api';
        
    //     $data=array
    //     (
    //         "api_key"       => "1UZXH1455455545NZDQ9",
    //         "mobile"        => "8801722977459,8801723309003",
    //         "reference_id"  => "5",
    //         "message"       => "this is a test sms"
    //     );

    //     $ch=curl_init($url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
    //     curl_setopt($ch, CURLOPT_HEADER, 0);  
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
    //     curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
    //     curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    //     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
    //     $response = curl_exec( $ch );
    //     curl_close($ch);        
    //     $response=json_decode($response,TRUE);
    //     print_r($response);
    // }
    


    
}
