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
<div class="clearfix"></div>
<div class="row thumbnail bt_box_shadow">
	<div class="card-header text-center"><h3>Knowledge Concept Graph</h3></div>
	<div class="col-md-3 pull-right">
	<?php if(!empty($kcgChart)){?>
		<input id="kcg-student_id" type="hidden" name="student_id" value='<?php echo $user_id; ?>'>
	    <select id="kcg-search" class="form-control selectpicker">
	    <?php 
	        foreach ($get_datas as $data) {
	            echo '<option value='.$data->ID.'>'.$data->post_title.'</option>';
	    	}
	    ?>
	    
	    </select>
	<?php }?>
	</div>
	<div class="clearfix"></div><br/>
	<!--<?php if(!empty($kcgChart) ) { ?>-->
    	<div class="kcg_chart_area"><div id="kcg-chart"></div></div>
	<!--<?php } else { ?>
		<center><h4>No Data</h4></center>
	<?php } ?>-->
</div>