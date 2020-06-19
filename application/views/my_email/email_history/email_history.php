<?php $this->load->view('admin/theme/message'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><i class="fa fa-history"></i> <?php echo $this->lang->line('My Email History'); ?>  </h1>  
</section>

<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width: 100%; height:659px !important;">
            <table 
            id="sms_history_table"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."my_email/my_email_history_data"; ?>" 
            pagination="true" 
            rownumbers="true" 
            toolbar="#tb" 
            pageSize="10" 
            pageList="[5,10,20,50,100]"  
            fit= "true" 
            fitColumns= "true" 
            nowrap= "true" 
            view= "detailview"
            idField="id"
            >
            
                <thead>
                    <tr>      
                       <th field="to_email" sortable="true" ><?php echo $this->lang->line('Sent To'); ?></th>
                       <th field="subject" sortable="true" ><?php echo $this->lang->line('Subject'); ?></th>                       
                       <th field="attachment" sortable="true" formatter="attachment_download"><?php echo $this->lang->line('Attachment'); ?></th>
                        <th field="sent_time" sortable="true" ><?php echo $this->lang->line('Sent At'); ?></th>
                        <th field="send_status" sortable="true"><?php echo $this->lang->line('Delivery Status'); ?></th>
                        <th field="view"  formatter='action_column'><?php echo $this->lang->line('Actions'); ?></th>
                    </tr>
                </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">  
            <form class="form-inline" style="margin-top:20px">
                <div class="form-group">
                    <input  id="schedule_from_date" name="schedule_from_date" class="form-control" size="20" placeholder="<?php echo $this->lang->line('From Date');?>">
                </div> 
				<div class="form-group">
                     <input  id="schedule_to_date" name="schedule_to_date" class="form-control" size="20" placeholder="<?php echo $this->lang->line('To Date');?>">
                </div>
                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line('Search')?></button>
            </form>         
        </div>
    </div>
  </div>   
</section>



<!--  Modal for contacts show of the tution -->

<div id="modal_email_history_detail" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
		
            <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						 <span aria-hidden="true">&times;</span>
				 </button>
                <h4 id="email_history_details_title" class="modal-title"><?php echo $this->lang->line('Email History Details'); ?></h4>
            </div>
			
            <div id="email_history_view_body" class="modal-body">
			
				 <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-user"></i> <?php echo $this->lang->line('Send As'); ?></h4> <br/>
                    <p id="message_send_as"></p> 
                </div>

                <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-send"></i> <?php echo $this->lang->line('Send To'); ?></h4> <br/>
                    <p id="to_email"></p> 
                </div>

                <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-clock-o"></i> <?php echo $this->lang->line('Send At'); ?></h4> <br/>
                    <p id="sent_time"></p> 
                </div>

                <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('Attachment'); ?></h4> <br/>
                    <p id="message_attachment"></p> 
                </div>

                 <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-check-square"></i> <?php echo $this->lang->line('Subject'); ?></h4> <br/>
                    <p id="message_subject"></p> 
                </div>

                <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-envelope"></i> <?php echo $this->lang->line('Message'); ?> </h4> <br/>
                    <p id="message"></p> 
                </div> 
               
					
			</div>
			
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	
	var base_url="<?php echo site_url(); ?>";
	
	function action_column(value,row,index){
	
	   
		var email_history_info=JSON.stringify(row);
			email_history_info=HTMLEncode(email_history_info);
		  str="<a title='View Contacts' style='cursor:pointer' onclick='view_details(event,"+email_history_info+")' >" +' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/magnifier.png");?>" alt="View">'+"</a>";
   		return str;
	}
	
	function view_details(e,email_history_info){

            var  message=email_history_info.email_message;
            var  api_id=email_history_info.api_id;
            var  configure_table_name=email_history_info.configure_table_name;
            $("#modal_email_history_detail").modal();

            $("#to_email").html(email_history_info.to_email);
            $("#sent_time").html(email_history_info.sent_time);
            $("#message_subject").html(email_history_info.subject);
            $("#message_attachment").html(attachment_download(email_history_info.attachment));
            var message_url="<?php echo site_url('my_email/view_message'); ?>"+"/"+email_history_info.id;
            var message_link="<a target='_BLANK' href='"+message_url+"' title='Message Preview' class='btn btn-info'><?php echo $this->lang->line('message preview');?></a>";            
            $("#message").html(message_link);

            $.ajax({
                url: '<?php echo site_url("home/decode_html_send_as"); ?>',
                type: 'POST',
                dataType :'json',
                data: {message: message,api_id:api_id,configure_table_name:configure_table_name},
                success: function(response) 
                {
                      
                    $("#message_send_as").html(response.send_as);     
                }
            });
            
            
				
	}
	
	 function doSearch(event)
    {
        event.preventDefault(); 
        $j('#sms_history_table').datagrid('load',{
          schedule_from_date:             $j('#schedule_from_date').val(),
          schedule_to_date:               $j('#schedule_to_date').val(),
          is_searched:      1
        });
    }  
	
	
	$j("document").ready(function(){
		$('#schedule_from_date').datepicker({format: "dd-mm-yyyy"});    
		$('#schedule_to_date').datepicker({format: "dd-mm-yyyy"});    
	});
	

</script>