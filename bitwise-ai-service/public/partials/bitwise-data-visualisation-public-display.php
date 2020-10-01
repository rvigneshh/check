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


<style type="text/css">
 .snip1336 {
  font-family: 'Roboto', Arial, sans-serif;
  position: relative;
  overflow: hidden;
  margin: 10px;
  min-width: 230px;
  max-width: 315px;
  width: 100%;
  color: #ffffff;
  text-align: left;
  line-height: 1.4em;
  background-color: #337ab7;
}
.snip1336 * {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-transition: all 0.25s ease;
  transition: all 0.25s ease;
}
.snip1336 img {
  max-width: 100%;
  vertical-align: top;
  opacity: 0.85;
}
.snip1336 figcaption {
  width: 100%;
  background-color: #337ab7;
  padding: 25px;
  position: relative;
}
.snip1336 figcaption:before {
  position: absolute;
  content: '';
  bottom: 100%;
  left: 0;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 55px 0 0 400px;
  border-color: transparent transparent transparent #337ab7;
}
.snip1336 figcaption a {
  padding: 5px;
  border: 1px solid #ffffff;
  color: #ffffff;
  font-size: 0.7em;
  text-transform: uppercase;
  margin: 10px 0;
  display: inline-block;
  opacity: 0.65;
  width: 100%;
  text-align: center;
  text-decoration: none;
  font-weight: 600;
  letter-spacing: 1px;
}
.snip1336 figcaption a:hover {
  opacity: 1;
}
.snip1336 .profile {
  border-radius: 50%;
  position: absolute;
  bottom: 100%;
  left: 25px;
  z-index: 1;
  max-width: 90px;
  opacity: 1;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
}
.snip1336 .follow {
  margin-right: 4%;
  border-color: #2980b9;
  color: #2980b9;
}
.snip1336 h4 {
  margin: 0 0 5px;
  font-weight: 300;
  color: #fff;
}
.snip1336 h4 span {
  display: block;
  font-size: 0.5em;
  color: #2980b9;
}
.snip1336 p {
  margin: 0 0 10px;
  font-size: 0.8em;
  letter-spacing: 1px;
  opacity: 0.8;
}


.uo-t-row {
    width: 100%;
    clear: both;
    min-height: 0px;
    display: block;
    margin-bottom: 0px !important;
}

.entry-content h3 {
    font-size: 18px;
    line-height: 1.8;
    color: #ffffff !important;
}

.entry-content h3 {
   margin-bottom: 0px !important;
       font-size: 15px !important;
}

#uo-t-print-button {
margin-bottom: 0px !important;
}

.entry-content table, .comment-content table {
    margin: 10px 0px 0px 0px;
}

#tp_spent{
  margin-bottom: 15px;
}



</style>

<?php
$user = wp_get_current_user();

 if(isset($student_activity) && $student_activity) {
    if(!in_array('subscriber',$user->roles)) {
  ?>




    <div class="row">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url();?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo site_url();?>/student-reports/"><i class="fa fa-users" aria-hidden="true"></i> Student Reports</a></li>
            <li class="breadcrumb-item active"><i class="fa fa-file" aria-hidden="true"></i> Report</li>
        </ol>
    </div>
    <?php } ?>
   <div class="row thumbnail bt_box_shadow">
    <div class="well-header" style="color: black;font-weight: normal;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)"> 
      <div class="card-header">
    </div>
<div class="card" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            margin-left: 30px;
                width: 200px;float:left;
                margin-top:30px;text-align: center;">
  <img src="<?php echo get_avatar_url($user_id,array('size' => 128));?>" alt="" class="img-rounded img-responsive card-img-top" style="padding: 30px;margin:0 auto">
    <p class="card-text"><?php echo ucfirst(get_user_meta($user_id, 'first_name', true).' '.get_user_meta($user_id, 'last_name', true)); ?></p>
    <p>No. of Logins : <?php if(empty($get_student_summary['get_login_count'])){
                            echo '0';
                        }else{
                             echo $get_student_summary['get_login_count']; 
                        }?></p>




</div>
<div class="cardy" style="margin-top: 50px">
  <div class="card-body">
    <div style="float: right;margin-right: 50px">
        <h3><p class="card-text">&emsp;<i class="fa fa-trophy"></i>&nbsp;<?php echo get_the_author_meta('reg_grade', $user_id); ?></p></h3>
    </div>

    <p class="card-title">&emsp;<i class="fa fa-calendar" aria-hidden="true"></i>&emsp;Registration Date : <cite title="Source Title"> <?php echo date("jS F, Y", strtotime( get_the_author_meta('user_registered', $user_id) ) );?></cite></p>
    <p class="card-text">&emsp;<i class="fa fa-envelope" aria-hidden="true"></i>&emsp;<?php echo get_the_author_meta('user_email', $user_id); ?></p>

  </div>
  <div>
        <div class="col-md-3 col-sm-3">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading bt-bg-cyan">
                        <i class="fa fa-hourglass fa-fw fa-3x" aria-hidden="true"></i>
                    </div>
                </a>
                <div class="circle-tile-content bt-bg-cyan">
                    <div class="circle-tile-description">
                        Maximum Time Spent
                    </div>
                    <div class="circle-tile-number">
                         <?php if(empty($get_student_summary['result']['max_time_spent'])){
                            echo '00:00:00';
                        }else{
                            echo gmdate("H:i:s", $get_student_summary['result']['max_time_spent']);
                        }?>
                        <span id="sparklineB"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading bt-bg-green">
                        <i class="fa fa-hourglass-end fa-fw fa-3x" aria-hidden="true"></i>
                    </div>
                </a>
                <div class="circle-tile-content bt-bg-green">
                    <div class="circle-tile-description">
                        Minimum Time Spent
                    </div>
                    <div class="circle-tile-number">
                     <?php
                        if(empty($get_student_summary['result']['min_time_spent'])){
                            echo '00:00:00';
                        }else{
                            echo gmdate("H:i:s", $get_student_summary['result']['min_time_spent']);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading bt-bg-orange">
                        <i class="fa fa-calculator fa-fw fa-3x" aria-hidden="true"></i>
                    </div>
                </a>
                <div class="circle-tile-content bt-bg-orange">
                    <div class="circle-tile-description text-white">
                        Average Time Spent
                    </div>
                    <div class="circle-tile-number text-white">
                        <?php if(empty($get_student_summary['get_login_count'])){
                            echo '00:00:00';
                        }else{
                            echo gmdate("H:i:s", $get_student_summary['result']['total_time_spent']/$get_student_summary['get_login_count']);

                        }
                        ?>
                        <span id="sparklineC"></span>
                    </div>
                </div>
            </div>
        </div>
        </div>



</div>



        <div class="clearfix"></div>
       </div>


    <div class="clearfix"></div>

    <?php
        if(in_array('subscriber',$user->roles)) {
          echo do_shortcode( ' [ld_profile order="asc"] ' );
        } else {
          echo do_shortcode( ' [bit_student_transcript user-id="'.$user_id.'"] ' );
        }
    ?>
    </div>
<?php }

if(isset($childrens_profile) && $childrens_profile){
	if(!empty($student_details)) {
?>
<div class="alert alert-danger" style="padding:10px;">
	<strong>*** ALERT *** You are currently logged in as Parent. Courses are only visible in the Student account.
	</strong>
</div>
<?php } else { ?>
<div class="alert alert-danger" style="padding:3px;">
	<strong>*** ALERT *** You are currently logged in as Parent. Courses are only visible in the Student account.<br/><br/>
	<span style="color:#000099;">After purchasing a subscription, please</span> ADD <span style="color:#000099;">your child by clicking the <a href="'.site_url('manage-users').'" style="text-decoration: underline !important;color:#a94442 !important;">Students</a> tab on the menu bar. Please make sure that you do</span> NOT <span style="color:#000099;">reuse your Parent email address for your child\'s account. These must be different email addresses to complete the Student registration process.</span>
	</strong>
</div>	
<?php } ?>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo site_url();?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i> Home</a></li>
        <li class="breadcrumb-item active"><i class="fa fa-users" aria-hidden="true"></i> Student Reports</li>
    </ol>
</div>
<!-- <div class="container"> -->
    <!-- <div class="row"> -->
    <!-- <div class="row bt_box_shadow"> -->
<?php
if(!empty($student_details)) {
    foreach ($student_details as $student_detail) {
?>
<!--             <div class="col-xs-12 col-sm-6 col-md-6 ">
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                           <img src="<?php echo get_avatar_url($student_detail->ID,array('size' => 512));?>" alt="" class="img-rounded img-responsive" />
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-8">
                            <h4>
                                <?php echo ucfirst( get_user_meta( $student_detail->data->ID, 'first_name', true ) ); ?>
                            </h4>
                            <small>Registered on <cite title="Source Title"> <?php echo date("jS F, Y", strtotime( get_the_author_meta('user_registered', $student_detail->data->ID) ) ); ?></cite></small>
                            <p><span class="dashicons dashicons-email-alt"></span> <?php echo $student_detail->data->user_email; ?></p>
                            <p><span class="dashicons dashicons-dashboard"></span> Quiz Attempt : <?php echo count($quiz_attempt[$student_detail->data->ID]); ?></p>
                        </div>
                    </div>
                </div>
            </div> -->
<!--
    <ul class="row" style="text-align: center !important; list-style-type:none;">
      <li class="col-12 col-md-6 col-lg-3 practice-area" style="border: 2px solid #0122436b; margin: 0 0 0 20px;">
          <div class="cnt-block equal-hight" style="height: 349px;">
            <figure><img src="<?php echo get_avatar_url($student_detail->ID,array('size' => 512));?>" class="img-responsive img-rounded" style="border-radius: 50%;    display: block; margin-left: auto; margin-right: auto;width: 55%;" alt=""></figure>
            <h3 style="color:#367a8f !important; text-align: center; margin: 0px 0;"><?php echo ucfirst( get_user_meta( $student_detail->data->ID, 'first_name', true ) ); ?></h3>
            <small>Registered on <cite title="Source Title"> <?php echo date("jS F, Y", strtotime( get_the_author_meta('user_registered', $student_detail->data->ID) ) ); ?></cite></small>
            <p class="fs" style="margin: 0 0 0px;"><span class="dashicons dashicons-email-alt"></span> <?php echo $student_detail->data->user_email; ?></p>
            <p class="fs" style="margin: 0 0 0px;"><span class="dashicons dashicons-dashboard"></span> Quiz Attempt : <?php echo count($quiz_attempt[$student_detail->data->ID]); ?></p>
          </div>
      </li>



    </ul> -->


<!--<div class="profile-block col-md-3">
  <div class="panel text-center">
    <div class="user-heading" style="border: 2px solid #999;
    padding: 10px; background: rgb(60, 122, 144);"> <img style="border-radius: 50%;
    height: 106px;border: 6px solid #0122439e;" src="<?php echo get_avatar_url($student_detail->ID,array('size' => 512));?>" alt="" title=""></a>
      <h1 style="    margin: 10px 0;" ><?php echo ucfirst( get_user_meta( $student_detail->data->ID, 'first_name', true ) ); ?></h1>
      <small style="color: #fff;">Registered on <cite title="Source Title"> <?php echo date("jS F, Y", strtotime( get_the_author_meta('user_registered', $student_detail->data->ID) ) ); ?></cite></small>
      <p style="margin: 0 0 1px;color: #fff;"><span class="dashicons dashicons-email-alt"></span> <?php echo $student_detail->data->user_email; ?></p>
      <p style="color: #fff;"><span class="dashicons dashicons-dashboard"></span> Quiz Attempt : <?php echo count($quiz_attempt[$student_detail->data->ID]); ?></p>
    </div>

  </div>
</div>
  -->
<!--
        <div class="col-md-3">
            <div class="our-team">
                <img src="<?php echo get_avatar_url($student_detail->ID,array('size' => 512));?>" alt="team member" class="img-responsive">
                <div class="team-content">
                    <h3 class="name"><?php echo ucfirst( get_user_meta( $student_detail->data->ID, 'first_name', true ) ); ?></h3>
                    <span class="post"> Registered on<?php echo date("jS F, Y", strtotime( get_the_author_meta('user_registered', $student_detail->data->ID) ) ); ?></span>
                    <p style="color: #fff;"><span class="dashicons dashicons-dashboard"></span> Quiz Attempt : <?php echo count($quiz_attempt[$student_detail->data->ID]); ?></p>

                    <button class="spin" id="spin">
  <span>View Report</span>
  <span>
    <svg viewBox="0 0 24 24">
      <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
    </svg>
  </span>
</button>
                </div>
            </div>
        </div> -->
   <figure class="snip1336 col-md-3">
  <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/profile.jpg';?>" alt="profile" />
  <figcaption>
    <img src="<?php echo get_avatar_url($student_detail->ID,array('size' => 512));?>" alt="profile-sample4" class="profile" />
    <h4><?php echo ucfirst( get_user_meta( $student_detail->data->ID, 'first_name', true ) ); ?></h4>

     <span class="post"> Registered on <?php echo date("jS F, Y", strtotime( get_the_author_meta('user_registered', $student_detail->data->ID) ) ); ?></span>
                    <p style="color: #fff;"><span class="dashicons dashicons-dashboard"></span> Quiz Attempt : <?php echo count($quiz_attempt[$student_detail->data->ID]); ?></p>
    <a href="<?php echo site_url();?>/student-reports/report/?id=<?php echo $student_detail->ID;?>" class="info">View STEM Report</a>
    <a href="<?php echo site_url();?>/iq-report/?id=<?php echo $student_detail->ID;?>" class="info">View IQ Report</a>
  </figcaption>
</figure>







    <?php
    }
}else{
    echo '<div>No Data Found</div>';
}
?>
    <!-- </div> -->
    <!-- </div> -->

</div>
<?php
}
?>
