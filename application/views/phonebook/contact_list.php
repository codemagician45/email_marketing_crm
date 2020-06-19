<?php $this->load->view('admin/theme/message'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $this->lang->line('Contact'); ?>  </h1>

</section>


<!-- Main content -->
<section class="content">  
  <div class="row" >
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:659px !important;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."phonebook/contact_list_data"; ?>" 

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
                      <th field="id"  checkbox="true"></th>
                      <th field="first_name"  sortable="true" ><?php echo $this->lang->line('First Name'); ?></th>
                      <th field="last_name" sortable="true" ><?php echo $this->lang->line('Last Name'); ?></th>
                      <th field="phone_number" sortable="true" ><?php echo $this->lang->line('Mobile No.'); ?></th>
                      <th field="email" sortable="true" ><?php echo $this->lang->line('Email'); ?></th>
                      <th field="date_birth" formatter="valid_date" sortable="true"><?php echo $this->lang->line('Date of Birth'); ?></th>                     
                      <th field="contact_type_id"  sortable="true"><?php echo $this->lang->line('Contact Group'); ?></th>                     
                      <th field="view" formatter='action_column'><?php echo $this->lang->line('Actions'); ?></th>    
                  </tr>
              </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">

       <a class="btn btn-primary"  title="Add Contact" href="<?php echo site_url('phonebook/add_contact');?>">
            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add Contact'); ?>
       </a>

        <button type="button" class="btn btn-success pull-right" id = "url_with_email_wise_download_btn" style = 'margin-right:10px'><i class="fa fa-cloud-download"></i><?php echo $this->lang->line('Export'); ?></button>

       <a class="btn btn-primary pull-right" style="margin-right: 5px" title="Import" href="<?php echo site_url('phonebook/import_contact');?>">
            <i class="fa fa-cloud-upload"></i><?php echo $this->lang->line('Import'); ?> 
       </a>
              
            <form class="form-inline" style="margin-top:20px">
                <div class="form-group">
                    <input id="first_name" name="first_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line('First Name'); ?>">
                </div>   

                <div class="form-group">
                    <input id="last_name" name="last_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Last Name'); ?>">
                </div>

                <div class="form-group">
                    <input id="phone_number" name="phone_number" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Mobile No.'); ?>">
                </div>   

                <div class="form-group">
                    <input id="email" name="email" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Email'); ?>">
                </div>

                 <div class="form-group">
                    <input id="dob" name="dob" class="form-control datepicker" size="22" placeholder="<?php echo $this->lang->line('Date of Birth (mm/dd/yyyy)'); ?>">
                </div>  

                 <div class="form-group">
                    <?php 
                        $contact_type_id['']=$this->lang->line('Contact Group');
                        echo form_dropdown('contact_type_id',$contact_type_id,set_value('contact_type_id'),'class="form-control" id="contact_type_id"');  
                        ?>
                </div>  
                
                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line('Search'); ?></button>     
                      
            </form> 

        </div>        
    </div>
  </div>   
</section>


<script> 

	 $j(function() {
    $( ".datepicker" ).datepicker();
  });      
    var base_url="<?php echo site_url(); ?>";     

    function action_column(value,row,index)
    {             
               
        var edit_url=base_url+'phonebook/update_contact/'+row.id;
        // var delete_url=base_url+'phonebook/delete_contact_action/'+row.id;
        
        var str="";     
      
        str=str+"&nbsp;&nbsp;&nbsp;&nbsp;<a class='edit_button btn btn-outline-warning' style='cursor:pointer' title='"+'Edit'+"' href='"+edit_url+"'>"+'<i class="fa fa-edit"></i>'+"</a>";
        var delete_str="<?php echo $this->lang->line('Are you sure that you want to delete this record?');?>";
        str=str+"&nbsp;&nbsp;<a class='btn btn-outline-danger delete_contact' table-id='"+row.id+"' style='cursor:pointer'  title='"+'Delete'+"'>"+' <i class="fa fa-trash"></i>'+"</a>";
        
        return str;
    } 


    $("#url_with_email_wise_download_btn").click(function(){
    var base_url="<?php echo base_url(); ?>";
    $('#url_with_email_wise_download_btn').html("<?php echo $this->lang->line('please wait');?>");
    var link = "<?php echo site_url('phonebook/url_with_email_wise_download');?>";
    var rows = $j("#tt").datagrid("getSelections");
    var info=JSON.stringify(rows); 
    if(rows == '')
    {
      $('#url_with_email_wise_download_btn').html('<i class="fa fa-cloud-download"></i>'+"<?php echo $this->lang->line('Export');?>");
      alert("<?php echo $this->lang->line('You have not select any contact.');?>");
      return false;
    }
    $.ajax({
      type:'POST',
      url:link,
      data:{info:info},
      success:function(response)
      {
        if(response!="")         
        {
          response=base_url+response;
          $('#url_with_email_wise_download_btn').html('<i class="fa fa-cloud-download"></i>'+"<?php echo $this->lang->line('Export');?>");
          $('#download_content').html('<i class="fa fa-2x fa-thumbs-o-up" style="color:black"></i><br><br><a href="'+response+'" title="Download" class="btn btn-warning btn-lg" style="width:200px;""><i class="fa fa-cloud-download" style="color:white"></i> <?php echo $this->lang->line('Download');?></a>');
          $('#modal_for_download_url').modal();  
        }      
        else         
        alert("<?php echo $this->lang->line('Something went wrong, please try again.');?>");     
      }
    });
  });   

  function valid_date(value,row,index)
  {
     if(value=="0000-00-00") return "";
     return value;
  }          
   
    function doSearch(event)
    {
        event.preventDefault(); 
        $j('#tt').datagrid('load',{
          first_name       :     $j('#first_name').val(),
          last_name        :     $j('#last_name').val(),
          phone_number     :     $j('#phone_number').val(),       
          email            :     $j('#email').val(),    
          dob        :           $j('#dob').val(),       
          contact_type_id  :     $j('#contact_type_id').val(),         
          is_searched      :      1
        });


    }


    $(document.body).on('click', '.delete_contact', function(event) {
      event.preventDefault();
      var table_id = $(this).attr("table-id");
      alertify.confirm('<?php echo $this->lang->line("are you sure");?>','<?php echo $this->lang->line("Are you sure you want to delete this contact?"); ?>', 
        function(){ 
          $.ajax({
            url: base_url+'phonebook/delete_contact_action',
            type: 'POST',
            data: {table_id: table_id},
            success:function (response){
              if(response == "1"){
                $j('#tt').datagrid('reload');
                alertify.success('<?php echo $this->lang->line("your data has been successfully deleted from the database."); ?>');
              } else{
                
                $j('#tt').datagrid('reload');
                alertify.error('<?php echo $this->lang->line("something went wrong,please try again."); ?>');
              }
            }
          });
        },
        function(){});
      
    });

</script>

<!-- Modal for download -->
<div id="modal_for_download_url" class="modal fade">
  <div class="modal-dialog" style="width:65%;">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&#215;</span>
        </button>
        <h4 id="" class="modal-title"><i class="fa fa-cloud-download"></i> <?php echo $this->lang->line('Export Contact (CSV)'); ?></h4>
      </div>

      <div class="modal-body">
        <style>
        .box
        {
          border:1px solid #ccc;  
          margin: 0 auto;
          text-align: center;
          margin-top:10%;
          padding-bottom: 20px;
          background-color: #fffddd;
          color:#000;
        }
        </style>
        <!-- <div class="container"> -->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
              <div class="box">
                <h2><?php echo $this->lang->line('Your file is ready to download'); ?></h2>
                <span id="download_content"></span>
              </div>    
              
            </div>
          </div>
        <!-- </div>  -->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
      </div>
    </div>
  </div>
</div>
