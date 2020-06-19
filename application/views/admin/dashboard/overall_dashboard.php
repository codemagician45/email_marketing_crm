<style type="text/css">

	.redesign-aqua{
		background-image: -webkit-linear-gradient(90deg, #3f5efb 0%, #fc466b 100%);
	}
	.redesign-red{
		    background-image: -webkit-linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
	}
	.redesign-yellow{
		background-image: -webkit-linear-gradient(90deg, #45b649 0%, #dce35b 100%)
	}
	.redesign-red-one{
		    background-image: -webkit-linear-gradient(90deg, #ee0979 0%, #ff6a00 100%)
	}

	.box{border:1px solid #ccc;background: #fefefe;}

	.content-wrapper{background: #ecf0f5 !important;}

	.dashboard-arrow {
		margin-top: 10px;
		margin-bottom: 10px;
		padding: 11px 13px;
	}

	.dashboard-arrow .cmn {
		width: 100%;
		margin-bottom: 10px;
		/* background: #fff; */
	}	

	.dashboard-arrow .cmn .top-title {
		width: 100%;
		height: 70px;
		background-size: 100% 100%;
		color: #777;
		font-size: 16px;
		padding-top: 13px;
		text-align: center;
		background: #fff;
		/* border-radius: 15px 15px 0px 0px; */
	}	

	.dashboard-arrow .cmn .top-title.background-a {
		<?php echo $BOXSHADOW; ?>
	}		

	.dashboard-arrow .cmn .top-title.background-b {
		<?php echo $BOXSHADOW; ?>
	}	

	.dashboard-arrow .cmn .top-title.background-c {
		<?php echo $BOXSHADOW; ?>
	}

	.dashboard-arrow .cmn .top-title.background-d {
		<?php echo $BOXSHADOW; ?>
	}	

	.dashboard-arrow .cmn .stat {
		float: left;
		width: 100%;
		padding: 15px 20px 10px 20px;
		border-width: 0 1px 1px 1px;
		margin-top: -17px;		
		background: #fff;
		<?php echo $BOXSHADOW; ?>
	}	

	.dashboard-arrow .cmn .stat.a {
		border-color: #dc3545;
	}	

	.dashboard-arrow .cmn .stat.b {
		border-color: #3B90E6;
	}

	.dashboard-arrow .cmn .stat.c {
		border-color: #62AE62;
	}

	.dashboard-arrow .cmn .stat .icon {
		width: 50%;
		float: left;
	}

	.dashboard-arrow .cmn .stat .icon .icon-circle {
		/* background: #dc3545; */
		width: 70px;
		height: 70px;
		border-radius: 50%;
		text-align: center;
		padding-top: 11px;
		color: #fff;
		font-size: 23px;
	}	

	.dashboard-arrow .cmn .stat .icon .icon-circle.a {
		color: #00A65A;
		border: 1px solid #00A65A;
	}

	.dashboard-arrow .cmn .stat .icon .icon-circle.b {
		color: #F9A602;
		border: 1px solid #F9A602;
	}	

	.dashboard-arrow .cmn .stat .icon .icon-circle.c {
		color: #0A70E3;
		border: 1px solid #0A70E3;
	}	

	.dashboard-arrow .cmn .stat .icon .icon-circle.d {
		color: #FF4D7B;
		border: 1px solid #FF4D7B;
	}	

	.dashboard-arrow .cmn .stat .icon .icon-circle i {
		padding-top: 12px;
		padding-left: 3px;
	}	
	
	.dashboard-arrow .cmn .stat .number {
		width: 50%;
		float: left;
		color: #222;
		font-size: 20px;
		font-weight: 300;
		text-align: right;
		padding-top: 15px;
	}	


	.dashboard-arrow .cmn .stat .number.a {
		color: #00A65A;
	}

	.dashboard-arrow .cmn .stat .number.b {
		color: #F9A602;
	}	

	.dashboard-arrow .cmn .stat .number.c {
		color: #0A70E3;
	}	

	.dashboard-arrow .cmn .stat .number.d {
		color: #FF4D7B;
	}	


</style>


<div class="well well_border_left">
	<h4 class="text-center"> <i class="fa fa-dashboard"></i> <?php echo $this->lang->line('Dashboard');?></h4>
</div>
<section class="content-header" style="padding:0px;">
   <section class="content">

				<?php 										
					if ($total_email_sent=="") $total_email_sent=0; 
					if ($total_sms_sent=="") $total_sms_sent=0; 
					if ($today_total_email_sent=="") $today_total_email_sent=0; 
					if ($today_total_sms_sent=="") $today_total_sms_sent=0; 
					if ($total_sent_email_this_month=="") $total_sent_email_this_month=0; 
					if ($total_sent_sms_this_month=="") $total_sent_sms_this_month=0; 
				?>

				<!-- <div class="row">
					<div class="text-center"><h2 style="font-weight:900;">TOTAL EMAIL & SMS SENT REPORT</h2></div>
					<div id="div_for_circle_chart"></div>
				</div> -->

	<div class="dashboard-arrow row">
		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="cmn">
				<div class='top-title background-a'>
					<?php echo $this->lang->line("Today's Sent Email");?>
				</div>
				<div class='stat a'>

					<div class='icon'>
						<div class='icon-circle a'><i class='fa fa-envelope'></i></div>
					</div>
					<div class='number a'>
						<?php echo  $this->lang->line("Total")." : ".$today_total_email_sent; ?>
					</div>
				</div>
				<div class="clearfix"></div>		
			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="cmn">
				<div class='top-title background-c'>
					<?php echo $this->lang->line("Today's Sent SMS");?>
				</div>

				<div class='stat c'>
					<div class='icon'>
						<div class='icon-circle c'><i class='fa fa-send'></i></div>
					</div>
					<div class='number c'>
						<?php echo  $this->lang->line("Total")." : ".$today_total_sms_sent; ?>
					</div>
				</div>
				<div class="clearfix"></div>		
			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="cmn">
				<div class='top-title background-b'>
					<?php echo $this->lang->line("This Month's Sent Email");?>
				</div>
				<div class='stat b'>
					<div class='icon'>
						<div class='icon-circle b'><i class='fa fa-envelope'></i></div>
					</div>
					<div class='number b'>

						<?php echo $this->lang->line("Total")." : ".$total_sent_email_this_month; ?>
					</div>
				</div>
				<div class="clearfix"></div>		
			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="cmn">
				<div class='top-title background-d'>
					<?php echo $this->lang->line("This Month's Sent SMS");?>
				</div>
				<div class='stat d'>
					<div class='icon'>
						<div class='icon-circle d'><i class='fa fa-send'></i></div>
					</div>
					<div class='number d'>
						<?php echo $this->lang->line("Total")." : ".$total_sent_sms_this_month; ?>
					</div>
				</div>
				<div class="clearfix"></div>		
			</div>
		</div>		

	</div> 	

	<div class="row" style="padding-left:12px;padding-right: 12px;">

			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

				<div style="background: #fff;padding:20px 0;margin:7px 2px;border-radius: 10px;min-height: 500px;" class="">

				
						<div class="text-center col-xs-12" style="font-size:17px;font-weight: bold; color:#3C8DBC;"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('total email sent report'); ?></div><br>
						 <br>
						 <br>
						<div class="box-body">
							<div class="row">
								<div class="col-md-8 col-xs-12">
									<div class="chart-responsive">
										<canvas id="pieChart" height="220"></canvas>
									</div><!-- ./chart-responsive -->
								</div><!-- /.col -->
								<div class="col-md-4 col-xs-12" style="padding-top:35px;">
									<ul class="chart-legend clearfix">
										<?php foreach($email_gateway_name as $value): ?>
											<li><i class="fa fa-circle-o" style="color:<?php echo $value['color']; ?>"></i> <?php echo $value['name']; ?></li>
										<?php endforeach; ?>
									</ul>
								</div><!-- /.col -->
							</div><!-- /.row -->
						</div><!-- /.box-body -->
				</div>

			</div>



			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

				<div style="background: #fff;padding:20px 0;margin:7px 2px;border-radius: 10px;min-height: 500px;" class="">

				<div class="text-center col-xs-12" style="font-size:17px;font-weight: bold; color:#3C8DBC;"><i class="fa fa-send"></i> <?php echo $this->lang->line('total sms sent report'); ?></div><br>
					<br>
					<br>
					<div class="box-body">
						<div class="row">
							<div class="col-md-8 col-xs-12">
								<div class="chart-responsive">
									<canvas id="pieChart_sms" height="220"></canvas>
								</div><!-- ./chart-responsive -->
							</div><!-- /.col -->
							<div class="col-md-4 col-xs-12" style="padding-top:35px;">
								<ul class="chart-legend clearfix">
									<?php foreach($gateway_name as $sms_gateway): ?>
										<li><i class="fa fa-circle-o" style="color:<?php echo $sms_gateway['color']; ?>"></i> <?php echo $sms_gateway['name']; ?></li>
									<?php endforeach; ?>
								</ul>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.box-body -->

		

			

				</div>

			</div>

	</div>



		
				<!--end of daily report section -->		

				<!-- monthly report section -->
		

				
				<!--end of monthly report section -->

				
		<br/>
		<div class="row" style="background: #fff;padding:20px 0;margin:7px 2px;border-radius: 10px;">
			
			<div class="text-center" style="font-size:17px;font-weight: bold; color:#3C8DBC;"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('EMAIL & SMS SENT REPORT FOR LAST 12 MONTHS'); ?></div><br>
			<div id='div_for_bar'></div>
		</div>

				
				<div>
				
				
				<?php
				
  						// $bar=array("0"=>array("y"=>2014,"a"=>100,"b"=>50),"1"=>array("y"=>2015,"a"=>100,"b"=>50));
				$bar = $chart_bar;
				$circle_bir = array(
					'0' => array(
						'label'=>"Total Email Sent",
						'value'=>$total_email_sent
						),
					'1' =>array(
						'label'=>"Total SMS Sent",
						'value'=>$total_sms_sent
						
						)
					
					);
				
				 ?>
				
				
				<input type="hidden" id="pichart_sms_data" value='<?php echo $piechart_sms; ?>' />
				<input type="hidden" id="pichart_email_data" value='<?php echo $piechart_email; ?>' />

			</div>
		
   </section>
</section>


<script>
$j(document).ready(function(){


  var pieChartCanvas = $j("#pieChart").get(0).getContext("2d");
  var pieChart = new Chart(pieChartCanvas);
  var PieData = $("#pichart_email_data").val();
  PieData=JSON.parse(PieData); 

  var pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 20, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%>"
  };
  pieChart.Doughnut(PieData, pieOptions);



  var pieChartCanvas_sms = $j("#pieChart_sms").get(0).getContext("2d");
  var pieChart_sms = new Chart(pieChartCanvas_sms);

  var PieData_sms = $("#pichart_sms_data").val(); 
  PieData_sms=JSON.parse(PieData_sms); 


  var pieOptions_sms = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 20, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%>"
  };
  pieChart_sms.Doughnut(PieData_sms, pieOptions_sms);

	Morris.Bar({
	  element: 'div_for_bar',
	  data: <?php echo json_encode($bar); ?>,
	  xkey: 'year',
	  ykeys: ['sent_email', 'sent_sms'],
	  labels: ['Total Sent Email', 'Total Sent Sms']
	});
});

// Morris.Donut({
//   element: 'div_for_circle_chart',
//   data: <?php echo json_encode($circle_bir); ?>
// });
</script>






