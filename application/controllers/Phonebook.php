<?php 
require_once("Home.php");

class Phonebook extends Home
{
    public $user_id;
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login', 'location');
        }

        $this->important_feature();
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


    public function contact_group()
    {

        //This process is important to reset the last serial no...
        $page = $this->input->get_post('page');
        if ($page == '') 
        {
            $this->session->set_userdata('contactGroupLastSerial', "");
        } 
        else
        {
            $per_page = $this->input->get_post('per_page');
            $start = ($page-1) * $per_page;
            $this->session->set_userdata('contactGroupLastSerial', $start);
        }

        $this->load->database();
        $this->db->select();
        // $this->db->where('contact_type.deleted', '0');
        // // $this->db->where('contact_type.user_id', $this->user_id);
        // $res = $this->db->get('contact_type')->result_array();
        // print_r($res); exit();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();

        $crud->where('contact_type.deleted', '0');
        $crud->where('contact_type.user_id', $this->user_id);
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        
        $crud->set_theme('flexigrid');
        $crud->set_table('contact_type');
        $crud->order_by('type');
        $crud->set_subject($this->lang->line('Contact Group'));
        $crud->required_fields('type');
        $crud->columns('SL','type','id');

        // for SL column                
        $crud->callback_column('SL', array($this, 'generateSerialNoContactGroup'));

        $crud->fields('type');
        $crud->display_as('type', $this->lang->line('Contact Group Name'));
        $crud->display_as('id', $this->lang->line('Contact Group ID'));
        /**insert the user_id**/
        $crud->callback_after_insert(array($this, 'insert_user_id_group'));

        $output = $crud->render();
        $data['page_title'] = 'Contact Group';
        $data['output'] = $output;
        $data['crud']= 1;
        $this->_viewcontroller($data);
    }


    public function contact_list(){
        $data['page_title'] = 'Contact';
        $data['body'] = 'phonebook/contact_list';

        $table = 'contact_type';
        $where['where'] = array('user_id'=>$this->user_id);

        $info = $this->basic->get_data($table,$where);

        foreach ($info as $key => $value) {
            $result = $value['id'];
            $data['contact_type_id'][$result] = $value['type'];
        }
        $this->_viewcontroller($data);
    }


    public function contact_list_data()
        {

        // setting variables for pagination
        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'first_name';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $order_by_str=$sort." ".$order;


        // setting properties for search
        $first_name = trim($this->input->post('first_name', true));
        $last_name  = trim($this->input->post('last_name', true));
        $phone_number      = trim($this->input->post("phone_number", true));
        $email = $this->input->post('email', true);
        $contact_type_id = $this->input->post('contact_type_id', true);

        $dob = $this->input->post('dob', true);
        $dob = date('Y-m-d', strtotime($dob));
        

         // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('contact_list_first_name',$first_name);
            $this->session->set_userdata('contact_list_last_name',$last_name);
            $this->session->set_userdata('contact_list_phone_number',$phone_number);
            $this->session->set_userdata('contact_list_email', $email);
            $this->session->set_userdata('contact_list_contact_type_id',$contact_type_id);
            $this->session->set_userdata('contact_list_dob',$dob);
            //  $this->session->set_userdata('book_list_category',$category_id);
        }

            // saving session data to different search parameter variables
        $search_first_name      = $this->session->userdata('contact_list_first_name');
        $search_last_name       = $this->session->userdata('contact_list_last_name');
        $search_phone_number    = $this->session->userdata('contact_list_phone_number');
        $search_email           = $this->session->userdata('contact_list_email');
        $search_contact_type_id = $this->session->userdata('contact_list_contact_type_id');
        $search_dob             = $this->session->userdata('contact_list_dob');
        //  $search_category=$this->session->userdata('book_list_category');

            // creating a blank where_simple array
        $where_simple=array();

            // trimming data
        if ($search_first_name) 
        {
            $where_simple['first_name like '] = "%".$search_first_name."%";
        }
        if ($search_last_name) 
        {
            $where_simple['last_name like '] = "%".$search_last_name."%";
        }
        if ($search_phone_number) 
        {
            $where_simple['phone_number like ']    = "%".$search_phone_number."%";
        }

        if ($search_email) 
        {
            $where_simple['email like ']    = "%".$search_email."%";
        }

        if ($search_contact_type_id) 
        {
            // $where_simple['contact_type_id like ']    = "%".$search_contact_type_id."%";
            $this->db->where("FIND_IN_SET('$search_contact_type_id',contacts.contact_type_id) !=", 0);
        }

        if ($search_dob) 
        {
            if ($search_dob != '1970-01-01') 
            {
                $where_simple['date_birth like ']= "%".$search_dob."%";
            }
        }

        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
     
        $where_simple['user_id'] = $user_id;
        $where  = array('where'=>$where_simple);

        $offset = ($page-1)*$rows;
        $result = array();       

        $table = "contacts";        

        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str);
        // echo $this->db->last_query();        
        if ($search_contact_type_id) 
        {
            // $where_simple['contact_type_id like ']    = "%".$search_contact_type_id."%";
            $this->db->where("FIND_IN_SET('$search_contact_type_id',contacts.contact_type_id) !=", 0);
        }
        $total_rows_array = $this->basic->count_row($table, $where, $count="id");      
        // echo $this->db->last_query();
        $total_result = $total_rows_array[0]['total_rows'];

        $info_count = count($info);


        foreach ($info as $key => $value) 
        {
          $value = $info[$key]['contact_type_id'];

          $type_id = explode(",",$value);

           $table = 'contact_type';
          $select = array('type');

          $where_group['where_in'] = array('id'=>$type_id);
          $where_group['where'] = array('deleted'=>'0');

          $info1 = $this->basic->get_data($table,$where_group,$select);

         $str = '';
         foreach ($info1 as  $value1)
          {
            $str.= $value1['type'].","; 
          }

            
        $str = trim($str, ",");

        $info[$key]['contact_type_id']= $str;


        }


        echo convert_to_grid_data($info, $total_result);

    }

    public function add_contact()
    {
         $data['body'] = 'phonebook/add_contact';
         $where = array();
         $where['where'] = array('user_id'=>$this->user_id);

         //Section for creating checkbox group.
         $group_info=$this->basic->get_data('contact_type', $where, $select='', $join='', $limit='', $start='', $order_by='type', $group_by='', $num_rows=0); 
         $str = '';
         $form_contact=array();
         if($_POST) 
         {
            if(!empty($_POST["contact_type_id"]))
            $form_contact=$this->input->post("contact_type_id");             
         }
         foreach ($group_info as $info) {
             $type =  $info['type'];            
             $type_id = $info['id'];

             if(!empty($form_contact)){                
                 if(in_array($type_id,$form_contact))
                 $str.= "<label class='checkbox-inline'><input checked='true' type='checkbox' name= 'contact_type_id[]' id = 'contact_type_id[]' value='{$type_id}'>{$type}</label><br/>";
              else
             $str.= "<label class='checkbox-inline'><input type='checkbox' name= 'contact_type_id[]' id = 'contact_type_id[]' value='{$type_id}'>{$type}</label><br/>";
             $data['group_checkbox'] = $str;

             }
              else
             $str.= "<label class='checkbox-inline'><input type='checkbox' name= 'contact_type_id[]' id = 'contact_type_id[]' value='{$type_id}'>{$type}</label><br/>";
             $data['group_checkbox'] = $str;

                  
         }//End of section.


         $this->_viewcontroller($data);
    }


    public function add_contact_action()
    {

        if ($_POST) 
        {
            $this->form_validation->set_rules('first_name',$this->lang->line('First Name'),'trim');
            $this->form_validation->set_rules('last_name',$this->lang->line("Last Name"),'trim');
            $this->form_validation->set_rules('phone_number',$this->lang->line("Mobile Number"),'trim');
            $this->form_validation->set_rules('email',$this->lang->line("Email"),'trim|valid_email');
            $this->form_validation->set_rules('from_date',$this->lang->line("Date of Birth"),'trim');              

            // $from_date = date of birth
            $from_date = $this->input->post('from_date', true);  
            if($from_date=="") $from_date="0000-00-00";
            else $from_date = date('Y-m-d', strtotime($from_date));  


            if ($this->form_validation->run() == false || (empty($_POST['contact_type_id'])) || (empty($_POST['phone_number']) && empty($_POST['email']))) 
            {
                if(empty($_POST['phone_number']) && empty($_POST['email']))                
                    $this->session->set_userdata('phone_number_email_error', 1); 

                if(empty($_POST['contact_type_id']))           
                   $this->session->set_userdata('group_type_error', 1); 

                 return $this->add_contact();
            }    

            else 
            {
                $first_name =           strip_tags($this->input->post('first_name', true));
                $last_name =            strip_tags($this->input->post('last_name', true));
                $phone_number =         strip_tags($this->input->post('phone_number', true));
                $email =                strip_tags($this->input->post('email', true));
                $date_birth = $from_date;

                $user_id = $this->session->userdata('user_id');

                $temp = $this->input->post('contact_type_id', true);
                $type = '';
                if ($temp) 
                {
                    $type = implode($temp, ',');
                }

                $contact_type_id = $type;

                $data = array(
                               "first_name" => $first_name,
                               "last_name" => $last_name,
                               "phone_number" => $phone_number,
                               "email" => $email,
                               "date_birth" => $date_birth,
                               "contact_type_id" => $contact_type_id,                                  
                               "user_id" => $user_id                                   
                            );
                if(!empty($data)){
                    $this->basic->insert_data("contacts",$data);
                    $success = 1;
                    $this->session->set_flashdata('success_message', 1);
                    redirect('phonebook/contact_list', 'location');
                }
                
                else {
                    $this->session->set_flashdata("error_message", 1);
                    redirect('phonebook/contact_list', 'location');
                }                       
            }               
        }
    }

    public function update_contact($id = 0)
    {  
       if($id==0) exit();

       $data['body'] = 'phonebook/update_contact';
       $where = array();
       $where['where'] = array('user_id'=>$this->user_id);      

       $group_info=$this->basic->get_data('contact_type', $where, $select='', $join='', $limit='', $start='', $order_by='type', $group_by='', $num_rows=0); 

     
       $where_contacts["where"] = array("contacts.id" => $id,"contacts.user_id"=>$this->user_id); 
       $result = $this->basic->get_data("contacts",$where_contacts);
       $data['info'] = $result[0];

         $str = '';
         $form_contact=array();
         if($_POST && isset($_POST["contact_type_id"])) 
         {
            // $form_contact=$_POST["contact_type_id"];
            $form_contact=$this->input->post('contact_type_id',true);
         }
         else
         {
            $form_contact_str=$result[0]["contact_type_id"];
            $form_contact=explode(',',$form_contact_str);
         }
         foreach ($group_info as $info) 
         {
             $type =  $info['type'];            
             $type_id = $info['id'];
             if(in_array($type_id,$form_contact))
             $str.= "<label class='checkbox-inline'><input checked='true' type='checkbox' name= 'contact_type_id[]' id = 'contact_type_id[]' value='{$type_id}'>{$type}</label><br/>";
             else
            $str.= "<label class='checkbox-inline'><input type='checkbox' name= 'contact_type_id[]' id = 'contact_type_id[]' value='{$type_id}'>{$type}</label><br/>";
             $data['group_checkbox'] = $str; 
         }

       $this->_viewcontroller($data);
    }


    public function update_contact_action($id = 0)
    {

       if ($_POST) 
        {
            $this->form_validation->set_rules('first_name','First Name','trim');
            $this->form_validation->set_rules('last_name',"Last Name",'trim');
            $this->form_validation->set_rules('phone_number',"Mobile Number",'trim');
            $this->form_validation->set_rules('email',"Email",'trim|valid_email');
            $this->form_validation->set_rules('from_date',"Date of Birth",'trim'); 

            $from_date = $this->input->post('from_date', true);  
            if($from_date=="") $from_date="0000-00-00";
            else $from_date = date('Y-m-d', strtotime($from_date));  


            if ($this->form_validation->run() == false || (!isset($_POST['contact_type_id'])) || (empty($_POST['phone_number']) && empty($_POST['email']))) 
            {
                if(empty($_POST['phone_number']) && empty($_POST['email']))                
                    $this->session->set_userdata('phone_number_email_error', 1); 

                if(!isset($_POST['contact_type_id']))                
                    $this->session->set_flashdata('reset_success', '<br/>'.'<b>'.$this->lang->line('Contact Group').'</b>'.str_replace("%s","", $this->lang->line("required")));

                return $this->update_contact($id);
            }    

            else 
            {
                $first_name =           strip_tags($this->input->post('first_name', true));
                $last_name =            strip_tags($this->input->post('last_name', true));
                $phone_number =         strip_tags($this->input->post('phone_number', true));
                $email =                strip_tags($this->input->post('email', true));
                $date_birth = $from_date;              

                $temp = $this->input->post('contact_type_id', true);
                $type = '';
                if ($temp) 
                {
                    $type = implode($temp, ',');
                }

                $contact_type_id = $type;

                $data = array(
                               "first_name" => $first_name,
                               "last_name" => $last_name,
                               "phone_number" => isset($phone_number) ? $phone_number:"",
                               "email" => isset($email) ? $email:"",
                               "date_birth" => $date_birth,
                               "contact_type_id" => $contact_type_id                                               
                            );

                $where = array("contacts.id" => $id,"contacts.user_id"=>$this->user_id ); 

                if(!empty($data)){
                    $this->basic->update_data("contacts", $where, $data);
                    $success = 1;
                    $this->session->set_flashdata('success_message', 1);
                    redirect('phonebook/contact_list', 'location');
                }
                
                else {
                    $this->session->set_flashdata("error_message", 1);
                    redirect('phonebook/contact_list', 'location');
                }                       
            }               
        }
    }

    public function delete_contact_action()
    {   
        $table_id = $this->input->post("table_id",true);

        if($table_id == '0') exit;

        $this->basic->update_data('contacts',array("id"=>$table_id,"user_id"=>$this->user_id),array("deleted"=>"1"));

        echo $this->db->affected_rows();
              
    }

    public function import_contact()
    {       
        $data['body']="phonebook/import_contact";
        $data['contact_info']=$this->get_contact_types();
        $this->_viewcontroller($data);
    }

    public function import_contact_action_ajax()
    {        
        $user_id=$this->user_id;
        $contact_group = $this->input->post("contact_type");

        if(!is_array($contact_group))
            $contact_group = array();

        $contact_groups = implode(",",$contact_group);

        $this->load->library('upload');
        $filename=$this->user_id."_"."contact"."_".time().substr(uniqid(mt_rand(), true), 0, 6);
        $config['file_name'] = $filename;
        $config['upload_path'] = './upload/csv/';
        $config['allowed_types'] = 'text/plain|text/anytext|csv|text/x-comma-separated-values|text/comma-separated-values|application/octet-stream|application/vnd.ms-excel|application/x-csv|text/x-csv|text/csv|application/csv|application/excel|application/vnd.msexcel';
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload('csv_file') != true) {
            $upload_image = array('upload_data' => $this->upload->data());
            $temp = $upload_image['upload_data']['file_name'];
            $response['status']=$this->upload->display_errors();
        } else {
            $upload_image = array('upload_data' => $this->upload->data());
            $csv= realpath(FCPATH.'upload/csv/'.$upload_image['upload_data']['file_name']);

            if (!file_exists($csv) || !is_readable($csv)) {
                $response['status']="File is not readable.";
            } else {
                $delimiter=',';
                $header = null;
                $data = array();
                if (($handle = fopen($csv, 'r')) !== false) {
                    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) 
                    {
                           $data[] =  $row;                        
                    }
                    fclose($handle);
                }

                $this->db->trans_start();
                $count_insert=0;
                foreach ($data as $row) 
                {
                    $insert_data=array();
                    $insert_data['user_id']=$user_id;
                    $insert_data['deleted']='0';
                    $insert_data['first_name'] = isset($row[0]) ? $row[0]:"";
                    $insert_data['last_name'] = isset($row[1]) ? $row[1]:"";
                    $insert_data['phone_number'] = isset($row[2]) ? $row[2]:"";
                    $insert_data['email'] = isset($row[3]) ? $row[3]:"";

                    $date_birth = $row[4];  
                    if($date_birth=="") $date_birth=="0000-00-00";
                    else $date_birth = date('Y-m-d', strtotime($date_birth));  

                    $insert_data['date_birth'] = $date_birth;
                    $insert_data['contact_type_id'] = $contact_groups;

                    if($insert_data["email"]=="email" || $insert_data["phone_number"]=="phone_number")
                    continue;

                    $this->basic->insert_data("contacts", $insert_data);
                    $count_insert++;
                }
                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    $response['status']='Database error occoured. Please try again.';
                } else {
                    $this->session->set_flashdata('success_message', 1);
                    $response['status']='ok';
                    $response['count']=$count_insert;
                }
            }
        }

        $response['status']=str_replace("<p>", "", $response['status']);
        $response['status']=str_replace("</p>", "", $response['status']);

        echo json_encode($response);
    }

   
     public function url_with_email_wise_download()
    {
        $table = 'contacts';       

        $selected_grid_data = $this->input->post('info', true);
        $url_names = json_decode($selected_grid_data, true);
        $url_names_array = array();
        foreach ($url_names as  $value) {
            $id_array[] = $value['id'];
        }
        $where['where_in'] = array('id' => $id_array);        
        
        
        
        $info = $this->basic->get_data('contacts',$where);

         $info_count = count($info);

        for($i=0; $i<$info_count; $i++)
        {
              $value = $info[$i]['contact_type_id'];

              $type_id = explode(",",$value);

              $table = 'contact_type';
              $select = array('type');

              $where_group['where_in'] = array('id'=>$type_id);
              $where_group['where'] = array('deleted'=>'0');

              $info1 = $this->basic->get_data($table,$where_group,$select);

             $str = '';
             foreach ($info1 as  $value1)
              {
                $str.= $value1['type'].","; 
              }
                
            $str = trim($str, ",");

            $info[$i]['contact_type_id']= $str;
        }

        
        $file_name = "download/contact_export/exported_contact_list_".time()."_".$this->user_id.".csv";
        $fp = fopen($file_name, "w");
        $head=array("First Name","Last Name","Mobile No.","Email","Date of Birth");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
            $write_info=array();            
            $write_info[] = $value['first_name'];
            $write_info[] = $value['last_name'];
            $write_info[] = $value['phone_number'];
            $write_info[] = $value['email'];
            $write_info[] = $value['date_birth'];      
            fputcsv($fp, $write_info);  
        }

        fclose($fp);  
        echo $file_name;
        
    }


    public function insert_user_id_group($post_array, $primary_key)
    {
        $user_id=$this->user_id;
        $update_data=array('user_id'=>$user_id);
        $where=array("id"=>$primary_key);
        ;
        $this->basic->update_data("contact_type", $where, $update_data);
    }
    
     

    public function generateSerialNoContactGroup()
    {
        if ($this->session->userdata('contactGroupLastSerial') == '') {
            $this->session->set_userdata('contactGroupLastSerial', 0);
            $this->session->set_userdata('contactGroupLastPage', 1);
            $lastSerial = 0;
        } else {
            $lastSerial = $this->session->userdata('contactGroupLastSerial');
        }
        
        $lastSerial++;
        $page = $this->input->post('page');
        if ($page != '') {
            $this->session->set_userdata('contactGroupLastPage', $page);
        } else {
            $this->session->set_userdata('contactGroupLastPage', 1);
        }
        $this->session->set_userdata('contactGroupLastSerial', $lastSerial);
        return $lastSerial;
    }

}