<?php $this->load->view('admin/theme/message'); ?>

<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-envelope"></i> <?php echo $this->lang->line('Email Campaigns'); ?>  </h1>  
</section>

<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12 table-responsive">
        <div class="grid_container" style="width: 100%; height:659px !important;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."my_email/email_campaign_data"; ?>" 
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
                        <th field="campaign_name" sortable="true"><?php echo $this->lang->line('Campign Name');?></th>
                        <th field="email_api" sortable="true" ><?php echo $this->lang->line('Email API');?></th>
                        <th field="sent_count" sortable="true"><?php echo $this->lang->line('Total Sent');?></th>
                        <th field="post_status_formatted" sortable="true" ><?php echo $this->lang->line('status');?></th>
                        <th field="actions" sortable="true" align="left"><?php echo $this->lang->line('actions');?></th>
                        <th field="scheduled_at" sortable="true"><?php echo $this->lang->line('Scheduled at');?></th>
                        <th field="added_at" sortable="true"><?php echo $this->lang->line('created at');?></th>
                        <th field="attachement" sortable="true"><?php echo $this->lang->line('Attachment');?></th>
                    </tr>
                </thead>
            </table>                        
         </div>

             <div id="tb" style="padding:3px"> 
                <?php 
                    $search_campaign_name   = $this->session->userdata('email_sending_campaign_name');
                    $posting_status         = $this->session->userdata('email_sending_posting_status');
                    $search_scheduled_from  = $this->session->userdata('email_sending_scheduled_from');
                    $search_scheduled_to    = $this->session->userdata('email_sending_scheduled_to');
                ?>

               <a class="btn btn-primary"  title="Add Scheduler" href="<?php echo site_url('my_email/add_schedule');?>">
                   <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('New Email Campaign');?>
               </a>
               <form class="form-inline" style="margin-top:20px">
                    <div class="form-group">
                        <input id="campaign_name" name="campaign_name" class="form-control" value="<?php echo $search_campaign_name;?>" size="20" placeholder="<?php echo $this->lang->line('campaign name');?>">
                    </div> 

                    <div class="form-group">
                        <select name="search_status" id="search_status"  class="form-control">
                            <option value="" <?php if($this->session->userdata('email_sending_posting_status') == '') echo "selected"; ?>><?php echo $this->lang->line("status") ?></option>
                            <option <?php if($posting_status == "0") echo "selected";?> value="0">
                                <?php echo $this->lang->line("pending") ?>
                            </option>
                            <option <?php if($posting_status == "1") echo "selected";?> value="1">
                                <?php echo $this->lang->line("processing") ?>
                            </option>
                            <option <?php if($posting_status == "2") echo "selected";?> value="2">
                                <?php echo $this->lang->line("completed") ?>
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                       <input  id="schedule_from_date" name="schedule_from_date" class="form-control" value="<?php echo $search_scheduled_from;?>" size="20" placeholder="<?php echo $this->lang->line('Schedule From');?>">
                    </div> 
                    <div class="form-group">
                        <input  id="schedule_to_date" name="schedule_to_date" value="<?php echo $search_scheduled_to;?>" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Schedule To');?>">
                   </div>
                   <button class='btn btn-info' onclick="doSearch(event)"><?php echo $this->lang->line('Search'); ?></button>
               </form>         
             </div>
    </div>
  </div>   
</section>



<!--  Modal -->

<div id="modal_contact_detail" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">		
            <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						 <span aria-hidden="true">&times;</span>
				 </button>
                <h4 id="contact_details_title" class="modal-title"><?php echo $this->lang->line('Scheduler Details'); ?></h4>
            </div>
			
            <div class="modal-body">

            <div class="bs-callout bs-callout-info"> 
            	<h4><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('Subject'); ?> </h4> <br/>
            	<p id="scheduled_subject"></p> 
            </div>

            <div class="bs-callout bs-callout-info"> 
            	<h4><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('Attachment'); ?> </h4> <br/>
            	<p id="scheduled_attachment"></p> 
            </div>

            <div class="bs-callout bs-callout-info"> 
            	<h4><i class="fa fa-envelope"></i> <?php echo $this->lang->line('Message'); ?> </h4> <br/>
            	<p id="scheduled_message"></p> 
            </div>

            <div id="contacts_view_body"></div>				
			</div>
			
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            </div>
        </div>
    </div>
</div>

<?php 
    $alreadyEnabled = $this->lang->line("this campaign is already enable for processing.");
    $somethingwentwrong = $this->lang->line("something went wrong.");
    $doyoureallywanttodeletethiscampaign = $this->lang->line("do you really want to delete this campaign?");
    $Doyouwanttopausethiscampaign = $this->lang->line("do you want to pause this campaign?");
    $Doyouwanttostartthiscampaign = $this->lang->line("do you want to start this campaign?");
    $reprocessalertmessage = $this->lang->line("Force Reprocessing means you are going to process this campaign again from where it ended. You should do only if you think the campaign is hung for long time and didn't send message for long time. It may happen for any server timeout issue or server going down during last attempt or any other server issue. So only click OK if you think message is not sending. Are you sure to Reprocessing ?");
?>


<script type="text/javascript">
	
	var base_url="<?php echo site_url(); ?>";

    // datepicker plugin
    $('#schedule_from_date').datepicker({format: "dd-mm-yyyy"});        
    $('#schedule_to_date').datepicker({format: "dd-mm-yyyy"});

    function doSearch(event)
    {
        event.preventDefault(); 
        $j('#tt').datagrid('load',{
            campaign_name  : $j('#campaign_name').val(),
            posting_status : $j('#search_status').val(),
            scheduled_from : $j('#schedule_from_date').val(),
            scheduled_to   : $j('#schedule_to_date').val(),
            is_searched    :      1
        });
    }  



    $j("document").ready(function(){

        // showing campaign report
        $(document.body).on('click','.sent_report',function(){
            event.preventDefault();

            var loading = '<br/><img src="'+base_url+'assets/pre-loader/Fading squares2.gif" class="center-block"><br/>';
            $("#sent_report_body").html(loading);
            $("#sent_report_modal").modal();

            var id = $(this).attr('cam-id');

            $.ajax({
                type:'POST' ,
                url:"<?php echo site_url();?>my_email/campaign_sent_status",
                data:{id:id},
                success:function(response){
                    $("#sent_report_body").html(response);
                }
            });
        });

        // restart the camapaign where it is left
        $(document.body).on('click','.restart_button',function(){
            event.preventDefault();

            var table_id = $(this).attr('table_id');
            var reprocessalertmessage = "<?php echo $reprocessalertmessage; ?>";

            alertify.confirm('<?php echo $this->lang->line("are you sure");?>',reprocessalertmessage, 
              function(){ 
                $.ajax({
                   type:'POST' ,
                   url: "<?php echo base_url('my_email/restart_campaign')?>",
                   data: {table_id:table_id},
                   success:function(response)
                   {
                        if(response=='1'){
                            $j('#tt').datagrid('reload');
                            $("#sent_report_modal").modal('hide');
                            alertify.success('<?php echo $this->lang->line("campaign has been restarted successfully."); ?>');
                        }
                   }
                });
              },
              function(){     
              });
        });

        // force processing from grib table
        $(document.body).on('click','.force',function(){
            event.preventDefault();
            var id = $(this).attr('id');
            var alreadyEnabled = "<?php echo $alreadyEnabled; ?>";
            var reprocessalertmessage = "<?php echo $reprocessalertmessage; ?>";

             alertify.confirm('<?php echo $this->lang->line("are you sure");?>',reprocessalertmessage, 
              function(){ 
                $.ajax({
                   type:'POST' ,
                   url: "<?php echo base_url('my_email/force_reprocess_campaign')?>",
                   data: {id:id},
                   success:function(response)
                   {
                    if(response=='1')
                        $j('#tt').datagrid('reload');
                    else
                        alertify.alert('<?php echo $this->lang->line("Alert");?>',alreadyEnabled,function(){});
                   }
                });
              },
              function(){     
              });
        });


        /* delete a campaign */
        
        $(document.body).on('click','.delete',function(){
            event.preventDefault();
            var id = $(this).attr('id');

            if (typeof(id)==='undefined')
            {
                alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("this campaign is in processing state");?>',function(){});
                return;
            }
            var somethingwentwrong = "<?php echo $somethingwentwrong; ?>";
            var doyoureallywanttodeletethiscampaign = "<?php echo $doyoureallywanttodeletethiscampaign; ?>";

            alertify.confirm('<?php echo $this->lang->line("are you sure");?>',doyoureallywanttodeletethiscampaign, 
              function(){ 
                $.ajax({
                   type:'POST' ,
                   url: "<?php echo base_url('my_email/delete_campaign')?>",
                   data: {id:id},
                   success:function(response)
                   {
                    if(response=='1')
                    {
                        $j('#tt').datagrid('reload');
                        alertify.success('<?php echo $this->lang->line("your data has been successfully deleted from the database."); ?>');

                    }
                    else
                    alertify.alert('<?php echo $this->lang->line("Alert");?>',somethingwentwrong,function(){});
                   }
                });
              },
              function(){     
            });
        });


        /* pause a campaign */
        
        $(document.body).on('click','.pause_campaign_info',function(){
            var Doyouwanttopausethiscampaign = "<?php echo $Doyouwanttopausethiscampaign; ?>";
            var table_id = $(this).attr('table_id');
            alertify.confirm('<?php echo $this->lang->line("are you sure");?>',Doyouwanttopausethiscampaign, 
              function(){ 
                $.ajax({
                    type:'POST' ,
                    url: base_url+"my_email/ajax_campaign_pause",
                    data: {table_id:table_id},
                    success:function(response){
                        $j('#tt').datagrid('reload');
                    }

                });
              },
              function(){     
            });
        });

        /* play a campaign from stopped */
        $(document.body).on('click','.play_campaign_info',function(){
            var Doyouwanttostartthiscampaign = "<?php echo $Doyouwanttostartthiscampaign; ?>";
            var table_id = $(this).attr('table_id');

            alertify.confirm('<?php echo $this->lang->line("are you sure");?>',Doyouwanttostartthiscampaign, 
              function(){ 
                $.ajax({
                    type:'POST' ,
                    url: base_url+"my_email/ajax_campaign_play",
                    data: {table_id:table_id},
                    success:function(response){
                        $j('#tt').datagrid('reload');
                    }

                });
              },
              function(){     
            });
        });
        

    });

</script>

<div class="modal fade" id="sent_report_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" style="min-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-th-list"></i> <?php echo $this->lang->line("campaign report") ?></h4>
            </div>
            <div class="modal-body" id="sent_report_body">

            </div>
        </div>
    </div>
</div>