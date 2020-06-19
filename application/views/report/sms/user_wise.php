<?php $this->load->view('admin/theme/message'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> <?php echo $this->lang->line("Users' SMS Report");?> </h1>  
</section>

<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container">
            <table 
            id="sms_history_table"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."report/sms_report_userwise_data"; ?>" 
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
                       <th field="username" sortable="true" ><?php echo $this->lang->line('Username'); ?></th>
                       <th field="first_name" sortable="true" ><?php echo $this->lang->line('First Name'); ?></th>
                       <th field="last_name" sortable="true" ><?php echo $this->lang->line('Last Name'); ?></th>
                       <th field="email" sortable="true" ><?php echo $this->lang->line('Email'); ?></th>
                       <th field="mobile" sortable="true" ><?php echo $this->lang->line('Mobile'); ?></th>
                       <th field="total_sms_sent" sortable="true" ><?php echo $this->lang->line('Total SMS Sent'); ?></th>
                    </tr>
                </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">  
          <a target="_blank" class="btn btn-primary"  title="Download" href="<?php echo site_url('report/download_sms_report_userwise');?>">
            <i class="fa fa-cloud-download"></i> <?php echo $this->lang->line('Download'); ?>
          </a>
            <form class="form-inline" style="margin-top:20px">
                <div class="form-group">
                    <input  id="schedule_from_date" name="schedule_from_date" class="form-control" size="20" placeholder="<?php echo $this->lang->line('From Date'); ?> ">
                </div> 
				
				        <div class="form-group">
                     <input  id="schedule_to_date" name="schedule_to_date" class="form-control" size="20" placeholder="<?php echo $this->lang->line('To Date'); ?> ">
                </div>
                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line('Search'); ?></button>
            </form>         
        </div>
    </div>
  </div>   
</section>


<script type="text/javascript">
	
	var base_url="<?php echo site_url(); ?>";
	
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