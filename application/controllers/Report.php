<?php 
require_once("Home.php");

class Report extends Home
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

    public function sms_report_userwise()
    {
        if ($this->session->userdata('user_type') != 'Admin') {
            redirect('home/login', 'location');
        }

        $data['body']="report/sms/user_wise";
        $data['page_title'] = 'Users\' SMS Report';
        $this->_viewcontroller($data);
    }
    
    
    public function sms_report_userwise_data()
    {
        if ($this->session->userdata('user_type') != 'Admin') {
            redirect('home/login', 'location');
        }

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'first_name';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $order_by_str=$sort." ".$order;
        
        $from_date        =  strip_tags(trim($this->input->post('schedule_from_date', true)));
        $from_date      = date('Y-m-d', strtotime($from_date));
        $to_date        =  strip_tags(trim($this->input->post('schedule_to_date', true)));
        $to_date        = date('Y-m-d', strtotime($to_date));
        $is_searched = $this->input->post('is_searched', true);

        $where=array();

    
        if ($is_searched) {
            $this->session->set_userdata('sms_report_userwise_from_date',  $from_date);
            $this->session->set_userdata('sms_report_userwise_to_date',    $to_date);
        }
        
        $search_from_date   = $this->session->userdata('sms_report_userwise_from_date');
        $search_to_date     = $this->session->userdata('sms_report_userwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }
        
        
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
                
                    
        $select=array("user_id","first_name","last_name","email","mobile","username","count(sms_history.id) as total_sms_sent");
        $join=array("users"=>"users.id=sms_history.user_id,left");
        $offset = ($page-1)*$rows;
        $result = array();
        $info=$this->basic->get_data('sms_history', $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='user_id', $num_rows=0);
        
        $total_rows_array=$this->basic->count_row($table="sms_history", $where, $count="sms_history.id", $join, $group_by='user_id');
        
        $total_result=$total_rows_array[0]['total_rows'];
        
        echo convert_to_grid_data($info, $total_result);
    }

    public function download_sms_report_userwise()
    {
        if ($this->session->userdata('user_type') != 'Admin') {
            redirect('home/login', 'location');
        }

        $where=array();

        $search_from_date   = $this->session->userdata('sms_report_userwise_from_date');
        $search_to_date     = $this->session->userdata('sms_report_userwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
                
                    
        $select=array("user_id","first_name","last_name","email","mobile","username","count(sms_history.id) as total_sms_sent");
        $join=array("users"=>"users.id=sms_history.user_id,left");
        $info=$this->basic->get_data('sms_history', $where, $select, $join, $limit='', $start='', $order_by='first_name ASC', $group_by='user_id', $num_rows=0);

        $fp = fopen("download/report/user_wise_sms_report/user_wise_sms_report.csv", "w");
        $head=array("First Name", "Last Name", "Email", "Mobile", "Username", "Total Sms Sent");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['first_name'] = $value['first_name'];
            $write_info['last_name'] = $value['last_name'];
            // $write_info['company'] = $value['company'];
            $write_info['email'] = $value['email'];
            $write_info['mobile'] = $value['mobile'];
            $write_info['username'] = $value['username'];
            $write_info['total_sms_sent'] = $value['total_sms_sent'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/user_wise_sms_report/user_wise_sms_report.csv";
        $data['file_name'] = $file_name;
        $this->load->view('page/download', $data);
    }
    
    public function sms_report_contactwise()
    {
        $data['body']="report/sms/contact_wise";
        $data['page_title'] = 'My SMS Report';
        $this->_viewcontroller($data);
    }
    
    
    public function sms_report_contactwise_data()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'first_name';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $order_by_str=$sort." ".$order;

        $from_date        =  strip_tags(trim($this->input->post('schedule_from_date', true)));
        $from_date      = date('Y-m-d', strtotime($from_date));
        $to_date        =  strip_tags(trim($this->input->post('schedule_to_date', true)));
        $to_date        = date('Y-m-d', strtotime($to_date));
        $is_searched = $this->input->post('is_searched', true);

        $where=array();

        if ($is_searched) {
            $this->session->set_userdata('sms_report_contactwise_from_date',  $from_date);
            $this->session->set_userdata('sms_report_contactwise_to_date',    $to_date);
        }
        
        $search_from_date   = $this->session->userdata('sms_report_contactwise_from_date');
        $search_to_date     = $this->session->userdata('sms_report_contactwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }
        
        $where_simple['sms_history.user_id']=$this->user_id;
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
        
        $select=array("first_name","last_name","email","to_number","count(sms_history.id) as total_sms_sent");
        $join=array("contacts"=>"contacts.phone_number=sms_history.to_number,left");
        
        $offset = ($page-1)*$rows;
        $result = array();
        $info=$this->basic->get_data('sms_history', $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='to_number', $num_rows=0);
        
        $total_rows_array=$this->basic->count_row($table="sms_history", $where, $count="sms_history.id", $join, $group_by='to_number');
        
        $total_result=$total_rows_array[0]['total_rows'];
        
        echo convert_to_grid_data($info, $total_result);
    }


    public function download_sms_report_contactwise()
    {
        $where=array();
        $search_from_date   = $this->session->userdata('sms_report_contactwise_from_date');
        $search_to_date     = $this->session->userdata('sms_report_contactwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }
        
        $where_simple['sms_history.user_id']=$this->user_id;
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
        
        $select=array("first_name","last_name","email","to_number","count(sms_history.id) as total_sms_sent");
        $join=array("contacts"=>"contacts.phone_number=sms_history.to_number,left");
        $info=$this->basic->get_data('sms_history', $where, $select, $join, $limit='', $start='', $order_by='first_name ASC', $group_by='to_number', $num_rows=0);

        $fp = fopen("download/report/contact_wise_sms_report/contact_wise_sms_report.csv", "w");
        $head=array("First Name", "Last Name", "Company Name", "Email", "Mobile", "Total Sms Sent");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['first_name'] = $value['first_name'];
            $write_info['last_name'] = $value['last_name'];
            $write_info['email'] = $value['email'];
            $write_info['to_number'] = $value['to_number'];
            $write_info['total_sms_sent'] = $value['total_sms_sent'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/contact_wise_sms_report/contact_wise_sms_report.csv";
        $data['file_name'] = $file_name;
        $this->load->view('page/download', $data);
    }
    
    
    public function email_report_userwise()
    {
        if ($this->session->userdata('user_type') != 'Admin') {
            redirect('home/login', 'location');
        }

        $data['body']="report/email/user_wise";
        $data['page_title'] = 'Users\' Email Report';
        $this->_viewcontroller($data);
    }
    
    public function email_report_userwise_data()
    {
        if ($this->session->userdata('user_type') != 'Admin') {
            redirect('home/login', 'location');
        }

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'first_name';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $order_by_str=$sort." ".$order;
        
        $from_date        =  strip_tags(trim($this->input->post('schedule_from_date', true)));
        $from_date      = date('Y-m-d', strtotime($from_date));
        $to_date        =  strip_tags(trim($this->input->post('schedule_to_date', true)));
        $to_date        = date('Y-m-d', strtotime($to_date));
        $is_searched = $this->input->post('is_searched', true);

        $where=array();

        if ($is_searched) {
            $this->session->set_userdata('email_report_userwise_from_date',  $from_date);
            $this->session->set_userdata('email_report_userwise_to_date',    $to_date);
        }
        
        $search_from_date   = $this->session->userdata('email_report_userwise_from_date');
        $search_to_date     = $this->session->userdata('email_report_userwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
                        
        $select=array("user_id","first_name","last_name", "email","mobile","username","count(email_history.id) as total_email_sent");
        $join=array("users"=>"users.id=email_history.user_id,left");
        $offset = ($page-1)*$rows;
        $result = array();
        $info=$this->basic->get_data('email_history', $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='email', $num_rows=0);
        $total_rows_array=$this->basic->count_row($table="email_history", $where, $count="email_history.id", $join, $group_by='email');
        $total_result=$total_rows_array[0]['total_rows'];
        echo convert_to_grid_data($info, $total_result);
    }


    public function download_email_report_userwise()
    {
        if ($this->session->userdata('user_type') != 'Admin') {
            redirect('home/login', 'location');
        }

        $where=array();
        $search_from_date   = $this->session->userdata('email_report_userwise_from_date');
        $search_to_date     = $this->session->userdata('email_report_userwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
                        
        $select=array("user_id","first_name","last_name","email","mobile","username","count(email_history.id) as total_email_sent");
        $join=array("users"=>"users.id=email_history.user_id,left");
        $info=$this->basic->get_data('email_history', $where, $select, $join, $limit='', $start='', $order_by='first_name ASC', $group_by='email', $num_rows=0);

        $fp = fopen("download/report/user_wise_email_report/user_wise_email_report.csv", "w");
        $head=array("First Name", "Last Name", "Email", "Mobile", "Username", "Total Email Sent");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['first_name'] = $value['first_name'];
            $write_info['last_name'] = $value['last_name'];
            // $write_info['company'] = $value['company'];
            $write_info['email'] = $value['email'];
            $write_info['mobile'] = $value['mobile'];
            $write_info['username'] = $value['username'];
            $write_info['total_email_sent'] = $value['total_email_sent'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/user_wise_email_report/user_wise_email_report.csv";
        $data['file_name'] = $file_name;
        $this->load->view('page/download', $data);
    }
    
    
    public function email_report_contactwise()
    {
        $data['body']="report/email/contact_wise";
        $data['page_title'] = 'My Email Report';
        $this->_viewcontroller($data);
    }
    
    
    public function email_report_contactwise_data()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'first_name';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $order_by_str=$sort." ".$order;
        
        $from_date        =  strip_tags(trim($this->input->post('schedule_from_date', true)));
        $from_date      = date('Y-m-d', strtotime($from_date));
        $to_date        =  strip_tags(trim($this->input->post('schedule_to_date', true)));
        $to_date        = date('Y-m-d', strtotime($to_date));
        $is_searched = $this->input->post('is_searched', true);

        $where=array();

        if ($is_searched) {
            $this->session->set_userdata('email_report_contactwise_from_date',  $from_date);
            $this->session->set_userdata('email_report_contactwise_to_date',    $to_date);
        }
        
        $search_from_date   = $this->session->userdata('email_report_contactwise_from_date');
        $search_to_date     = $this->session->userdata('email_report_contactwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }

        $where_simple['email_history.user_id']=$this->user_id;
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
                
                    
        $select=array("first_name","last_name","email","to_email","count(email_history.id) as total_email_sent");
        $join=array("contacts"=>"contacts.phone_number=email_history.to_email,left");
        
        $offset = ($page-1)*$rows;
        $result = array();
        $info=$this->basic->get_data('email_history', $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='to_email', $num_rows=0);
        
        $total_rows_array=$this->basic->count_row($table="email_history", $where, $count="email_history.id", $join, $group_by='to_email');
        
        $total_result=$total_rows_array[0]['total_rows'];
        
        echo convert_to_grid_data($info, $total_result);
    }


    public function download_email_report_contactwise()
    {
        $where=array();
        $search_from_date   = $this->session->userdata('email_report_contactwise_from_date');
        $search_to_date     = $this->session->userdata('email_report_contactwise_to_date');

        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') <= "] =    $search_to_date;
            }
        }
        
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["date_format(sent_time,'%Y-%m-%d') >= "] =    $search_from_date;
            }
        }

        $where_simple['email_history.user_id']=$this->user_id;
        
        if (isset($where_simple)) {
            $where=array('where'=>$where_simple);
        }
                
                    
        $select=array("first_name","last_name","email","to_email","count(email_history.id) as total_email_sent");
        $join=array("contacts"=>"contacts.phone_number=email_history.to_email,left");
        $info=$this->basic->get_data('email_history', $where, $select, $join, $limit='', $start='', $order_by='first_name ASC', $group_by='to_email', $num_rows=0);

        $fp = fopen("download/report/contact_wise_email_report/contact_wise_email_report.csv", "w");
        $head=array("First Name", "Last Name", "To Email", "Total Email Sent");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['first_name'] = $value['first_name'];
            $write_info['last_name'] = $value['last_name'];
            // $write_info['email'] = $value['email'];
            $write_info['to_email'] = $value['to_email'];
            $write_info['total_email_sent'] = $value['total_email_sent'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/contact_wise_email_report/contact_wise_email_report.csv";
        $data['file_name'] = $file_name;
        $this->load->view('page/download', $data);
    }
}
