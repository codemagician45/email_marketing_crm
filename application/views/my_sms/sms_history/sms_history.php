<?php $this->load->view('admin/theme/message'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><i class="fa fa-history"></i> <?php echo $this->lang->line('My SMS History');?> </h1>  
</section>

<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width: 100%; height:659px !important;">
            <table 
            id="sms_history_table"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."my_sms/my_sms_history_data"; ?>" 
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
                       <th field="to_number" sortable="true" ><?php echo $this->lang->line('Sent To');?></th>
                        <th field="sent_time" sortable="true" ><?php echo $this->lang->line('Sent At');?></th>
                        <th field="sms_status" sortable="true" ><?php echo $this->lang->line('Delivery Status');?></th>
                        <th field="sms_uid" sortable="true"><?php echo $this->lang->line('SMS ID');?></th>
                        <!-- <th field="message" sortable="true" formatter='message_formatter'>Message');?></th> -->
                        <th field="view"  formatter='action_column'><?php echo $this->lang->line('Actions');?></th>
                    </tr>
                </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">  
            <form class="form-inline" style="margin-top:20px">
                <div class="form-group">
                    <input  id="schedule_from_date" name="schedule_from_date" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Schedule From Date'); ?>">
                </div> 
				<div class="form-group">
                     <input  id="schedule_to_date" name="schedule_to_date" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Schedule To Date'); ?>">
                </div>
                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line('Search');?></button>
            </form>         
        </div>
    </div>
  </div>   
</section>



<!--  Modal for contacts show of the tution -->

<div id="modal_sms_history_detail" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
		
            <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						 <span aria-hidden="true">&times;</span>
				 </button>
                <h4 id="sms_history_details_title" class="modal-title"><?php echo $this->lang->line('SMS History Details'); ?></h4>
            </div>
			
            <div id="sms_history_view_body" class="modal-body">
			
			    <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-user"></i> <?php echo $this->lang->line('Send As'); ?> </h4> <br/>
                    <p id="send_as"></p> 
                </div>

                <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-send"></i> <?php echo $this->lang->line('Send To'); ?> </h4> <br/>
                    <p id="to_number"></p> 
                </div>

                <div class="bs-callout bs-callout-info"> 
                    <h4><i class="fa fa-clock-o"></i> <?php echo $this->lang->line('Sent At'); ?> </h4> <br/>
                    <p id="sent_time"></p> 
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
	
	   
		var sms_history_info=JSON.stringify(row);
			sms_history_info=HTMLEncode(sms_history_info);
		  str="<a class='btn btn-outline-info' title='View Contacts' style='cursor:pointer' onclick='view_details(event,"+sms_history_info+")' >" +' <i class="fa fa-eye"></i>'+"</a>";
   		return str;
	}
	
	function view_details(e,schedule_info){

        $("#modal_sms_history_detail").modal();
        var  message=schedule_info.message;
        $.ajax({
                url: '<?php echo site_url("home/decode_url"); ?>',
                type: 'POST',
                data: {message: message},
                success: function(response) 
                {
                   $("#send_as").html(schedule_info.send_as);
                   $("#to_number").html(schedule_info.to_number);
                   $("#sent_time").html(schedule_info.sent_time);
                   $("#message").html(response);     
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