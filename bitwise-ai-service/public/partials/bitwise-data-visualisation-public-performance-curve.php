<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       www.bitwise.academy/
 * @since      1.0.0
 *
 * @package    Bitwise_Data_Visualisation
 * @subpackage Bitwise_Data_Visualisation/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<?php

?> 

<div class="row thumbnail bt_box_shadow">
	
	<div style='text-align: center;' class="col-md-12 card-header">
		<h3>Student - Performance Curve</h3>
	</div>

	<?php if(isset($performance_chart) && $performance_chart) { ?>
		<div class="col-md-3 pull-right">
			<input id="pc-student_id" type="hidden" name="student_id" value='52'>
			<select id="pc-search" class="form-control selectpicker">
				<?php 
		    		foreach ($get_datas as $data) {
		        	echo '<option value='.$data->ID.'>'.$data->post_title.'</option>';
				}?>
			</select>
		</div>
	<?php } ?>
	<?php if(isset($performance_chart) && $performance_chart) { ?>
		<div id="spc-chart">
	    	<svg></svg>
		</div>
	<?php } else {?>
		<center><h4>No Data</h4></center>
	<?php } ?>
</div>