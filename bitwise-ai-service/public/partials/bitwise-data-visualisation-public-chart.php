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

if(isset($student_reports_chart) && $student_reports_chart) { 

if(isset($_GET['id']))
{
        $sid = $_GET['id'];
}
else
{
        $sid = get_current_user_id();
}


?> 

<div class="row thumbnail bt_box_shadow">
<div style='text-align: center;' class="col-md-12 card-header"><h3>Student Activity Report - Time spent by Month</h3></div>
<!--    <div style="text-align:center;" class="col-md-6 pull-left"> 
        <h3>Course Name</h3>
    </div> -->

    <div class="col-md-3 pull-right">
    <?php if(!empty($results)){?>
    <input id="student_id" type="hidden" name="student_id" value='<?php echo $sid ?>'>
        <select id="searchStudent" class="form-control selectpicker">

        <option value="all">All</option>
        <?php 
            foreach ($get_datas as $data) {
                echo '<option value='.$data->ID.'>'.$data->post_title.'</option>';
        }
        ?>
        
        </select>
    <?php }?>
    </div>
    <div class="clearfix"></div>

    <?php if($results && $forceY){?>
        <span class="chart_area"><div style='width:100%;height:500px;' id='chart1'><svg></svg></div></span>
    <?php }else{?>
        <span class="chart_area"><div style='width:100%;height:500px; text-align:center; padding-top:50px; font-size:20px;'><strong>No Data Found</strong></div></span>
    <?php }?>
</div>
<?php } ?>
