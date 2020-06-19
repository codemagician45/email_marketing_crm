<div class="bs-callout bs-callout-info"> 
	<h4><i class="fa fa-send"></i><?php echo $this->lang->line('Send To');?></h4> <br>
	<?php
	echo "<table width='100%' class='responsive table table-bordered table-zebra'>";
			
			echo "<tr>";
				echo "<th>".$this->lang->line("name")."</th>";
				echo "<th>".$this->lang->line("mobile")."</th>";
			echo "</tr>";
			
	 foreach($contact_details as $info){
			
				$name=$info['first_name']. " " . $info['last_name'] ;
				$mobile=$info['phone_number'];
				echo "<tr>";
					echo "<td>{$name}</td>";
					echo "<td>{$mobile}</td>";
				echo "</tr>";
			}
	echo "</table>";	
	?>	
</div>
	

