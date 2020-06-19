<?php 
require_once("Home.php");

class User extends Home
{
    

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
        $this->user();
    }

    public function user()
    {
        //This process is important to reset the last serial no...
        // $page = $this->input->get_post('page');
        // if ($page == '') {
        //     $this->session->set_userdata('userManagementLastSerial', "");
        // } else {
        //     $per_page = $this->input->get_post('per_page');
        //     $start = ($page-1) * $per_page;
        //     $this->session->set_userdata('userManagementLastSerial', $start);
        // }

        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();

        $crud->where('users.deleted', '0');

        // $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();

        $crud->set_theme('flexigrid');
        $crud->set_table('users');
        $crud->order_by('first_name');
        $crud->set_subject($this->lang->line('User'));
        $crud->required_fields('first_name', 'last_name', 'email', 'mobile', 'username', 'password', 'status', 'user_type');
        $crud->columns('username', 'first_name', 'last_name', 'email', 'mobile', 'user_type', 'status','expired_date');
        $crud->add_fields('first_name', 'last_name', 'email', 'mobile', 'username', 'password', 'user_type', 'status');
        $crud->edit_fields('first_name', 'last_name', 'email', 'mobile', 'username', 'user_type', 'status','expired_date');
        $crud->field_type('password', 'password');
        $crud->field_type('expired_date', 'input');
        $crud->callback_field('status', array($this, 'status_field_crud'));
        $crud->callback_column('status', array($this, 'status_display_crud'));

        
        $crud->callback_after_insert(array($this, 'password_hash'));
        // $crud->callback_after_update(array($this, 'password_hash'));

        // for SL column				
        // $crud->callback_column('SL', array($this, 'generateSerialUserManagement'));
        
        $crud->set_rules('username', 'Username', 'callback_unique_username_check['.$this->uri->segment(4).']');
        $crud->set_rules('email', $this->lang->line('Email'), 'required|valid_email');
        
       
        $crud->add_action($this->lang->line('Change User Password'), 'fa fa-key', 'user/change_user_password');

        $crud->display_as('first_name', $this->lang->line('First Name'));
        $crud->display_as('last_name', $this->lang->line('Last Name'));
        $crud->display_as('password', $this->lang->line('Password'));
        $crud->display_as('user_type', $this->lang->line('User Type'));
        $crud->display_as('username', $this->lang->line('Username'));
        $crud->display_as('email', $this->lang->line('Email'));
        $crud->display_as('mobile', $this->lang->line('Mobile'));
        $crud->display_as('status', $this->lang->line('Status'));
        $crud->display_as('expired_date', $this->lang->line('expiry date'));
        

        $output = $crud->render();
        $data['page_title'] = 'User Management';
        $data['output']=$output;
        $data['crud']=1;
        $this->_viewcontroller($data);
    }


    public function change_user_password($id=0)
    {
        if ($id==0) {
            redirect('home/access_forbidden', 'location');
        }

        $this->session->set_userdata('change_user_password_id', $id);

        $table = 'users';
        $where['where'] = array('id'=>$id);

        $info = $this->basic->get_data($table, $where);

        $data['member_name'] = $info[0]['first_name'].' '.$info[0]['last_name'];

        $data['body'] = 'admin/user/change_user_password';
        $data['page_title'] = 'Reset User\'s Password';
        $this->_viewcontroller($data);
    }

    public function change_user_password_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $id = $this->session->userdata('change_user_password_id');
        
        if ($_POST) {
            $this->form_validation->set_rules('password',            '<b>'.$this->lang->line('Password').'</b>',         'trim|required');
            $this->form_validation->set_rules('confirm_password',   '<b>'.$this->lang->line('Confirm Password').'</b>', 'trim|required|matches[password]');
        }


        if ($this->form_validation->run() == false) {
            $this->change_user_password($id);
        } else {
            $new_password =  strip_tags($this->input->post('password', true));
            $new_confirm_password =  strip_tags($this->input->post('confirm_password', true));
        
            $table_change_password = 'users';
            $where_change_passwor = array('id'=>$id);
            $data = array('password'=>md5($new_password));
            $this->basic->update_data($table_change_password, $where_change_passwor, $data);
            $this->session->set_flashdata('success_message', 1);
            redirect('user', 'location');
        }
    }

        
    public function unique_username_check($str, $edited_id)
    {
        $username= strip_tags(trim($this->input->post('username', true)));
        
        if ($username=="") {
            $s = $this->lang->line("required");
            $s=str_replace("<b>%s</b>", "", $s);
            $s="<b>".$this->lang->line('Username')."</b> ".$s;
            $this->form_validation->set_message('unique_username_check', $s);
            return false;
        }
        
        if (!isset($edited_id) || !$edited_id) {
            $where=array("username"=>$username);
        } else {
            $where=array("username"=>$username,"id !="=>$edited_id);
        }
        
        
        $is_unique=$this->basic->is_unique("users", $where, $select='');
        
        if (!$is_unique) {
            $s = $this->lang->line("is_unique");
            $s=str_replace("<b>%s</b>", "", $s);
            $s="<b>".$this->lang->line('Username')."</b> ".$s;
            $this->form_validation->set_message('unique_username_check', $s);
            return false;
        }
                
        return true;
    }



    
    public function status_field_crud($value, $row)
    {
        if ($value=='') {
            $value=1;
        }
        return form_dropdown('status', array(0 => $this->lang->line('Inactive'), 1 => $this->lang->line('Active')), $value, 'class="form-control" id="field-status"');
    }

    public function status_display_crud($value, $row)
    {
        if ($value==1) {
             return "<span class='label label-light'><i class='fa fa-check-circle green'></i> ".$this->lang->line('active')."</sapn>";
        } else {
            return "<span class='label label-light'><i class='fa fa-remove red'></i> ".$this->lang->line('inactive')."</sapn>";
        }
    }
    
    public function password_hash($post_array, $primary_key)
    {
        $password=$post_array['password'];
        $encode_password=md5($password);
        $update_data=array('password'=>$encode_password);
        $where=array("id"=>$primary_key);
        ;
        $this->basic->update_data("users", $where, $update_data);
    }

    // public function generateSerialUserManagement()
    // {
    //     if ($this->session->userdata('userManagementLastSerial') == '') {
    //         $this->session->set_userdata('userManagementLastSerial', 0);
    //         $this->session->set_userdata('userManagementLastPage', 1);
    //         $lastSerial = 0;
    //     } else {
    //         $lastSerial = $this->session->userdata('userManagementLastSerial');
    //     }
        
    //     $lastSerial++;
    //     $page = $this->input->post('page');
    //     if ($page != '') {
    //         $this->session->set_userdata('userManagementLastPage', $page);
    //     } else {
    //         $this->session->set_userdata('userManagementLastPage', 1);
    //     }
    //     $this->session->set_userdata('userManagementLastSerial', $lastSerial);
    //     return $lastSerial;
    // }
}
