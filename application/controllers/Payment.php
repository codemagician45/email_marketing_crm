<?php require_once("Home.php"); // including home controller

class Payment extends Home
{

    public $user_id;
    public $download_id;
    
    /**
    * load constructor
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('home/login', 'location');
        }
        $this->user_id=$this->session->userdata('user_id');
		$this->load->library('paypal_class');
        set_time_limit(0);

        $this->important_feature();
        $this->periodic_check();
    }

    public function payment_setting_admin()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('dashboard/index', 'location');
        }
        
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_theme('flexigrid');
		$crud->set_table('payment_config');
		$crud->order_by('id');
		$crud->where('deleted', '0');
		$crud->set_subject($this->lang->line('Payment Settings'));
		$crud->required_fields('paypal_email','monthly_fee','currency');
		$crud->columns('paypal_email','monthly_fee', 'currency');		
		$crud->fields('paypal_email','monthly_fee', 'currency');
		$crud->display_as('paypal_email',$this->lang->line('Paypal Email'));
        $crud->display_as('monthly_fee',$this->lang->line('Monthly Fee'));
		$crud->display_as('currency',$this->lang->line('Currency'));
		// $crud->callback_field('ordering',array($this,'class_ordering_field_crud'));
		
		$crud->unset_add();
		// $crud->unset_edit();	

		$crud->unset_delete();
		$crud->unset_read();
		$crud->unset_print();
		$crud->unset_export();
	
		$output = $crud->render();
		$data['output']=$output;
		$data['crud']=1;
		$this->_viewcontroller($data);
    }

    public function payment_dashboard_admin()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('dashboard/index', 'location');
        }

    	// $total_where_users['where'] = array('deleted'=>'0');
    	$total_user = $this->basic->get_data('users',$total_where_users='',$select=array('count(id) as total_user'));
    	$data['total_user'] = $total_user[0]['total_user'];

    	$days = date('t');
    	$first_date = date("Y-m-01");
    	$last_date = date("Y-m-{$days}");
    	// $this_month_simple_where["deleted"] = '0';
    	$this_month_simple_where["date_format(add_date,'%Y-%m-%d') >="] = $first_date;
    	$this_month_simple_where["date_format(add_date,'%Y-%m-%d') <="] = $last_date;
    	$this_month_where = array('where'=>$this_month_simple_where);
    	$this_month_user = $this->basic->get_data('users',$this_month_where,$select=array('count(id) as total_user'));
    	if(!empty($this_month_user))
    		$data['this_month_total_user'] = $this_month_user[0]['total_user'];
    	else
    		$data['this_month_total_user'] = 0;

    	$total_paid_amount = $this->basic->get_data('transaction_history',$where='',$select=array('sum(paid_amount) as total_paid_amount'));
    	if(!empty($total_paid_amount))
    		$data['total_paid_amount'] = $total_paid_amount[0]['total_paid_amount'];
    	else
    		$data['total_paid_amount'] = 0;

    	$this_month_paid_simple_where["date_format(payment_date,'%Y-%m-%d') >="] = $first_date;
    	$this_month_paid_simple_where["date_format(payment_date,'%Y-%m-%d') <="] = $last_date;
    	$this_month_paid_where = array('where' => $this_month_paid_simple_where);

    	$this_month_paid_amount = $this->basic->get_data('transaction_history',$this_month_paid_where,$select=array('sum(paid_amount) as total_paid_amount'));
    	if(!empty($this_month_paid_amount))
    		$data['this_month_paid_amount'] = $this_month_paid_amount[0]['total_paid_amount'];
    	else 
    		$data['this_month_paid_amount'] = 0;

        $where_today_user['where'] = array("date_format(add_date,'%Y-%m-%d') =" => date('Y-m-d'));
        $today_user = $this->basic->get_data('users',$where_today_user,$select=array('count(id) as total_user'));
        if(!empty($today_user))
            $data['today_user'] = $today_user[0]['total_user'];
        else
            $data['today_user'] = 0;

        $today_paid_simple_where["date_format(payment_date,'%Y-%m-%d') ="] = date("Y-m-d");
        $today_paid_where = array('where' => $today_paid_simple_where);

        $today_paid_amount = $this->basic->get_data('transaction_history',$today_paid_where,$today_select=array('sum(paid_amount) as total_paid_amount'));

        if(!empty($today_paid_amount))
            $data['today_paid_amount'] = $today_paid_amount[0]['total_paid_amount'];
        else 
            $data['today_paid_amount'] = 0;

    	$data['body'] = 'admin/payment_dashboard';
    	$data['page_title'] = 'Payment Dashboard';
    	$this->_viewcontroller($data);
    }

    public function admin_payment_history()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('dashboard/index', 'location');
        }

    	$data['body'] = 'admin/admin_payment_history';
    	$data['page_title'] = 'Payment History';
        
        $table = "transaction_history";
        $info = $this->basic->get_data($table, $where='', $select = '');        
        $total_paid_amount = 0;
        foreach ($info as $payment_info) {
            $total_paid_amount = $total_paid_amount + $payment_info['paid_amount'];
        }
        $data['total_paid_amount'] = $total_paid_amount;

    	$this->_viewcontroller($data);
    }

    public function admin_payment_history_data()
    {
    	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        // setting variables for pagination
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str = $sort." ".$order;

        // setting properties for search
        $first_name = trim($this->input->post('first_name', true));
        $last_name = trim($this->input->post('last_name', true));
        $from_date = trim($this->input->post('from_date', true));
        if($from_date != '')
        	$from_date = date('Y-m-d', strtotime($from_date));
        $to_date = trim($this->input->post('to_date', true));
        if($to_date != '')
        	$to_date = date('Y-m-d', strtotime($to_date));

        $is_searched= $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('admin_payment_history_first_name', $first_name);
            $this->session->set_userdata('admin_payment_history_last_name', $last_name);
            $this->session->set_userdata('admin_payment_history_from_date', $from_date);
            $this->session->set_userdata('admin_payment_history_to_date', $to_date);
        }
        // saving session data to different search parameter variables
        $search_first_name = $this->session->userdata('admin_payment_history_first_name');
        $search_last_name = $this->session->userdata('admin_payment_history_last_name');
        $search_from_date = $this->session->userdata('admin_payment_history_from_date');
        $search_to_date = $this->session->userdata('admin_payment_history_to_date');

        // creating a blank where_simple array
        $where_simple = array();

        // trimming data
        if ($search_first_name) {
            $where_simple['first_name like'] = $search_first_name."%";
        }

        if ($search_last_name) {
            $where_simple['last_name like'] = $search_last_name."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') >="] = $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') <="] = $search_to_date;
            }
        }

        $where = array('where' => $where_simple);
        $offset = ($page-1)*$rows;
        $result = array();

        $table = "transaction_history";
        $select = array("transaction_history.*","users.username","users.mobile","users.email");
        $join=array('users'=>"users.id=transaction_history.user_id,left");
        $info = $this->basic->get_data($table, $where, $select , $join, $limit = $rows, $start = $offset, $order_by = $order_by_str);
        
        // $total_paid_amount = 0;
        // foreach ($info as $payment_info) {
        //     $total_paid_amount = $total_paid_amount + $payment_info['paid_amount'];
        // }

        // $this->session->set_userdata('total_paid_amount',$total_paid_amount);

        $total_rows_array = $this->basic->count_row($table, $where, $count = "id");
        $total_result = $total_rows_array[0]['total_rows'];
        echo convert_to_grid_data($info, $total_result);
    }

    

    public function member_payment_history()
    {
    	$data['body'] = 'member/member_payment_history';
    	$data['page_title'] = 'Member Payment History';

    	
    	$cancel_url=base_url()."payment/member_payment_history";
		$success_url=base_url()."payment/member_payment_history";

		$where['where'] = array('deleted'=>'0');
		$payment_config = $this->basic->get_data('payment_config',$where,$select='');
		if(!empty($payment_config)) {
			$payment_amount = $payment_config[0]['monthly_fee'];
			$paypal_email = $payment_config[0]['paypal_email'];
            $currency = $payment_config[0]['currency'];
		} else {
			$payment_amount = "";
			$paypal_email = "";
		}

		$this->paypal_class->mode="live";
		$this->paypal_class->cancel_url=$cancel_url;
		$this->paypal_class->success_url=$success_url;
		$this->paypal_class->notify_url=site_url()."paypal_ipn/ipn_notify";
        $this->paypal_class->amount=$payment_amount;
		$this->paypal_class->currency=$currency; //currency 
		$this->paypal_class->user_id=$this->user_id;
		$this->paypal_class->business_email=$paypal_email;
		$button = $this->paypal_class->set_button();	

		$data['button']= $button;

    	$this->_viewcontroller($data);
    }

    public function member_payment_history_data()
    {
    	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $user_id = $this->session->userdata('user_id');
        // setting variables for pagination
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str = $sort." ".$order;

        // setting properties for search
        $first_name = trim($this->input->post('first_name', true));
        $last_name = trim($this->input->post('last_name', true));
        $from_date = trim($this->input->post('from_date', true));
        if($from_date != '')
        	$from_date = date('Y-m-d', strtotime($from_date));
        $to_date = trim($this->input->post('to_date', true));
        if($to_date != '')
        	$to_date = date('Y-m-d', strtotime($to_date));

        $is_searched= $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('member_payment_history_first_name', $first_name);
            $this->session->set_userdata('member_payment_history_last_name', $last_name);
            $this->session->set_userdata('member_payment_history_from_date', $from_date);
            $this->session->set_userdata('member_payment_history_to_date', $to_date);
        }
        // saving session data to different search parameter variables
        $search_first_name = $this->session->userdata('member_payment_history_first_name');
        $search_last_name = $this->session->userdata('member_payment_history_last_name');
        $search_from_date = $this->session->userdata('member_payment_history_from_date');
        $search_to_date = $this->session->userdata('member_payment_history_to_date');

        // creating a blank where_simple array
        $where_simple = array();

        // trimming data
        if ($search_first_name) {
            $where_simple['first_name like'] = $search_first_name."%";
        }

        if ($search_last_name) {
            $where_simple['last_name like'] = $search_last_name."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') >="] = $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') <="] = $search_to_date;
            }
        }

        $where_simple['user_id'] = $user_id;

        $where = array('where' => $where_simple);
        $offset = ($page-1)*$rows;
        $result = array();

        $table = "transaction_history";
        $info = $this->basic->get_data($table, $where, $select = '', $join='', $limit = $rows, $start = $offset, $order_by = $order_by_str);

        $total_rows_array = $this->basic->count_row($table, $where, $count = "id");
        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }
	
	
	

	
	
	public function payment_success_page(){
		
	}
	
    
	
}