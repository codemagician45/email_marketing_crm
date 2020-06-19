
<div class="bs-callout bs-callout-info"> 
	<h4><i class="fa fa-check-square"></i> <?php echo $this->lang->line('Send As'); ?></h4> <br/>
	<p><?php echo $gateway_name.$send_as_info[0]['email_address']; ?></p> 
</div>

<div class="bs-callout bs-callout-info"> 
	<h4><i class="fa fa-send"></i> <?php echo $this->lang->line('Send To'); ?></h4> <br/>
	<p>
		<?php			
			echo "<table width='100%' class='responsive table table-zebra'>";
					
					echo "<tr>";
						echo "<th>".$this->lang->line('name')."</th>";
						echo "<th>".$this->lang->line('email')."</th>";
					echo "</tr>";
					
			 foreach($contact_details as $info){
					
						$name=$info['first_name']. " " . $info['last_name'] ;
						$email=$info['email'];
						echo "<tr>";
							echo "<td>{$name}</td>";
							echo "<td>{$email}</td>";
						echo "</tr>";
					}
			echo "</table>";		
	
	    ?>
	</p> 
</div>	
