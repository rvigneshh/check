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
<?php

    $user = wp_get_current_user();

    if(in_array('subscriber',$user->roles)) { ?>
    <div class="row thumbnail bt_box_shadow">
        <div class="col-md-12">
            <?php echo do_shortcode( ' [uo_transcript] ' );  ?>
        </div>
    </div>
    <?php 
    }

if( isset($single_report) && $single_report) {
?>  
<span>
    <table class='table table-striped table-responsive table-bordered bt_box_shadow'>
        <tr>
            <th colspan='2' class='bt-bg-green font-bold' style='text-align:center;'>MOST TIME SPENT - <?php echo $user_name; ?></th>    
        </tr>
        <tr>
            <th style='text-align:center;'><strong>Topic</strong></th>
            <th style='text-align:center;'><strong>Time</strong></th>
        </tr>
        <?php foreach($courses as $course){ ?>

        <tr>
            <!-- <td style='padding-left:10px;padding-right:10px;'>post_title</td> -->
            <td style='padding-left:10px;padding-right:10px;'><?php echo $course['post_title']; ?></td>
            <!-- <td style='padding-right:10px;'>time_spent<span style='color:grey; font-size:10px;'>(HH:MM:SS)</span> </td> -->
            <td style='padding-right:10px;'><?php echo gmdate("H:i:s", $course['time_spent']); ?><span style='color:grey; font-size:10px;'>(HH:MM:SS)</span> </td>
        </tr>
    <?php } ?>
    </table>
</span>
<?php
} 
if(isset($user_visited) && $user_visited){
?>
	<div class='bt-card bt-body_1 bt-bg-orange'>
		<div class='bt-card-body'>
		<div class='font-bold bt-m-b--35'>MOST VISITED - <?php echo $user_name; ?></div>
			<ul class='bt-dashboard-stat-list'>
		<?php foreach ($get_details as $data) {?>
			<li class='bt-li-pd'><?php echo $data['post_title'];?><span class='pull-right'><b><?php echo $data['visited'];?></b><small> Vists</small></span></li>
		<?php } ?>		
   	</ul></div></div>
<?php
}
?>