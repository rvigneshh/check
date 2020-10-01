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
	<div class="card-header text-center"><h3>bitWise IQ - Knowledge Concept Graph</h3></div>
	<div class="col-md-3 pull-right">
	<?php if(!empty($iqkcgChart)){?>
	    <input id="iqkcg-student_id" type="hidden" name="student_id" value='<?php echo $user_id; ?>'>
	    <select id="iqkcg-search" class="form-control selectpicker">
	    	<option value="26133">AP Chemistry</option>
		<option selected="" value="27726">Algebra 1</option>
		<option value="29260">Organic chemistry</option>
		<option value="38322">Pre Calculus</option>
		<option value="43418">AP Biology</option>
		<option value="56922">AP Calculus AB</option>
		<option value="72527">Honors Chemistry</option>
		<option value="78696">Honors Biology</option>
		<option value="100513">AP Calculus BC</option>
		<option value="101205">Conceptual Physics</option>
		<option value="121073">AP Physics 2</option>
		<option value="121339">AP Physics C(Mechanics)</option>
		<option value="123625">AP Physics 1</option>
	    </select>
	<?php }?>
	</div>
	<div class="clearfix"></div><br/>
	<!--<?php if(!empty($iqkcgChart) ) { ?>-->
    	<div class="iqkcg_chart_area"><div id="iqkcg-chart"></div></div>
	<!--<?php } else { ?>
		<center><h4>No Data</h4></center>
	<?php } ?>-->
</div>

