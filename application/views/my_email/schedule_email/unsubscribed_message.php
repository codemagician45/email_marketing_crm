<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo base_url('plugins/alertifyjs/css/alertify.min.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('plugins/alertifyjs/css/themes/default.min.css')?>" />

<div class="container">
	<div class="row">
		<div class="col-6 offset-3">
			<div class="text-center" style="text-align: center;font-size: 20px;">
				<p style="padding-top: 80px;"><?php echo $this->lang->line("Do you want to unsubscribe from our mailing service?");?></p>
				<input type="hidden" id="contactid" value="<?php echo $contact_id; ?>">
				<input type="hidden" id="email" value="<?php echo $email_address; ?>">
				<a href="" class="btn btn-outline-primary btn-sm" id="subscribed" button-type="sub"><i class="fa fa-bell"></i> <?php echo $this->lang->line("Subscribe");?></a>
				<a href="" class="btn btn-outline-danger btn-sm" id="unsubscribe" button-type="unsub"><i class="fa fa-user-times"></i> <?php echo $this->lang->line("Unsubscribe");?></a>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/24f7885cb9.js"></script>
<script src="<?php echo base_url('plugins/alertifyjs/alertify.min.js')?>"></script>


<script>
	$(document).ready(function($) {

		var base_url = '<?php echo base_url() ?>';
		var status = '<?php echo $status; ?>';
		
		if(status == '0')
		{
			$("#unsubscribe").show();
			$("#subscribed").hide();
		}

		if(status == '1')
		{
			$("#unsubscribe").hide();
			$("#subscribed").show();
		}

		$(document.body).on('click', '#unsubscribe', function(event) {
			event.preventDefault();
			var contactid = $("#contactid").val();
			var email 	  = $("#email").val();
			var btntype   = $(this).attr("button-type");

			if(contactid != '' || email != '')
			{
				$.ajax({
					url: base_url+'home/unsubscribe_action',
					type: 'POST',
					data: {contactid: contactid, email:email, btntype:btntype},
					success:function(response)
					{
						if(response == "1")
						{
							alertify.success('<?php echo $this->lang->line("you have unsubscribed successfully from our mailing services.")?>');
							$("#subscribed").show();
							$("#unsubscribe").hide();
							
						}

					}
				})
			}
		});

		$(document.body).on('click', '#subscribed', function(event) {
			event.preventDefault();
			var contactid = $("#contactid").val();
			var email 	  = $("#email").val();
			var btntype   = $(this).attr("button-type");

			if(contactid != '' || email != '')
			{
				$.ajax({
					url: base_url+'home/unsubscribe_action',
					type: 'POST',
					data: {contactid: contactid, email:email, btntype:btntype},
					success:function(response)
					{
						if(response == "1")
						{
							alertify.success('<?php echo $this->lang->line("you have subscribed successfully into our mailing services.")?>');
							$("#subscribed").hide();
							$("#unsubscribe").show();
							
						}

					}
				})
			}
		});
	});

</script>