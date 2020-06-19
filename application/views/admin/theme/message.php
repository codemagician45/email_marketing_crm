<?php 
	if($this->session->flashdata('success_message')==1)
	echo "<div class='alert alert-success text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i> ".$this->lang->line('your data has been successfully stored into the database.')." </h4></div>";

	if($this->session->flashdata('warning_message')==1)
	echo "<div class='alert alert-warning text-center'><h4 style='margin:0;'><i class='fa fa-warning'></i> ".$this->lang->line('something went wrong, please try again.')." </h4></div>";

	if($this->session->flashdata('error_message')==1)
	echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i>" .$this->lang->line('your data has been failed to stored into the database.'). "</h4></div>";
		
	if($this->session->flashdata('delete_success_message')==1)
	echo "<div class='alert alert-success text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i> ".$this->lang->line('your data has been successfully deleted from the database.')." </h4></div>";
	
	if($this->session->flashdata('delete_error_message')==1)
	echo "<div class='alert alert-success text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i> ". $this->lang->line('your data has been failed to delete from the database.') ."</h4></div>";
	
	if($this->session->flashdata('custom_success_message')!="")
	echo "<div class='alert alert-success text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i>".$this->session->flashdata('custom_success_message')."</h4></div>";
	if($this->session->flashdata('custom_error_message')!="")
	echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i>".$this->session->flashdata('custom_error_message')."</h4></div>";
	
	if($this->session->userdata('notice_deleted')!=""){
		echo "<div class='alert alert-success text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i>".$this->session->userdata('notice_deleted')."</h4></div>";
		$this->session->unset_userdata('notice_deleted');
	}


?>