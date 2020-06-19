<?php 
require_once("Home.php");

class Change_password extends Home
{

    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login', 'location');
        }
    }


    public function index()
    {
        $this->reset_password_form();
    }


    public function reset_password_form()
    {
        $data['page_title'] = 'Change Password';
        $data['body'] = 'admin/theme/password_reset_form';
        $this->_viewcontroller($data);
    }

    public function reset_password_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('defaults/access_forbidden', 'location');
        }

        $this->form_validation->set_rules('old_password', '<b>'.$this->lang->line('Old Password').'</b>', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_password', '<b>'.$this->lang->line('New Password').'</b>', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_new_password', '<b>'.$this->lang->line('Confirm New Password').'</b>', 'trim|required|xss_clean|matches[new_password]');
        if ($this->form_validation->run() == false) {
            $this->reset_password_form();
        } else {
            $user_id = $this->session->userdata('user_id');
            $password = strip_tags($this->input->post('old_password', true));
            $new_password = strip_tags($this->input->post('new_password', true));
            $table = 'users';
            $where['where'] = array(
                'id' => $user_id,
                'password' => md5($password)
                );
            $select = array('username');
            if ($this->basic->get_data($table, $where, $select)) {
                $where = array(
                    'id' => $user_id,
                    'password' => md5($password)
                    );
                $data = array('password' => md5($new_password));
                $this->basic->update_data($table, $where, $data);
                $this->session->set_userdata('logged_in', 0);
                $this->session->set_flashdata('reset_success', $this->lang->line("please login with new password"));
                redirect('home/login', 'location');
                // echo $this->session->userdata('reset_success');exit();
            } else {
                $this->session->set_userdata('error', $this->lang->line("the old password you have given is wrong"));
                $this->reset_password_form();
            }
        }
    }
}
