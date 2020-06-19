<?php
class Update extends CI_Controller
{
      
    public function __construct()
    {
        parent::__construct();   
        $this->load->database();
        $this->load->model('basic');
        set_time_limit(0);
    }

    public function index()
    {
        $this->v3_1to3_2();
    }


    public function v3_1to3_2()
    {
        $lines='ALTER TABLE  `sms_api_config` CHANGE  `gateway_name`  `gateway_name` ENUM(  "planet",  "plivo",  "twilio",  "clickatell",  "nexmo",  "msg91.com",  "textlocal.in",  "sms4connect.com",  "telnor.com",  "mvaayoo.com",  "routesms.com",  "trio-mobile.com", "sms40.com",  "africastalking.com",  "infobip.com",  "smsgatewayme",  "semysms.net" ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

            ALTER TABLE  `schedule_email` CHANGE  `is_sent`  `is_sent` ENUM(  "0",  "1",  "2" ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  "0" COMMENT  "pending,complete,processing";

            ALTER TABLE  `schedule_sms` CHANGE  `is_sent`  `is_sent` ENUM(  "0",  "1",  "2" ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  "0" COMMENT  "pending,complete,processing";

            ALTER TABLE  `birthday_reminder_email` CHANGE  `status`  `status` ENUM(  "0",  "1",  "2" ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  "0" COMMENT  "pending,complete,processing";

            ALTER TABLE  `birthday_reminder` CHANGE  `status`  `status` ENUM(  "0",  "1",  "2" ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  "0" COMMENT  "pending,complete,processing"';


       
        // Loop through each line

        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
        echo "SSEM has been updated to v3.2 successfully.".$count." queries executed.";
    }



}
