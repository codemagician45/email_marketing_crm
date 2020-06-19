<?php 
require_once("Home.php");

class My_sms extends Home
{

    public $user_id;
    public function __construct()
    {
        parent::__construct();
        
        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login', 'location');
        }

        $this->important_feature();

        $this->load->library('Sms_manager');

        $this->user_id=$this->session->userdata("user_id");

        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            $where['where'] = array('id'=>$this->user_id);
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $payment_config=$this->basic->get_data("payment_config");
            $monthly_fee=$payment_config[0]["monthly_fee"];
            if ($expire_date < $current_date && $monthly_fee>0)
            redirect('payment/member_payment_history','Location');
        }
    }

    public function index()
    {
        $this->_viewcontroller();
    }

    public function csv_upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $type= strip_tags($this->input->post('hidden_import_type', true));

        $this->load->library('upload');
        $filename = $this->user_id."_".$type."_".time().substr(uniqid(mt_rand(), true), 0, 6);
        $config['file_name'] = $filename;
        $config['upload_path'] = './upload/csv/';
        $config['allowed_types'] = 'text/plain|text/anytext|csv|text/x-comma-separated-values|text/comma-separated-values|application/octet-stream|application/vnd.ms-excel|application/x-csv|text/x-csv|text/csv|application/csv|application/excel|application/vnd.msexcel';
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload('csv_file') != true) {
            $upload_image = array('upload_data' => $this->upload->data());
            $temp= $upload_image['upload_data']['file_name'];
            $response['status']=$this->upload->display_errors();
        } else {
            $upload_image = array('upload_data' => $this->upload->data());
            $csv= base_url().'upload/csv/'.$upload_image['upload_data']['file_name'];
            $file=file_get_contents($csv);
            
            $file=str_replace(array("\'", "\"","\t","\r"," "), '', $file);
            $file=str_replace(array("\n"), ',', $file);
            $file=trim($file,",");

            $response['status']='ok';
            $response['message']="<div class='alert alert-success text-center'>".$this->lang->line("your given information has been updated successfully.")."</div>";
            $response['file']=$file;
        }

        echo json_encode($response);
    }

    public function load_template()
    {
       $id= $this->input->post('template_id'); 
       if($id=='') 
       {
         $no_response["message"] = "";
         echo json_encode($no_response);
         exit();
       }
 
       $this->db->select('message');
       $this->db->from('message_template');
       $this->db->where(array("id"=>$id,"user_id"=>$this->user_id));
       $data=$this->db->get()->result_array();
       $response=array();
       $message = "";
       foreach ($data as $key => $value) 
       {
          $message=$value["message"];
       }
       $response["message"] = $message;
       echo json_encode($response);
    }


    public function sms_api()
    {
        //This process is important to reset the last serial no...
        $page = $this->input->get_post('page');
        if ($page == '') {
            $this->session->set_userdata('SMSAPILastSerial', "");
        } else {
            $per_page = $this->input->get_post('per_page');
            $start = ($page-1) * $per_page;
            $this->session->set_userdata('SMSAPILastSerial', $start);
        }

        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();

        $crud->where('sms_api_config.deleted', '0');
        $crud->where('sms_api_config.user_id', $this->user_id);

        $crud->set_theme('flexigrid');
        $crud->set_table('sms_api_config');
        $crud->order_by('gateway_name');
        $crud->set_subject($this->lang->line('SMS API'));
        $crud->columns('SL','id','gateway_name', 'username_auth_id', 'password_auth_token', 'api_id', 'phone_number','credit', 'status');
        $crud->required_fields('gateway_name');
        $crud->fields('gateway_name', 'username_auth_id', 'password_auth_token', 'api_id', 'phone_number', 'status');
        $crud->callback_field('status', array($this, 'status_field_crud'));
        $crud->callback_column('status', array($this, 'status_display_crud'));
        $crud->callback_column('credit', array($this, 'credit_display_crud'));
        $crud->callback_after_insert(array($this, 'insert_user_id_sms_api'));    /**insert the user_id***/

        // for SL column				
        $crud->callback_column('SL', array($this, 'generateSerialSMSAPI'));

        $crud->display_as('gateway_name', $this->lang->line('Gateway'));
        $crud->display_as('username_auth_id', $this->lang->line('Auth ID/ Auth Key/ API Key/ MSISDN/ Account Sid/ Account ID/ Username/ Admin'));
        $crud->display_as('password_auth_token', $this->lang->line('Auth Token/ API Secret/ Password'));
        $crud->display_as('api_id', $this->lang->line("API ID (if clickatell)"));
        $crud->display_as('phone_number', $this->lang->line("Sender/ Sender ID/ Mask/ From"));
        $crud->display_as('id', $this->lang->line('Reference ID'));
        $crud->display_as('credit', $this->lang->line('Remaining Credit'));
        $crud->display_as('status', $this->lang->line('Status'));

        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_export();
    
    
        $output = $crud->render();
        $data['page_title'] = 'SMS API';
        $data['output']=$output;
        $data['crud']=1;
        $this->_viewcontroller($data);
    }
    
    // sms sending functions
    public function sms_template()
    {
        //This process is important to reset the last serial no...
        $page = $this->input->get_post('page');
        if ($page == '') {
            $this->session->set_userdata('SMSTemplateLastSerial', "");
        } else {
            $per_page = $this->input->get_post('per_page');
            $start = ($page-1) * $per_page;
            $this->session->set_userdata('SMSTemplateLastSerial', $start);
        }

        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        
        $crud->where('message_template.deleted', '0');
        $crud->where('message_template.user_id', $this->user_id);

        $crud->unset_export();
        $crud->unset_print();

        $crud->set_theme('flexigrid');
        $crud->set_table('message_template');
        $crud->order_by('template_name');
        $crud->set_subject($this->lang->line('SMS Template'));
        $crud->required_fields('message', 'template_name');
        $crud->columns('SL', 'template_name', 'message');
        $crud->fields('template_name', 'message');
        
        $crud->callback_after_insert(array($this, 'insert_user_id_sms_template'));    /**insert the user_id***/

        // for SL column				
        $crud->callback_column('SL', array($this, 'generateSerialSMSTemplate'));
        $crud->callback_field('message', array($this, 'message_field_with_instruction'));

        $state = $crud->getState();
        if ($state == 'read') {
            $crud->columns('template_name', 'message');
        } else {
            $crud->columns('SL', 'template_name', 'message');
        }

        $crud->display_as('template_name', $this->lang->line('Template Name'));
        $crud->display_as('message', $this->lang->line('Message'));
        
        $output = $crud->render();
        $data['output']=$output;
        $data['page_title'] = 'SMS Template';
        $data['crud']=1;
        $this->_viewcontroller($data);
    }
    
    public function sms_campaign()
    {
        $data['body']       = "my_sms/schedule_sms/scheduled_sms";
        $data['page_title'] = $this->lang->line('SMS Campaign');
        $this->_viewcontroller($data);
    }
    
    public function sms_campaign_data()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'added_at';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str = $sort." ".$order;

        $campaign_name  = trim($this->input->post("campaign_name", true));
        $posting_status = trim($this->input->post("posting_status", true));
        $scheduled_from = trim($this->input->post('scheduled_from', true));
        $scheduled_to   = trim($this->input->post('scheduled_to', true));
        $is_searched    = $this->input->post('is_searched', true);

        if($scheduled_from) $scheduled_from = date('Y-m-d H:i:s', strtotime($scheduled_from));
        if($scheduled_to) $scheduled_to     = date('Y-m-d H:i:s', strtotime($scheduled_to));

        if($is_searched)
        {
            $this->session->set_userdata('sms_sending_campaign_name', $campaign_name);
            $this->session->set_userdata('sms_sending_posting_status', $posting_status);
            $this->session->set_userdata('sms_sending_scheduled_from', $scheduled_from);
            $this->session->set_userdata('sms_sending_scheduled_to', $scheduled_to);
        }

        $search_campaign_name   = $this->session->userdata('sms_sending_campaign_name');
        $search_posting_status  = $this->session->userdata('sms_sending_posting_status');
        $search_scheduled_from  = $this->session->userdata('sms_sending_scheduled_from');
        $search_scheduled_to    = $this->session->userdata('sms_sending_scheduled_to');

        $where_simple = array();

        if ($search_campaign_name) $where_simple['campaign_name like ']    = "%".$search_campaign_name."%";
        if ($search_posting_status !='') $where_simple['posting_status']        = $search_posting_status;

        if ($search_scheduled_from)
        {
            if ($search_scheduled_from != '1970-01-01')
                $where_simple["Date_Format(schedule_time,'%Y-%m-%d') >="]= $search_scheduled_from;
        }

        if ($search_scheduled_to)
        {
            if ($search_scheduled_to != '1970-01-01')
                $where_simple["Date_Format(schedule_time,'%Y-%m-%d') <="] = $search_scheduled_to;
        }

        
        $where_simple['sms_sending_campaign.user_id'] = $this->user_id;

        $where  = array('where' => $where_simple);
        $join   = array("sms_api_config" => "sms_sending_campaign.api_id=sms_api_config.id,left");
        $select = array("sms_sending_campaign.*","CONCAT(sms_api_config.gateway_name,' : ',sms_api_config.phone_number) AS send_as");
        $offset = ($page-1)*$rows;
        $result = array();
        $info   = $this->basic->get_data('sms_sending_campaign', $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='', $num_rows=0);
        for($i = 0; $i < count($info); $i++)
        {
            if($info[$i]['schedule_time'] != "0000-00-00 00:00:00")
                $scheduled_at = date("M j, y H:i",strtotime($info[$i]['schedule_time']));
            else 
                $scheduled_at = '<i class="fa fa-remove" title="'.$this->lang->line("not scheduled").'"></i>';

            $info[$i]['scheduled_at'] =  $scheduled_at;

            // added date
            if($info[$i]['added_at'] != "0000-00-00 00:00:00")
                $info[$i]['added_at'] = date("M j, y H:i",strtotime($info[$i]['added_at']));

            $posting_status = $info[$i]['posting_status'];

            // generating delete button
            if($posting_status=='1')
                $info[$i]["delete"] = "<a class='delete btn btn-outline-danger border_gray gray'><i class='fa fa-trash'></i></a>";
            else 
                $info[$i]['delete'] =  "<a title='".$this->lang->line("delete this campaign")."' id='".$info[$i]['id']."' class='delete btn btn-outline-danger'><i class='fa fa-trash'></i></a>";

            $is_try_again = $info[$i]["is_try_again"];

            $force_porcess_str="";

            // generating restat and force processing button
            if($this->config->item("number_of_sms_to_be_sent_in_try") == "" || $this->config->item("number_of_sms_to_be_sent_in_try") == "0")
            {
                $force_porcess_str="";
            }
            else
            {
                if($posting_status=='1' && $is_try_again=='1')
                    $force_porcess_str = "<button class='btn btn-outline-warning pause_campaign_info' table_id='".$info[$i]['id']."' title='".$this->lang->line("Pause Campaign")."'><i class='fa fa-pause'></i></button>&nbsp;";
                if($posting_status=='3')
                    $force_porcess_str = "<button class='btn btn-outline-success play_campaign_info' table_id='".$info[$i]['id']."' title='".$this->lang->line("Start Campaign")."'><i class='fa fa-play'></i></button>&nbsp;";
            }

            if($posting_status=='1')
                $force_porcess_str .= "<a title='".$this->lang->line("reprocess this campaign")."' id='".$info[$i]['id']."' class='force btn btn-outline-primary' title='".$this->lang->line("force reprocessing")."'><i class='fa fa-refresh'></i></a>";

            // for force processing
            $info[$i]['force'] = $force_porcess_str;

            // status
            if( $posting_status == '2') 
                $info[$i]['post_status_formatted'] = '<span class="label label-light"><i class="green fa fa-check-circle"></i> '.$this->lang->line("completed").'</span>';
            else if( $posting_status == '1') 
                $info[$i]['post_status_formatted'] = '<span class="label label-light"><i class="orange fa fa-spinner"></i> '.$this->lang->line("processing").'</span>';
            else if( $posting_status == '3') 
                $info[$i]['post_status_formatted'] = '<span class="label label-light"><i class="black fa fa-stop"></i> '.$this->lang->line("stopped").'</span>';
            else 
                $info[$i]['post_status_formatted'] = '<span class="label label-light"><i class="red fa fa-remove"></i> '.$this->lang->line("pending").'</span>';

            // sent column
            $info[$i]["sent_count"] =  $info[$i]["successfully_sent"]."/". $info[$i]["total_thread"] ;

            $info[$i]['report'] =  "<a title='".$this->lang->line("view campaign report")."' cam-id='".$info[$i]['id']."' class='sent_report btn btn-outline-info'><i class='fa fa-list'></i> </a>";

            if($posting_status != '0' || $info[$i]['time_zone'] == "") 
                $info[$i]['edit'] = "<a title='".$this->lang->line("only pending campaigns are editable")."' class='btn btn-outline-warning border_gray gray'><i class='fa fa-edit'></i></a>";
            else
            {
                $edit_url = site_url('my_sms/edit_sms_campaign/'.$info[$i]['id']);
                $info[$i]['edit'] =  "<a title=".$this->lang->line("edit campaign")." href='".$edit_url."' class='btn btn-outline-warning'><i class='fa fa-edit'></i></a>";
            }

            $info[$i]['actions'] = $info[$i]['report']." ".$info[$i]['edit']." ".$info[$i]['delete']." ".$force_porcess_str;
        }

        $total_rows_array = $this->basic->count_row($table="sms_sending_campaign", $where, $count="sms_sending_campaign.id", $join);
        $total_result     = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }

    public function add_schedule()
    {
        $data['body']="my_sms/schedule_sms/schedule_add";
        
        /**Get contact number and contact_type***/
        $user_id = $this->user_id;
        $table_type = 'contact_type';   
        $where_type['where'] = array('user_id'=>$user_id);
        $info_type = $this->basic->get_data($table_type,$where_type,$select='', $join='', $limit='', $start='', $order_by='type');  
        $result = array();

        if(isset($info_type) && !empty($info_type))
        {
            foreach ($info_type as  $value) 
            {
                $search_key  = $value['id'];
                $search_type = $value['type'];

                $where_simple = array('contacts.user_id'=>$this->user_id);
                $this->db->where("FIND_IN_SET('$search_key',contacts.contact_type_id) !=", 0);
                $this->db->where('unsubscribed !=', 0);
                $where = array('where'=>$where_simple);

                $this->db->select("count(contacts.id) as number_count",false);    

                $contact_details = $this->basic->get_data('contacts', $where, $select='', $join='', $limit='', $start='', $order_by='contacts.first_name', $group_by='', $num_rows=0);
                foreach ($contact_details as $key2 => $value2) 
                {
                    if($value2['number_count']>0)
                    $group_name[$search_key] = $search_type." (".$value2['number_count'].")";
                }
                    
            }  
        }   
        
        /*** get Sms Template ***/
        $where = array("where" => array('user_id'=>$this->user_id));
        $data['sms_template'] = $this->basic->get_data('message_template', $where, $select=array("id","template_name"), $join='', $limit='', $start='', $order_by='template_name ASC', $group_by='', $num_rows=0);

        $where_simple = array();
        $temp_userid = $this->user_id;

        /***get sms config***/
        if($this->config->item('sms_api_access') == '1' && $this->session->userdata("user_type") == 'User')
        {
            $join = array('users' => 'sms_api_config.user_id=users.id,left');
            $select = array('sms_api_config.*','users.id AS usersId','users.user_type');
            $where_in = array('sms_api_config.user_id'=>array('1',$temp_userid),'users.user_type'=>array('Admin','User'));
            $where = array('where_simple'=> array('status'=>'1'),'where_in'=>$where_in);
            $sms_api_config=$this->basic->get_data('sms_api_config', $where, $select, $join, $limit='', $start='', $order_by='phone_number ASC', $group_by='', $num_rows=0);
        } else
        {
            $where = array("where" => array('user_id'=>$temp_userid,'status'=>'1'));
            $sms_api_config=$this->basic->get_data('sms_api_config', $where, $select='', $join='', $limit='', $start='', $order_by='phone_number ASC', $group_by='', $num_rows=0);
        }
        
        $sms_api_config_option=array();
        foreach ($sms_api_config as $info) {
            $id=$info['id'];
            $sms_api_config_option[$id]=$info['gateway_name'].": ".$info['phone_number'];
        }

        $data['sms_option'] = $sms_api_config_option;
        $data['groups_name']  = isset($group_name) ? $group_name: "";
        $data["time_zone"]     = $this->_time_zone_list();
        $data['page_title'] = $this->lang->line('Add SMS Campaign');
        $this->_viewcontroller($data);
    }
    
    public function add_schedule_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $schedule_name = strip_tags(trim($this->input->post('schedule_name', true)));
        $message       = $this->input->post('message', true);
        $schedule_time = $this->input->post('schedule_time');
        $time_zone     = strip_tags(trim($this->input->post('time_zone', true)));
        $sms_api       = strip_tags(trim($this->input->post('from_sms', true)));
        $to_numbers    = trim($this->input->post('to_numbers', true));
        $country_code_add  = trim($this->input->post('country_code_add', true));
        $country_code_remove  = trim($this->input->post('country_code_remove', true));

        if($time_zone=='')
        {
            $time_zone = "Europe/Dublin";
        }

        if(!empty($to_numbers))
        {
            $exploded_to_numbers = explode(',',$to_numbers);
            $exploded_to_numbers = array_unique($exploded_to_numbers);

        }

        $successfully_sent = 0;
        $added_at          = date("Y-m-d H:i:s");
        $posting_status    = "0";

        $contacts_sms_group = $this->input->post('contacts_id', true);
        if(isset($contacts_sms_group) && !empty($contacts_sms_group))
            $contact_groupid    = implode(",",$contacts_sms_group);

        $manual_numbers = array();

        $report = array();

        $contacts_id = array();


        if(isset($contacts_sms_group) && !empty($contacts_sms_group))
        foreach ($contacts_sms_group as $key => $value) 
        {
            $where_simple = array('contacts.user_id'=>$this->user_id);
            $this->db->where("FIND_IN_SET('$value',contacts.contact_type_id) !=", 0);
            $where = array('where'=>$where_simple);    
            $contact_details = $this->basic->get_data('contacts', $where);

            foreach ($contact_details as $key2 => $value2) 
            {   
                if($value2['phone_number'] == "") continue;

                if(isset($country_code_add) & $country_code_add != '')
                {
                    

                    if(!preg_match("/^\+?{$country_code_add}/",$value2['phone_number'])) 
                    {
                        $value2['phone_number'] = $country_code_add.$value2['phone_number'];
                    }
                }
                else if(isset($country_code_remove) && $country_code_remove != '')
                {
                    // $value2['phone_number'] = preg_replace("/^\+?{$country_code_remove}/", '',$value2['phone_number']);
                    if(preg_match("/^\+?{$country_code_remove}/",$value2['phone_number'])) {
                        $value2['phone_number'] = preg_replace("/^\+?{$country_code_remove}/",'',$value2['phone_number']);
                    }
                }
                

                $report[$value2['phone_number']] = array(
                    'api_id'              => $sms_api,
                    'contact_id'          => $value2['id'],
                    'contact_first_name'  => isset($value2['first_name']) ? $value2['first_name']:"",
                    'contact_last_name'   => isset($value2['last_name']) ? $value2['last_name']:"",
                    'contact_email'       => isset($value2['email']) ? $value2['email']:"",
                    'contact_phone_number'=> isset($value2['phone_number']) ? $value2['phone_number']:"",
                    'delivery_id'         =>'pending',
                    'sent_time'           =>'pending',
                );

                $contacts_id[] = isset($value2["id"]) ? $value2["id"]: "";
            }
        }

        $contacts_id = array_filter($contacts_id);
        $contacts_id = array_unique($contacts_id);
        $contacts_id = implode(',', $contacts_id);

        // for manual phone number insertion
        $manual_thread = 0;
        if(isset($exploded_to_numbers))
        {
            foreach ($exploded_to_numbers as $single_values) 
            {
                if(isset($country_code_add) & $country_code_add != '')
                {
                    if(!preg_match("/^\+?{$country_code_add}/",$single_values)) 
                    {
                         $single_values = $country_code_add.$single_values;
                    }
                }
                else if(isset($country_code_remove) && $country_code_remove != '')
                {
                    // $single_values = preg_replace("/^\+?{$country_code_remove}/", '',$single_values);
                    if(preg_match("/^\+?{$country_code_remove}/",$single_values)) {
                        $single_values = preg_replace("/^\+?{$country_code_remove}/",'',$single_values);
                    }
                }

                $report[$single_values] = array(
                    'api_id' => $sms_api,
                    'contact_id' => '',
                    'contact_first_name'=> "",
                    'contact_last_name'=> "",
                    'contact_email'=> "",
                    'contact_phone_number'=>$single_values,
                    'delivery_id' =>'pending',
                    'sent_time' =>'pending',
                );
                $manual_thread++;
            }
        }

        $thread = count($report);

        // inserting data of sms_campaign_campaign Table
        $inserted_data = array(
            "user_id"           => $this->user_id,
            "api_id"            => $sms_api,
            "contact_ids"       => isset($contacts_id) ? $contacts_id:"",
            'contact_type_id'   => isset($contact_groupid) ? $contact_groupid:"",
            "campaign_name"     => $schedule_name,
            "campaign_message"  => $message,
            'manual_phone'      => $to_numbers,
            "posting_status"    => $posting_status, 
            "schedule_time"     => $schedule_time,
            "report"            => json_encode($report),
            "time_zone"         => $time_zone,
            "total_thread"      => $thread,
            "successfully_sent" => $successfully_sent,
            "added_at"          => $added_at,
        );


        if($this->basic->insert_data("sms_sending_campaign", $inserted_data))
        {
            // getting inserted row id
            $campaign_id = $this->db->insert_id();

            $report_insert = array();
            foreach ($report as $key=>$value) 
            {
                $report_insert = array(
                    'user_id'              => $this->user_id,
                    'sms_api_id'           => $value['api_id'],
                    'campaign_id'          => $campaign_id,
                    'contact_id'           => $value['contact_id'],
                    'contact_first_name'   => $value['contact_first_name'],
                    'contact_last_name'    => $value['contact_last_name'],
                    'contact_email'        => $value['contact_email'],
                    'contact_phone_number' => $key,
                    'delivery_id'          => 'pending',
                    'sent_time'            => '',
                    'processed'            => '0'
                );
                
                $this->basic->insert_data("sms_sending_campaign_send", $report_insert);
            }

            $this->session->set_flashdata('success_message', 1);
        }

    }

    public function campaign_sent_status()
    {
        if(!$_POST) exit();
        $id = $this->input->post("id");

        $campaign_data     = $this->basic->get_data("sms_sending_campaign",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));
        $report            = isset($campaign_data[0]["report"]) ? json_decode($campaign_data[0]["report"],true) : array();
        $campaign_name     = isset($campaign_data[0]["campaign_name"]) ? $campaign_data[0]["campaign_name"] : "";
        $campaign_message  = isset($campaign_data[0]["campaign_message"]) ? $campaign_data[0]["campaign_message"] : "";
        $total_thread      = isset($campaign_data[0]["total_thread"]) ? $campaign_data[0]["total_thread"] : 0;
        $successfully_sent = isset($campaign_data[0]["successfully_sent"]) ? $campaign_data[0]["successfully_sent"] : 0;

        $campaign_message_send = $campaign_message;

        // edit message url
        $edit_url = site_url('my_sms/edit_message_content/'.$campaign_data[0]['id']);

        // edit button generated
        $edit_button =  "<a title=".$this->lang->line("edit campaign")." href='".$edit_url."' class='btn-sm btn btn-outline-warning'><i class='fa fa-edit'></i> ".$this->lang->line("edit message")."</a>";

        $posting_status = $campaign_data[0]['posting_status'];

        // checking posting status from sms_sending_campaign table
        if( $posting_status == '2') 
            $posting_status = '<span class="label label-light"><i class="fa fa-check green"></i> '.$this->lang->line("completed").'</span>';
        else if( $posting_status == '1') 
            $posting_status = '<span class="label label-light"><i class="fa fa-spinner orange"></i> '.$this->lang->line("processing").'</span>';
        else if( $posting_status == '3') 
            $posting_status = '<span class="label label-light"><i class="fa fa-remove black"></i> '.$this->lang->line("stopped").'</span>';
        else 
            $posting_status = '<span class="label label-light"><i class="fa fa-remove red"></i> '.$this->lang->line("pending").'</span>';


        // Taken a variable to showing data on table
        $response = "";
        if(count($report) == 0)
        {
            $response.= "<h4><div class='alert alert-warning text-center'>".$this->lang->line("no data found for campaign")." <b>".$campaign_name."</b>.</div></h4>";
            echo $response;
            exit();
        }

        $response .= '<script>
                    $j(document).ready(function() {
                        $(".table-responsive").mCustomScrollbar({
                            autoHideScrollbar:true,
                            theme:"3d-dark",          
                            axis: "x"
                        });   
                        $("#campaign_report").DataTable();
                    });
                 </script>';

        $restart_button = '<span class="btn-sm btn btn-outline-primary restart_button" style="cursor:pointer;" table_id="'.$id.'"><i class="fa fa-refresh"></i> '.$this->lang->line('Resend from where it is left off').'</span> ';

        $response .= "<h4><span class='pull-left'></span>".$campaign_name."<span class='pull-right'>".$posting_status."</span></h4><span class='pull-right'>".$edit_button." ".$restart_button."</span><div class='clearfix'></div>";
        $response .= "<h4 class='text-center'><div class='blue' style=''>{$this->lang->line("successfully sent")} {$successfully_sent} {$this->lang->line("message out of")} {$total_thread}</div></h4><hr>";

        // datatabl section started
        $response .="<div class='table-responsive'>";
        $response .="<table id='campaign_report' class='table table-hover table-bordered table-striped table-condensed nowrap'>";
        $response .= "<thead><tr>";
        $response .= "<th class='text-center'>{$this->lang->line("sl.")}</th>";
        $response .= "<th class='text-center'>{$this->lang->line("First Name")}</th>";
        $response .= "<th class='text-center'>{$this->lang->line("Last Name")}</th>";
        $response .= "<th class='text-center'>{$this->lang->line("Email")}</th>";
        $response .= "<th class='text-center'>{$this->lang->line("Phone Number")}</th>";
        $response .= "<th class='text-center'>{$this->lang->line("sent at")}</th>";
        $response .= "<th class='text-center'>{$this->lang->line("Response")}</th>";
        $response .= "</tr></thead>";
        $i=0;

        // table data showing
        foreach ($report as $key => $value)
        {
            if(!isset($value["contact_first_name"])) $value["contact_first_name"] = "";
            if(!isset($value["contact_last_name"])) $value["contact_last_name"] = "";
            if(!isset($value["contact_email"])) $value["contact_email"] = "";
            if(!isset($value["contact_phone_number"])) $value["contact_phone_number"] = "";
            if(!isset($value["sent_time"])) $value["sent_time"] = "pending";
            if(!isset($value["delivery_id"])) $value["delivery_id"] = "";

            $message_sent_id_formatted = $value["delivery_id"];

            if($message_sent_id_formatted == "pending") 
                $message_sent_id_formatted = "<span class='label label-light'><i class='fa fa-close red'></i> ".$this->lang->line('pending')."</span>";

            $sent_time_formatted="x";
            if($value["sent_time"]!=="pending" && $value["sent_time"]!=="x") $sent_time_formatted = date("M j, y H:i",strtotime($value["sent_time"]));

            $i++;
            $response .= "<tr>";
            $response .= "<th class='text-center'>".$i."</th>";
            $response .= "<th class='text-center'>".$value['contact_first_name']."</th>";
            $response .= "<th class='text-center'>".$value['contact_last_name']."</th>";
            $response .= "<th class='text-center'>".$value['contact_email']."</th>";
            $response .= "<th class='text-center'>".$value['contact_phone_number']."</th>";

            $response .= "<th class='text-center'>".$sent_time_formatted."</th>";
            $response .= "<th class='text-center'>".$message_sent_id_formatted."</th>";
            $response .= "</tr>";

        }

        $response .= "</table></div>";
        $response.="<br/><div class='well'><h5 class='blue'>{$this->lang->line("original message :")} </h5>".nl2br($campaign_message_send)."</div>";

        // sending response to view through ajax
        echo $response;
    }

    public function edit_sms_campaign($id=0)
    {
        if($id==0) exit();

        $data['body']          = "my_sms/schedule_sms/edit_sms_campaign";
        $data["time_zone"]     = $this->_time_zone_list();
        $data["campaign_data"] = $this->basic->get_data("sms_sending_campaign",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));
        $data['selected_contact_gorups'] = explode(",",$data['campaign_data'][0]['contact_type_id']);

        // only pending campaigns are editable
        if(!isset($data["campaign_data"][0]["posting_status"]) || $data["campaign_data"][0]["posting_status"]!='0' ) exit();

        // only scheduled campaigns can be editted
        if(!isset($data["campaign_data"][0]["time_zone"]) || $data["campaign_data"][0]["time_zone"]=='' ) exit();
        
        /**Get contact number and contact_type***/
        $user_id = $this->user_id;
        $table_type = 'contact_type';   
        $where_type['where'] = array('user_id'=>$user_id);
        $info_type = $this->basic->get_data($table_type,$where_type,$select='', $join='', $limit='', $start='', $order_by='type');  
        $result = array();

        if(isset($info_type) && !empty($info_type))
        {
            foreach ($info_type as  $value) 
            {
                $search_key = $value['id'];
                $search_type = $value['type'];

                $where_simple = array('contacts.user_id' => $this->user_id);
                $this->db->where("FIND_IN_SET('$search_key',contacts.contact_type_id) !=", 0);
                $where = array('where'=>$where_simple);
                $this->db->select("count(contacts.id) as number_count",false);    
                $contact_details = $this->basic->get_data('contacts', $where, $select='', $join='', $limit='', $start='', $order_by='contacts.first_name', $group_by='', $num_rows=0);
            
                foreach ($contact_details as $key2 => $value2) 
                {
                    if($value2['number_count']>0)
                    $group_name[$search_key] = $search_type." (".$value2['number_count'].")";
                }
                    
            }  
        }   

        
        /*** get Sms Template ***/
        $where = array("where"=>array('user_id'=>$this->user_id));
        $data['sms_template'] = $this->basic->get_data('message_template', $where, $select=array("id","template_name"), $join='', $limit='', $start='', $order_by='template_name ASC', $group_by='', $num_rows=0);
                                                        
        /***get sms config***/
        if($this->config->item('sms_api_access') == '1' && $this->session->userdata("user_type") == 'User')
        {
            $join = array('users' => 'sms_api_config.user_id=users.id,left');
            $select = array('sms_api_config.*','users.id AS usersId','users.user_type');
            $where_in = array('sms_api_config.user_id'=>array('1',$this->user_id),'users.user_type'=>array('Admin','User'));
            $where = array('where_simple'=> array('status'=>'1'),'where_in'=>$where_in);
            $sms_api_config=$this->basic->get_data('sms_api_config', $where, $select, $join, $limit='', $start='', $order_by='phone_number ASC', $group_by='', $num_rows=0);
        } else
        {
            $where = array("where" => array('user_id'=>$this->user_id,'status'=>'1'));
            $sms_api_config=$this->basic->get_data('sms_api_config', $where, $select='', $join='', $limit='', $start='', $order_by='phone_number ASC', $group_by='', $num_rows=0);
        }
        
        $sms_api_config_option = array();

        foreach ($sms_api_config as $info) {
            $id = $info['id'];
            $sms_api_config_option[$id] = $info['gateway_name'].": ".$info['phone_number'];
        }

        $data['sms_option']=$sms_api_config_option;
        $data['groups_name']   = isset($group_name) ? $group_name: "";
        $data['page_title']    = $this->lang->line('Edit SMS Campaign');

        
        $this->_viewcontroller($data);   
    }

    public function edit_sms_campaign_action()
    {
        if(!$_POST) exit();

        $campaign_id   = $this->input->post('campaign_id',true);
        $schedule_name = strip_tags(trim($this->input->post('schedule_name', true)));
        $message       = $this->input->post('message', true);
        $schedule_time = $this->input->post('schedule_time');
        $time_zone     = strip_tags(trim($this->input->post('time_zone', true)));
        $sms_api       = strip_tags(trim($this->input->post('from_sms', true)));
        $to_numbers    = trim($this->input->post('to_numbers', true));
        $country_code_add  = trim($this->input->post('country_code_add', true));
        $country_code_remove  = trim($this->input->post('country_code_remove', true));

        if(!empty($to_numbers))
        {
            $exploded_to_numbers = explode(',',$to_numbers);
            $exploded_to_numbers = array_unique($exploded_to_numbers);
        }

        $successfully_sent  = 0;
        $posting_status     = "0";
        $added_at           = date("Y-m-d H:i:s");
        $contacts_sms_group = $this->input->post('contacts_id', true);
        if(isset($contacts_sms_group) && !empty($contacts_sms_group))
            $contact_groupid    = implode(",",$contacts_sms_group);

        $manual_numbers = array();

        $report = array();

        $contacts_id = array();

        $total_user = array();

        foreach ($contacts_sms_group as $key => $value) 
        {
            $where_simple = array('contacts.user_id'=>$this->user_id);
            $this->db->where("FIND_IN_SET('$value',contacts.contact_type_id) !=", 0);
            $where = array('where'=>$where_simple);

            $contact_details = $this->basic->get_data('contacts', $where, $select='');
            foreach ($contact_details as $key2 => $value2) 
            {
                if($value2['phone_number'] == "") continue;

                if(isset($country_code_add) & $country_code_add != '')
                {
                    if(!preg_match("/^\+?{$country_code_add}/",$value2['phone_number'])) 
                    {
                        $value2['phone_number'] = $country_code_add.$value2['phone_number'];
                    }
                }
                else if(isset($country_code_remove) && $country_code_remove != '')
                {
                    if(preg_match("/^\+?{$country_code_remove}/",$value2['phone_number'])) 
                    {
                        $value2['phone_number'] = preg_replace("/^\+?{$country_code_remove}/",'',$value2['phone_number']);
                    }

                }

                $report[$value2['phone_number']] = array(
                    'api_id'               => $sms_api,
                    'contact_id'           => $value2['id'],
                    'contact_first_name'   => isset($value2['first_name']) ? $value2['first_name']:"",
                    'contact_last_name'    => isset($value2['last_name']) ? $value2['last_name']:"",
                    'contact_email'        => isset($value2['email']) ? $value2['email']:"",
                    'contact_phone_number' => isset($value2['phone_number']) ? $value2['phone_number']:"",
                    'delivery_id'          =>'pending',
                    'sent_time'            =>'pending',
                );
              
                $contacts_id[] = isset($value2["id"]) ? $value2["id"]: "";
            }
        }
        
        // for manual phone number insertion into report
        $manual_thread = 0;
        if(isset($exploded_to_numbers))
        {
            foreach ($exploded_to_numbers as $single_values) 
            {
                if(isset($country_code_add) & $country_code_add != '')
                {
                    if(preg_match("/^\+?{$country_code_add}/",$single_values)) 
                    {
                        $single_values = $single_values;
                    }
                    else
                    { 
                        $single_values = $country_code_add.$single_values;
                    }

                }
                else if(isset($country_code_remove) && $country_code_remove != '')
                {
                    // $single_values = preg_replace("/^\+?{$country_code_remove}/", '',$single_values);
                    if(preg_match("/^\+?{$country_code_remove}/",$single_values)) {
                        $single_values = preg_replace("/^\+?{$country_code_remove}/",'',$single_values);
                    }
                    else
                    { 
                        $single_values = $single_values;
                    }

                } else
                {
                    $single_values = $single_values;
                }

                $report[$single_values] = array(
                    'api_id' => $sms_api,
                    'contact_id' => "",
                    'contact_first_name'=> "",
                    'contact_last_name'=> "",
                    'contact_email'=> "",
                    'contact_phone_number'=>$single_values,
                    'delivery_id' =>'pending',
                    'sent_time' =>'pending',
                );

                $manual_thread++;
            }
        }

        $thread = count($report);

        $contacts_id = array_filter($contacts_id);
        $contacts_id = array_unique($contacts_id);
        $contacts_id = implode(',', $contacts_id);

        // updating data of sms_campaign_campaign Table
        $updated_data = array(
            "user_id"           => $this->user_id,
            "api_id"            => $sms_api,
            "contact_ids"       => isset($contacts_id) ? $contacts_id:"",
            'contact_type_id'   => isset($contact_groupid) ? $contact_groupid:"",
            "campaign_name"     => $schedule_name,
            "campaign_message"  => $message,
            'manual_phone'      => $to_numbers,
            "posting_status"    => $posting_status, 
            "schedule_time"     => $schedule_time,
            "report"            => json_encode($report),
            "time_zone"         => $time_zone,
            "total_thread"      => $thread,
            "successfully_sent" => $successfully_sent,
            "added_at"          => $added_at,
        );

        /* updating sms_sending_campaign table data of the campaign */
        if($this->basic->update_data("sms_sending_campaign", array("id" => $campaign_id,"user_id"=>$this->user_id), $updated_data))
        {
            /* Delete the rows of updated campaign from sms_sending_campaign_send table */
            $this->basic->delete_data("sms_sending_campaign_send", array("campaign_id" =>$campaign_id));

            $report_insert = array();
            foreach ($report as $key=>$value) 
            {
                $report_insert = array(
                    'user_id'              => $this->user_id,
                    'sms_api_id'           => $value['api_id'],
                    'campaign_id'          => $campaign_id,
                    'contact_id'           => $value['contact_id'],
                    'contact_first_name'   => $value['contact_first_name'],
                    'contact_last_name'    => $value['contact_last_name'],
                    'contact_email'        => $value['contact_email'],
                    'contact_phone_number' => $key,
                    'delivery_id'          => 'pending',
                    'sent_time'            => '',
                    'processed'            => '0'
                );


                /* Inserting again the updated report data into sms_sending_campaign_send table */
                $this->basic->insert_data("sms_sending_campaign_send", $report_insert);
            }

            $this->session->set_flashdata('success_message', 1);
        }
    }


    public function edit_message_content($id=0)
    {
        if($id==0) exit();

        $data['body'] = "my_sms/schedule_sms/edit_message_content";
        $data['page_title'] = $this->lang->line("Edit Message");
        $data["xdata"] = $this->basic->get_data("sms_sending_campaign",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));
        $this->_viewcontroller($data);
    }

    public function edit_message_content_action()
    {
        if(!$_POST) exit();

        ignore_user_abort(TRUE);

        $user_id          = $this->user_id;
        $campaign_id      = $this->input->post("campaign_id");
        $campaign_message = $this->input->post('message');
        $edited_message   = array('campaign_message' => $campaign_message);

        $this->basic->update_data('sms_sending_campaign',array("id"=>$campaign_id,"user_id"=>$this->user_id),$edited_message); 
    }

    public function force_reprocess_campaign()
    {
        if(!$_POST) exit();

        $id    = $this->input->post("id");
        $where = array('id'=>$id,'user_id'=>$this->user_id);
        $data  = array('is_try_again'=>'1','posting_status'=>'1');
        $this->basic->update_data('sms_sending_campaign',$where,$data);

        if($this->db->affected_rows() != 0)
            echo "1";
        else
            echo "0";
    }

    public function restart_campaign()
    {
        if(!$_POST) exit();

        $id    = $this->input->post("table_id");
        $where = array('id'=>$id,'user_id'=>$this->user_id);
        $data  = array('is_try_again'=>'1','posting_status'=>'1');
        $this->basic->update_data('sms_sending_campaign',$where,$data);

        echo '1';
    }

    public function ajax_campaign_pause()
    {
        $table_id  = $this->input->post('table_id');
        $post_info = $this->basic->update_data('sms_sending_campaign',array('id'=>$table_id),array('posting_status'=>'3','is_try_again'=>'0'));

        echo 'success';
    }


    public function ajax_campaign_play()
    {
        $table_id  = $this->input->post('table_id');
        $post_info = $this->basic->update_data('sms_sending_campaign',array('id'=>$table_id),array('posting_status'=>'1','is_try_again'=>'1'));

        echo 'success';
    }

    public function delete_campaign()
    {
        if(!$_POST) exit();

        $id = $this->input->post("id");

        if($this->basic->delete_data("sms_sending_campaign",array("id"=>$id,"user_id"=>$this->user_id)))
        {
            if($this->basic->delete_data("sms_sending_campaign_send",array("campaign_id"=>$id,"user_id"=>$this->user_id)))
            echo "1";
        }
        else echo "0";
    }
    // end sms sending functions

    
    public function schedule_contacts()
    {
        $schedule_info=$_POST['schedule_info'];
        $contacts=$schedule_info['contact_ids'];
        $contacts=explode(",", $contacts);
        
        $where_in = array('id'=> $contacts);
        $where = array('where_in'=> $where_in);
        $data['contact_details']=$this->basic->get_data('contacts', $where, $select='', $join='', $limit='', $start='', $order_by='', $group_by='', $num_rows=0);
        
        $this->load->view('my_sms/schedule_sms/schedule_contact_details', $data);
    }
    
    /*** Delete Schedule ******/
    public function delete_schedule($id=0)
    {
        if($id==0) exit(); 
        $where=array("id"=>$id);
      
        if($this->basic->delete_data('schedule_sms', $where))
        {
            $this->session->set_flashdata('delete_success_message', 1);
            redirect('my_sms/scheduled_sms', 'location');
        }
    }
            
    public function birthday_sms()
    {
        //This process is important to reset the last serial no...
        $page = $this->input->get_post('page');
        if ($page == '') {
            $this->session->set_userdata('birthdaySMSLastSerial', "");
        } else {
            $per_page = $this->input->get_post('per_page');
            $start = ($page-1) * $per_page;
            $this->session->set_userdata('birthdaySMSLastSerial', $start);
        }

        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();

        $crud->where('birthday_reminder.deleted', '0');
        $crud->where('birthday_reminder.user_id', $this->user_id);

        $crud->unset_export();
        $crud->unset_print();

        $crud->set_theme('flexigrid');
        $crud->set_table('birthday_reminder');
        $crud->order_by('id');
        $crud->set_subject($this->lang->line('Scheduled SMS (Birthday Wish)'));
        $crud->required_fields('message', "time_zone", "api_id");
        $crud->columns('SL', 'api_id', 'time_zone', 'status');
        $crud->callback_field('message', array($this, 'message_field_with_instruction'));
        $crud->unset_texteditor("message");

        // for SL column				
        $crud->callback_column('SL', array($this, 'generateSerialBirhdaySMS'));
        
        $crud->callback_column('status', array($this, 'status_display_crud'));
        $crud->callback_column('api_id', array($this, 'api_display_crud'));
        $state = $crud->getState();
        if ($state == 'add' || $state=='edit') {
            $crud->callback_field('time_zone', array($this, 'time_zone_drop_down'));
            $crud->callback_field('sms_template', array($this, 'sms_template_field'));
            $crud->fields('api_id', 'sms_template', 'message', 'time_zone', 'status');
        }
        else $crud->fields('message', 'api_id', 'time_zone','status');
        $crud->callback_field('status', array($this, 'status_field_crud'));
        $crud->callback_field('api_id', array($this, 'api_field_crud'));

        // Only one schedule can be active at a time
        $crud->callback_after_insert(array($this, 'insert_user_id_birthday')); // insert id + check active functionalities as well
        $crud->callback_after_update(array($this, 'make_up_scheduler_setting_edit'));

        $crud->display_as('sms_template', $this->lang->line('SMS Template'));
        $crud->display_as('time_zone', $this->lang->line('Time Zone'));
        $crud->display_as('api_id', $this->lang->line('Send As'));
        $crud->display_as('message', $this->lang->line('Message'));
        $crud->display_as('status', $this->lang->line('Status'));
        
        
        $output = $crud->render();
        $data['page_title'] = 'Birthday Wish SMS';
        $data['output']=$output;
        $data['crud']=1;
        $this->_viewcontroller($data);
    }


    public function sms_history()
    {
        $data['body']="my_sms/sms_history/sms_history";
        $data['page_title'] = 'My SMS History';
        $this->_viewcontroller($data);
    }
    
    
    public function my_sms_history_data()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str=$sort." ".$order;
        
        $to_date="";
        $from_date="";
    
        if (isset($_POST['is_searched'])) {
            $to_date= strip_tags(trim($this->input->post('schedule_to_date', true)));
            $from_date= strip_tags(trim($this->input->post('schedule_from_date', true)));
        }
        
        
        if ($to_date) {
            $to_date=date('Y-m-d', strtotime($to_date));
            $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $to_date;
        }
        
        if ($from_date) {
            $from_date=date('Y-m-d', strtotime($from_date));
            $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $from_date;
        }
        
        $where_simple['sms_history.user_id']=$this->user_id;
        $where=array('where'=>$where_simple);
        $select=array("sms_history.*","CONCAT(sms_api_config.gateway_name,' : ',sms_api_config.phone_number) AS send_as");
        $join=array("sms_api_config"=>"sms_history.gateway_id=sms_api_config.id,left");
        $offset = ($page-1)*$rows;
        $result = array();
        $info=$this->basic->get_data('sms_history', $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='', $num_rows=0);
        $total_rows_array=$this->basic->count_row($table="sms_history", $where, $count="sms_history.id", $join);
        $total_result=$total_rows_array[0]['total_rows'];
        echo convert_to_grid_data($info, $total_result);
    }
    


    // public function send_sms(){

    //     $data['body']="my_sms/send_sms/send_sms";
        
    //     $user_id = $this->user_id;
    //     $table_type = 'contact_type';   
    //     $where_type['where'] = array('user_id'=>$user_id);
    //     $info_type = $this->basic->get_data($table_type,$where_type,$select='', $join='', $limit='', $start='', $order_by='type');  
    //     $result = array();

    //     $group_name=array();
    //     foreach ($info_type as  $value) 
    //     {
    //         $search_key = $value['id'];
    //         $search_type = $value['type'];

    //         $where_simple=array('contacts.user_id'=>$this->user_id);
    //         $this->db->where("FIND_IN_SET('$search_key',contacts.contact_type_id) !=", 0);
    //         $where=array('where'=>$where_simple);
    //         $this->db->select("count(contacts.id) as number_count",false);    
    //         $contact_details=$this->basic->get_data('contacts', $where, $select='', $join='', $limit='', $start='', $order_by='contacts.first_name', $group_by='', $num_rows=0);
        
    //         foreach ($contact_details as $key2 => $value2) 
    //         {
    //             if($value2['number_count']>0)
    //             $group_name[$search_key] = $search_type." (".$value2['number_count'].")";
    //         }
                
    //     }      

    //     /*** get Sms Template ***/
    //     $where=array("where"=>array('user_id'=>$this->user_id));
    //     $data['sms_template']=$this->basic->get_data('message_template', $where, $select=array('id','template_name'), $join='', $limit='', $start='', $order_by='template_name ASC', $group_by='', $num_rows=0);
                                                                
    //     /***get sms config***/
    //     $where=array("where"=>array('user_id'=>$this->user_id,'status'=>'1'));
    //     $sms_api_config=$this->basic->get_data('sms_api_config', $where, $select='', $join='', $limit='', $start='', $order_by='phone_number ASC', $group_by='', $num_rows=0);
        
    //     $sms_api_config_option=array();
    //     foreach ($sms_api_config as $info) {
    //         $id=$info['id'];
    //         $sms_api_config_option[$id]=$info['gateway_name'].": ".$info['phone_number'];
    //     }


    //     $data['sms_option']=$sms_api_config_option;
    //     // $data['contacts_info']=$contact_info;
    //     $data['groups_name']=$group_name;


    //     $data['page_title'] = 'Send SMS';
    //     $this->_viewcontroller($data);
    // }

    
    // public function sms_send_action()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         redirect('home/access_forbidden', 'location');
    //     }

    //     // echo "correct"; exit();

    //     $contacts_mobile_group=$this->input->post('contacts_mobile', true);
    //     $to_numbers= strip_tags(trim($this->input->post('to_numbers', true)));
    //     $to_numbers=explode(",", $to_numbers);
    //     $message=strip_tags(trim($this->input->post('message', true)));
        
    //     $all_contacts=array();

    //     if(!is_array($contacts_mobile_group))
    //     $contacts_mobile_group=array();

    //     $contacts_mobile=array();
    //     foreach ($contacts_mobile_group as $key => $value) 
    //     {
    //         $where_simple=array('contacts.user_id'=>$this->user_id);
    //         $this->db->where("FIND_IN_SET('$value',contacts.contact_type_id) !=", 0);
    //         $where=array('where'=>$where_simple);    
    //         $contact_details=$this->basic->get_data('contacts', $where, $select='phone_number');        
    //         foreach ($contact_details as $key2 => $value2) 
    //         {
    //             $contacts_mobile[] = $value2["phone_number"];                
    //         }

    //     }


    //     $contacts_mobile=array_filter($contacts_mobile);
    //     $to_numbers=array_filter($to_numbers);

    //     if(!empty($contacts_mobile))
    //     $all_contacts=array_add($contacts_mobile, $to_numbers);
    //     else
    //     $all_contacts=array_add($to_numbers, $contacts_mobile);        

    //     $all_contacts = array_unique($all_contacts);

    //     $config_id= strip_tags(trim($this->input->post('from_sms', true)));
        
        
    //     /**Get contact number and contact_type***/
    //     $contact_details=array();
    //     if(count($contacts_mobile)>0)
    //     {
    //         $where_in=array('phone_number'=>$contacts_mobile);
    //         $where_simple=array('user_id'=>$this->user_id);
    //         $where=array('where_in'=>$where_in,'where'=>$where_simple);
    //         $contact_details=$this->basic->get_data('contacts', $where, $select='', $join='', $limit='', $start='', $group_by='', $num_rows=0);
    //     }
    //     /***Set Sms Credential ***/
    //     $this->sms_manager->set_credentioal($config_id);
    //     $sent_number=array();
    
    //     foreach ($contact_details as $details) 
    //     {
    //         $first_name=$details['first_name'];
    //         $last_name=$details['last_name'];
    //         $phone_number=$details['phone_number'];
    //         $email=$details['email'];

    //         $message_replaced=$message;
    //         $message_replaced=str_replace("#firstname#", $first_name, $message_replaced);
    //         $message_replaced=str_replace("#lastname#", $last_name, $message_replaced);
    //         $message_replaced=str_replace("#mobile#", $phone_number, $message_replaced);
    //         $message_replaced=str_replace("#email#", $email, $message_replaced);

    //         if(in_array($phone_number,$sent_number)) continue;  
    //         $sent_number[]=$phone_number;

    //         $this->sms_manager->send_sms($message_replaced, $phone_number);
    //     }
        
    //     /***** Send sms which numbers are not in the contact list ******/
    //     $remaining_numbers=array_diff($all_contacts, $sent_number);
    //     $remaining_numbers=array_filter($remaining_numbers);
    //     $remaining_numbers=array_unique($remaining_numbers);
    //     foreach ($remaining_numbers as $numbers) 
    //     {
    //         if ($numbers!='') 
    //         {
    //             $this->sms_manager->send_sms($message, $numbers);
    //         }
    //     }
    // }


    //=================================================================================================================================
    // crud call back functions	
    public function status_field_crud($value, $row)
    {
        if ($value=='') {
            $value=1;
        }
        return form_dropdown('status', array(0=>$this->lang->line('Inactive'), 1=>$this->lang->line('Active')), $value, 'class="form-control" id="field-status"');
    }

    public function status_display_crud($value, $row)
    {
        if ($value==1) {
            return "<span class='label label-light'><i class='fa fa-check-circle green'></i> ".$this->lang->line('active')."</sapn>";
        } else {
            return "<span class='label label-light'><i class='fa fa-remove red'></i> ".$this->lang->line('inactive')."</sapn>";
        }
    }

    public function credit_display_crud($value, $row)
    {
        $gateway_name=$row->gateway_name;
        $credit="-";
        $this->sms_manager->set_credentioal($row->id,$this->user_id);
        if($gateway_name=="plivo") $credit=$this->sms_manager->get_plivo_balance();
        if($gateway_name=="clickatell") $credit=$this->sms_manager->get_clickatell_balance();
        if($gateway_name=="clickatell-platform") $credit=$this->sms_manager->get_clickatell_platform_balance();
        if($gateway_name=="nexmo") $credit=$this->sms_manager->get_nexmo_balance();
		if($gateway_name=="africastalking.com") $credit=$this->sms_manager->africastalking_sms_balance();
        if($gateway_name=="infobip.com") $credit=$this->sms_manager->infobip_balance_check();
		if($gateway_name=="Shreeweb") $credit=$this->sms_manager->get_shreeweb_balance();
		
		

        return $credit;
    }

    public function api_field_crud($value, $row)
    {
        /***get sms config***/
        $where=array("where"=>array('user_id'=>$this->user_id,'status'=>'1'));
        $sms_api_config=$this->basic->get_data('sms_api_config', $where, $select='', $join='', $limit='', $start='', $order_by='phone_number ASC', $group_by='', $num_rows=0);

        $str='';
        $str.='<select id="api_id" class="form-control" name="api_id">';
        $str.='<option value="">'.$this->lang->line('SMS API').'</option>';
        for ($i=0;$i<count($sms_api_config);$i++) {
            if ($sms_api_config[$i]['id']==$value) {
                $str.='<option selected="selected" value="'.$sms_api_config[$i]['id'].'">'.$sms_api_config[$i]['gateway_name'].": ".$sms_api_config[$i]['phone_number'].'</option>';
            } else {
                $str.='<option value="'.$sms_api_config[$i]['id'].'">'.$sms_api_config[$i]['gateway_name'].": ".$sms_api_config[$i]['phone_number'].'</option>';
            }
        }
        $str.='</select>';
        return $str;
    }

    public function api_display_crud($value, $row)
    {
        $where=array("where"=>array('birthday_reminder.user_id'=>$this->user_id,'birthday_reminder.id'=>$row->id));
        $join=array("sms_api_config"=>"sms_api_config.id=birthday_reminder.api_id,left");
        $sms_api_config=$this->basic->get_data('birthday_reminder', $where, $select=array("sms_api_config.gateway_name", "sms_api_config.phone_number"), $join);
        return $sms_api_config[0]['gateway_name']." : ".$sms_api_config[0]['phone_number'];
    }

    public function insert_user_id_sms_template($post_array, $primary_key)
    {
        $user_id=$this->user_id;
        $update_data=array('user_id'=>$user_id);
        $where=array("id"=>$primary_key);
        ;
        $this->basic->update_data("message_template", $where, $update_data);
    }

    public function insert_user_id_sms_api($post_array, $primary_key)
    {
        $user_id=$this->user_id;
        $update_data=array('user_id'=>$user_id);
        $where=array("id"=>$primary_key);
        $this->basic->update_data("sms_api_config", $where, $update_data);
    }
    
    public function message_field_with_instruction($value, $row)
    {
        return "<span class='hide_in_read'> 
                <a id='contact_first_name' class='btn btn-default btn-sm'>#FIRST_NAME#</a>
                <a id='contact_last_name' class='btn btn-default btn-sm'>#LAST_NAME#</a>
                <a id='contact_mobile_number' class='btn btn-default btn-sm'>#MOBILE#</a>
                <a id='contact_email_address' class='btn btn-default btn-sm'>#EMAIL_ADDRESS#</a>
                &nbsp; <a href='#' data-placement='top'  data-toggle='popover' title='".$this->lang->line("include lead user first name")."' data-content='".$this->lang->line("You can include #CONTACT_FIRST_NAME#, #CONTACT_LAST_NAME#, #CONTACT_MOBILE_NUMBER#, #CONTACT_EMAIL_ADDRESS# as variable inside your message. The variable will be replaced by corresponding real values when we will send it.")."'><i class='fa fa-info-circle'></i> </a> 
                </span>
                <textarea name='message' id='message'>$value</textarea>";
    }

    public function sms_template_field($value, $row)
    {
        $where=array("where"=>array('user_id'=>$this->user_id));
        $template=$this->basic->get_data('message_template', $where, $select=array("id","template_name"), $join='', $limit='', $start='', $order_by='template_name ASC', $group_by='', $num_rows=0);
                                                        
        $str= "<select id='message_template_birthday'>";
        
        $str.="<option value=''>".$this->lang->line("I want to write new messsage, don't want any template")."</option>";
        foreach ($template as $info) {
            $template_name=$info['template_name'];
            $id=$info['id'];
            // $message=htmlentities($info['message'], ENT_QUOTES);
            $str.= "<option value='{$id}'>{$template_name}</option>";
        }
        $str.= "</select>";
        
        return $str;
    }
    
    public function insert_user_id_birthday($post_array, $primary_key)
    {
        $user_id=$this->user_id;
        $update_data=array('user_id'=>$user_id);
        $where=array("id"=>$primary_key);
        ;
        $this->basic->update_data("birthday_reminder", $where, $update_data);

        if ($post_array['status']=='1') {
            $table="birthday_reminder";
            $where=array('id !='=> $primary_key);
            $data=array("status"=>"0");
            $this->basic->update_data($table, $where, $data);
        }

        return true;
    }


    public function make_up_scheduler_setting_edit($post_array, $primary_key)
    {
        if ($post_array['status']=='1') {
            $table="birthday_reminder";
            $where=array('id !='=> $primary_key);
            $data=array("status"=>"0");
            $this->basic->update_data($table, $where, $data);
        }
        return true;
    }

    public function generateSerialSMSAPI()
    {
        if ($this->session->userdata('SMSAPILastSerial') == '') {
            $this->session->set_userdata('SMSAPILastSerial', 0);
            $this->session->set_userdata('SMSAPILastPage', 1);
            $lastSerial = 0;
        } else {
            $lastSerial = $this->session->userdata('SMSAPILastSerial');
        }
        
        $lastSerial++;
        $page = $this->input->post('page');
        if ($page != '') {
            $this->session->set_userdata('SMSAPILastPage', $page);
        } else {
            $this->session->set_userdata('SMSAPILastPage', 1);
        }
        $this->session->set_userdata('SMSAPILastSerial', $lastSerial);
        return $lastSerial;
    }

    public function generateSerialSMSTemplate()
    {
        if ($this->session->userdata('SMSTemplateLastSerial') == '') {
            $this->session->set_userdata('SMSTemplateLastSerial', 0);
            $this->session->set_userdata('SMSTemplateLastPage', 1);
            $lastSerial = 0;
        } else {
            $lastSerial = $this->session->userdata('SMSTemplateLastSerial');
        }
        
        $lastSerial++;
        $page = $this->input->post('page');
        if ($page != '') {
            $this->session->set_userdata('SMSTemplateLastPage', $page);
        } else {
            $this->session->set_userdata('SMSTemplateLastPage', 1);
        }
        $this->session->set_userdata('SMSTemplateLastSerial', $lastSerial);
        return $lastSerial;
    }


    public function generateSerialBirhdaySMS()
    {
        if ($this->session->userdata('birthdaySMSLastSerial') == '') {
            $this->session->set_userdata('birthdaySMSLastSerial', 0);
            $this->session->set_userdata('birthdaySMSLastPage', 1);
            $lastSerial = 0;
        } else {
            $lastSerial = $this->session->userdata('birthdaySMSLastSerial');
        }
        
        $lastSerial++;
        $page = $this->input->post('page');
        if ($page != '') {
            $this->session->set_userdata('birthdaySMSLastPage', $page);
        } else {
            $this->session->set_userdata('birthdaySMSLastPage', 1);
        }
        $this->session->set_userdata('birthdaySMSLastSerial', $lastSerial);
        return $lastSerial;
    }

    

    
    
    //=================================================================================================================================
    // crud call back functions	
}
