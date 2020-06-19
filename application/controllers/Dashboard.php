<?php
require_once("Home.php");

class Dashboard extends Home
{
    public $user_id;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login', 'location');
        }

        // $this->important_feature();
        $this->periodic_check();

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
        if ($this->session->userdata('user_type') == "Admin") {
            $this->dashboard();
        } else {
            $this->my_dashboard();
        }
    }

    public function dashboard()
    {
        if ($this->session->userdata('user_type')!= "Admin") {
            redirect('dashboard/my_dashboard', 'location');
        }

        $where['where'] = array();
        if ($total = $this->basic->get_data('view_email_sent_history_user_wise_total', $where='', $select=array('sum(total_email_sent) as total_email_sent'))) {
            $data['total_email_sent'] = $total[0]['total_email_sent'];
        } else {
            $data['total_email_sent'] = 0;
        }

        if ($total = $this->basic->get_data('view_sms_sent_history_user_wise_total', $where='', $select=array('sum(total_sms_sent) as total_sms_sent'))) {
            $data['total_sms_sent'] = $total[0]['total_sms_sent'];
        } else {
            $data['total_sms_sent'] = 0;
        }
        $today = date("Y-m-d");
        $where_today_email['where'] = array("date_format(sent_time,'%Y-%m-%d') =" => $today);
        if ($total = $this->basic->get_data('email_history', $where_today_email, $select=array('count(id) as total_email_sent'))) {
            $data['today_total_email_sent'] = $total[0]['total_email_sent'];
        } else {
            $data['today_total_email_sent'] = 0;
        }

        if ($total = $this->basic->get_data('sms_history', $where_today_email, $select=array('count(id) as total_sms_sent'))) {
            $data['today_total_sms_sent'] = $total[0]['total_sms_sent'];
        } else {
            $data['today_total_sms_sent'] = 0;
        }


        if ($total = $this->basic->get_data('view_sms_sent_history_user_wise_this_month', $where='', $select=array('sum(total_sms_sent) as total_sms_sent'))) {
            $data['total_sent_sms_this_month'] = $total[0]['total_sms_sent'];
        } else {
            $data['total_sent_sms_this_month'] = 0;
        }

        if ($total = $this->basic->get_data('view_email_sent_history_user_wise_this_month', $where='', $select=array('sum(total_email_sent) as total_email_sent'))) {
            $data['total_sent_email_this_month'] = $total[0]['total_email_sent'];
        } else {
            $data['total_sent_email_this_month'] = 0;
        }

        $year = date('Y')-1;
        $where_yearly['where'] = array('years >=' => $year);
        if ($email_12_months = $this->basic->get_data('view_month_user_email_history', $where_yearly, $select=array('month_number', 'years', 'sum(total_email_sent) as total_email_sent'), $join='', $limit='', $start='', $order_by='', $group_by='years,month_number')) {
            foreach ($email_12_months as $total_email) {
                $yearly_email[$total_email['month_number']][$total_email['years']] = $total_email['total_email_sent'];
            }
        }

        // echo $this->db->last_query();


        if ($sms_12_months = $this->basic->get_data('view_month_user_sms_history', $where_yearly, $select=array('month_number', 'years', 'sum(total_sms_sent) as total_sms_sent'), $join='', $limit='', $start='', $order_by='', $group_by='years,month_number')) {
            foreach ($sms_12_months as $total_sms) {
                $yearly_sms[$total_sms['month_number']][$total_sms['years']] = $total_sms['total_sms_sent'];
            }
        }

        $chart_array=array();
        $cur_year=date('Y');
        $cur_month=date('m');
        $cur_month=(int)$cur_month;
        $months_name = array(1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec');

        for ($i=0;$i<=11;$i++) {
            $m_for_chart=$months_name[$cur_month];
            $m = $cur_month;
            $chart_array[$i]['year']=$m_for_chart."-".$cur_year;

            if (isset($yearly_email[$m][$cur_year])) {
                $chart_array[$i]['sent_email']=$yearly_email[$m][$cur_year];
            } else {
                $chart_array[$i]['sent_email']=0;
            }
            if (isset($yearly_sms[$m][$cur_year])) {
                $chart_array[$i]['sent_sms']=$yearly_sms[$m][$cur_year];
            } else {
                $chart_array[$i]['sent_sms']=0;
            }

            $cur_month=$cur_month-1;

            if ($cur_month==0) {
                $cur_month=12;
                $cur_year=$cur_year-1;
            }
        }

        $chart_array=array_reverse($chart_array);

        $color=array("#3F0082","#FE9601","#CC0063","#86269B","#F7F960","#FF534B","#BCCF3D","#BCCF3D","#82683B","#B6A754","#D79C8C");
        $join = array('sms_api_config'=>'sms_api_config.id=sms_history.gateway_id,left');
        $group_by = 'sms_api_config.gateway_name';
        $select = "count(sms_history.id) as total_sms_sent, gateway_name";
        $sms_sent_history = $this->basic->get_data('sms_history',$where="",$select,$join,$limit='',$start='',$order_by='',$group_by);
        $i = 0;
        $j = 0;
        $result = array();
        $gateway_name = array();
        foreach ($sms_sent_history as $value) {
            $result[$i]['value'] = (int)$value['total_sms_sent'];
            $result[$i]['color'] = $color[$j];
            $result[$i]['highlight'] = $color[$j];
            $result[$i]['label'] = "SMS From ".$value['gateway_name'];

            $gateway_name[$i]['name'] = $value['gateway_name'];
            $gateway_name[$i]['color'] = $color[$j];

            $i++;
            $j++;
            if($j == 10) $j=0;
        }


        $where = array();
        $group_by = 'configure_table_name';
        $select = "count(email_history.id) as total_email_sent, configure_table_name";
        $sms_sent_history = $this->basic->get_data('email_history',$where="",$select,$join='',$limit='',$start='',$order_by='',$group_by);
        $i = 0;
        $j = 0;
        $email_result = array();
        $email_gateway_names = array();
        foreach ($sms_sent_history as $value) {
            $email_result[$i]['value'] = (int)$value['total_email_sent'];
            $email_result[$i]['color'] = $color[$j];
            $email_result[$i]['highlight'] = $color[$j];
            $email_gateway = array();
            $email_gateway = explode('_', $value['configure_table_name']);
            if($email_gateway[1] == 'config') $email_gateway_name = "SMTP";
            else $email_gateway_name = $email_gateway[1];

            $email_result[$i]['label'] = "Email From ".$email_gateway_name;

            $email_gateway_names[$i]['name'] = $email_gateway_name;
            $email_gateway_names[$i]['color'] = $color[$j];

            $i++;
            $j++;
            if($j == 10) $j=0;
        }


        $data['piechart_sms'] = json_encode($result); 
        $data['piechart_email'] = json_encode($email_result); 
        $data['gateway_name'] = $gateway_name;
        $data['email_gateway_name'] = $email_gateway_names;
        $data['chart_bar'] = $chart_array;
        $data['body'] = 'admin/dashboard/overall_dashboard';
        $data['page_title'] = 'Admin Dashboard';
        $this->_viewcontroller($data);
    }


    public function my_dashboard()
    {
        $today = date("Y-m-d");
        $where_today_email['where'] = array("date_format(sent_time,'%Y-%m-%d') =" => $today,'user_id'=>$this->session->userdata('user_id'));

        $where['where'] = array('user_id'=>$this->session->userdata('user_id'));
        if ($total = $this->basic->get_data('view_email_sent_history_user_wise_total', $where, $select=array('total_email_sent'))) {
            $data['total_email_sent'] = $total[0]['total_email_sent'];
        } else {
            $data['total_email_sent'] = 0;
        }

        if ($total = $this->basic->get_data('view_sms_sent_history_user_wise_total', $where, $select=array('total_sms_sent'))) {
            $data['total_sms_sent'] = $total[0]['total_sms_sent'];
        } else {
            $data['total_sms_sent'] = 0;
        }


        if ($total = $this->basic->get_data('email_history', $where_today_email, $select=array('count(id) as total_email_sent'))) {
            $data['today_total_email_sent'] = $total[0]['total_email_sent'];
        } else {
            $data['today_total_email_sent'] = 0;
        }

        if ($total = $this->basic->get_data('sms_history', $where_today_email, $select=array('count(id) as total_sms_sent'))) {
            $data['today_total_sms_sent'] = $total[0]['total_sms_sent'];
        } else {
            $data['today_total_sms_sent'] = 0;
        }


        if ($total = $this->basic->get_data('view_sms_sent_history_user_wise_this_month', $where, $select=array('total_sms_sent'))) {
            $data['total_sent_sms_this_month'] = $total[0]['total_sms_sent'];
        } else {
            $data['total_sent_sms_this_month'] = 0;
        }

        if ($total = $this->basic->get_data('view_email_sent_history_user_wise_this_month', $where, $select=array('total_email_sent'))) {
            $data['total_sent_email_this_month'] = $total[0]['total_email_sent'];
        } else {
            $data['total_sent_email_this_month'] = 0;
        }

        $year = date('Y')-1;
        $month = date('m');
        $where_bar['where'] = array('user_id'=>$this->session->userdata('user_id'),'years >='=>$year);

        if ($email_12_months = $this->basic->get_data('view_month_user_email_history', $where_bar, $select=array('month_number', 'years', 'total_email_sent'), $join='')) {
            foreach ($email_12_months as $total_email) {
                $yearly_email[$total_email['month_number']][$total_email['years']] = $total_email['total_email_sent'];
            }
        }


        if ($sms_12_months = $this->basic->get_data('view_month_user_sms_history', $where_bar, $select=array('month_number', 'years', 'total_sms_sent'), $join='')) {
            foreach ($sms_12_months as $total_sms) {
                $yearly_sms[$total_sms['month_number']][$total_sms['years']] = $total_sms['total_sms_sent'];
            }
        }

        $chart_array=array();
        $cur_year=date('Y');
        $cur_month=date('m');
        $cur_month=(int)$cur_month;
        $months_name = array(1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec');

        for ($i=0;$i<=11;$i++) {
            $m_for_chart=$months_name[$cur_month];
            $m = $cur_month;
            $chart_array[$i]['year']=$m_for_chart."-".$cur_year;

            if (isset($yearly_email[$m][$cur_year])) {
                $chart_array[$i]['sent_email']=$yearly_email[$m][$cur_year];
            } else {
                $chart_array[$i]['sent_email']=0;
            }
            if (isset($yearly_sms[$m][$cur_year])) {
                $chart_array[$i]['sent_sms']=$yearly_sms[$m][$cur_year];
            } else {
                $chart_array[$i]['sent_sms']=0;
            }

            $cur_month=$cur_month-1;

            if ($cur_month==0) {
                $cur_month=12;
                $cur_year=$cur_year-1;
            }
        }

        $chart_array=array_reverse($chart_array);


        $color=array("#3F0082","#FE9601","#CC0063","#86269B","#F7F960","#FF534B","#BCCF3D","#BCCF3D","#82683B","#B6A754","#D79C8C");
        $where = array();
        $where['where'] = array('sms_history.user_id'=>$this->session->userdata('user_id'));
        $join = array('sms_api_config'=>'sms_api_config.id=sms_history.gateway_id,left');
        $group_by = 'sms_api_config.gateway_name';
        $select = "count(sms_history.id) as total_sms_sent, gateway_name";
        $sms_sent_history = $this->basic->get_data('sms_history',$where,$select,$join,$limit='',$start='',$order_by='',$group_by);
        $i = 0;
        $j = 0;
        $result = array();
        $gateway_name = array();
        foreach ($sms_sent_history as $value) {
            $result[$i]['value'] = (int)$value['total_sms_sent'];
            $result[$i]['color'] = $color[$j];
            $result[$i]['highlight'] = $color[$j];
            $result[$i]['label'] = "SMS From ".$value['gateway_name'];

            $gateway_name[$i]['name'] = $value['gateway_name'];
            $gateway_name[$i]['color'] = $color[$j];

            $i++;
            $j++;
            if($j == 10) $j=0;
        }


        $where = array();
        $where['where'] = array('email_history.user_id'=>$this->session->userdata('user_id'));
        $group_by = 'configure_table_name';
        $select = "count(email_history.id) as total_email_sent, configure_table_name";
        $sms_sent_history = $this->basic->get_data('email_history',$where,$select,$join='',$limit='',$start='',$order_by='',$group_by);
        $i = 0;
        $j = 0;
        $email_result = array();
        $email_gateway_names = array();
        foreach ($sms_sent_history as $value) {
            $email_result[$i]['value'] = (int)$value['total_email_sent'];
            $email_result[$i]['color'] = $color[$j];
            $email_result[$i]['highlight'] = $color[$j];
            $email_gateway = array();
            $email_gateway = explode('_', $value['configure_table_name']);
            if($email_gateway[1] == 'config') $email_gateway_name = "SMTP";
            else $email_gateway_name = $email_gateway[1];

            $email_result[$i]['label'] = "Email From ".$email_gateway_name;

            $email_gateway_names[$i]['name'] = $email_gateway_name;
            $email_gateway_names[$i]['color'] = $color[$j];

            $i++;
            $j++;
            if($j == 10) $j=0;
        }


        $data['piechart_sms'] = json_encode($result); 
        $data['piechart_email'] = json_encode($email_result); 
        $data['gateway_name'] = $gateway_name;
        $data['email_gateway_name'] = $email_gateway_names;
        $data['chart_bar'] = $chart_array;
        $data['body'] = 'admin/dashboard/dashboard';
        $data['page_title'] = 'My Dashboard';
        $this->_viewcontroller($data);
    }
}
