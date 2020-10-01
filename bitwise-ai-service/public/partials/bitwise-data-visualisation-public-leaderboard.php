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
$get_user_ids = array();
$ids = array();
$user_ids = array();
$usr_ids = array();
$roles = wp_get_current_user()->roles;
$redis = new RedisCache();

?>
<div class="row thumbnail bt_box_shadow">
<div style='text-align:center;' class="col-md-12 card-header bt-hd"><h3><strong>LEADERBOARD</strong></h3></div>
<div class= "row">
<div class="col-md-3 pull-right">
    <?php //if(!empty($results)){?>
    <!-- <input id="student_id" type="hidden" name="student_id" value=''> -->
      <select id="lb_search" class="form-control selectpicker lb_pd">
        <option value="all">All</option>
        <?php foreach($get_user_courses as $get_user_course){ ?>
          <option value="<?php echo $get_user_course;?>"><?php echo get_the_title($get_user_course)?></option>   
        <?php }?>
      </select>
    <?php //}?>
    </div>
</div>    
<div class="clearfix"></div>
<div class="row">
<div class="col-md-6">
<div class="row bt-margin-ldb">
      <div class="panel panel-default widget">
          <div class="panel-heading tb-head text-center bt-ld">
              <h3 class="panel-title">
                  <strong>MOST TOPICS COMPLETED</strong></h3>
          </div>
          <div class="panel-body">
              <ul class="list-group bt-lb" id="lb-topic-completed">
          <?php  

            foreach($get_data['leaderboard']['mostTopicCompleted']['all']['result'] as $get_detail){
                  $get_user_ids[] =  $get_detail["user_id"];
                  $user_info = get_userdata($get_detail["user_id"]);

                  if(in_array('group_leader',$roles)) {
                    $style = in_array($get_detail["user_id"], $student_id) ? "color:blue;" : ""; ?>     
                   
                          <li class="list-group-item">
                              <div class="row" style="<?php echo $style; ?>">
                                  <div class="col-md-2">
                                      <img src="<?php echo get_avatar_url($get_detail["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                                  <div class="col-md-9">
                                      <div>
                                         <label>Name: </label> <?php echo ucfirst($user_info->display_name); ?></br>
                                         <label>Topics Completed: </label> <?php echo ($get_detail['count'] ? $get_detail['count'] : 0); ?></br>
                                      </div>
                                  </div>
                              </div>
                          </li>
                    <?php 

                  } else{
                    if($get_detail["user_id"] == $current_user_id){ 
                    ?>  
                    <li class="list-group-item">
                      <div class="row" style="color:blue;">
                        <div class="col-md-2">
                          <img src="<?php echo get_avatar_url($get_detail["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                            <div class="col-md-9">
                              <div>
                                 <label>Name: </label> <?php echo ucfirst($user_info->display_name); ?></br>
                                 <label>Topics Completed: </label> <?php echo ($get_detail['count'] ? $get_detail['count'] : 0); ?></br>
                              </div>
                            </div>
                        </div>
                    </li>
                <?php  
                  }else{ ?>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col-md-2">
                          <img src="<?php echo get_avatar_url($get_detail["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                            <div class="col-md-9">
                              <div>
                                 <label>Name: </label> <?php echo ucfirst($user_info->display_name); ?></br>
                                 <label>Topics Completed: </label> <?php echo ($get_detail['count'] ? $get_detail['count'] : 0); ?></br>
                              </div>
                            </div>
                        </div>
                    </li>
                 <?php }
                }

              }
              $k = count($get_user_ids);
              
              if(in_array('group_leader', $roles)){

                foreach($student_details as $student_detail){
                  $current_user_id = $student_detail->ID;

                  if(!in_array($current_user_id,$get_user_ids)){
                    $k = $k + 1;  
                    $get_json = json_decode($redis->getValue($current_user_id),true);
                    $get_user_data = $get_json['leaderboard']['mostTopicCompleted']['all'][$current_user_id];
                   
                    $user_detail = get_userdata($current_user_id); ?>
                      <li class="list-group-item">
                        <div class="row"  style="color:blue;">
                            <div class="col-md-2">
                                <img src="<?php echo get_avatar_url($get_detail["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                            <div class="col-md-9">
                                <div>
                                    <label>Name: </label> <?php echo ucfirst($user_detail->display_name) ?></br>
                                    <label>Topics Completed: </label> <?php echo ($get_user_data['count']? $get_user_data['count'] : 0)?>
                                </div>                     
                            </div>
                        </div>
                    </li>
                <?php  }
                } 
              }else{

                if(!in_array($current_user_id,$get_user_ids)){
                    $k = $k + 1;  
                    $get_user_data = $get_data['leaderboard']['mostTopicCompleted']['all'][$current_user_id];
                    $user_detail = get_userdata($current_user_id); ?>
                      <li class="list-group-item">
                        <div class="row"  style="color:blue;">
                            <div class="col-md-2">
                                <img src="<?php echo get_avatar_url($get_detail["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                            <div class="col-md-9">
                                <div>
                                    <label>Name: </label> <?php echo ucfirst($user_detail->display_name) ?></br>
                                        <label>Topics Completed: </label> <?php echo ($get_user_data['count']? $get_user_data['count'] : 0)?>
                                </div>                     
                            </div>
                        </div>
                    </li>
              <?php  } 
              }

            $show = $display_count - $k;
            if($show !== 0){
            for ($i=0; $i < $show; $i++) { ?>
                <li class="list-group-item">
                      <div class="row">
                          <div class="col-md-2">
                              <img src="<?php echo get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg');?>" class="img-circle img-responsive" alt="" /></div>
                          <div class="col-md-9">
                              <div>
                                  <label>Name: </label> NIL </br>
                                      <label>Topics Completed: </label> NIL
                              </div>                     
                          </div>
                      </div>
                  </li>
           <?php 
            }
            }

            ?>
                </ul>
          </div>
      </div>
  </div>
</div>
<div class="col-md-6">
         <div class="row bt-margin-ldb">
            <div class="panel panel-default widget">
                <div class="panel-heading tb-head text-center bt-ld">
                    <h3 class="panel-title">
                        <strong>MOST TIME SPENT</strong></h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group bt-lb"  id="lb-time-spent">
                    <?php  
                    foreach($get_data['leaderboard']['mostTimeSpent']['all']['result'] as $mostTimeSpent){
                        $ids[] =  $mostTimeSpent["user_id"];
                        $user_info = get_userdata($mostTimeSpent["user_id"]);
                         
                        if(in_array('group_leader',$roles)) {
                            $style = in_array($mostTimeSpent["user_id"], $student_id) ? "color:blue;" : "";
                            // if($mostTimeSpent["user_id"] == $current_user_id){ ?>
                            <li class="list-group-item">
                                <div class="row" style="<?php echo $style;?>">
                                    <div class="col-md-2">
                                        <img src="<?php echo get_avatar_url($mostTimeSpent["user_id"])?>" class="img-circle img-responsive" alt="" /></div>
                                    <div class="col-md-9">
                                        <div>
                                            <label>Name: </label> <?php echo ucfirst($user_info->display_name); ?></br>
                                            <label>Time Spent: </label> <?php echo (learndash_seconds_to_time($mostTimeSpent["time_spent"]) ?learndash_seconds_to_time($mostTimeSpent["time_spent"]) : 0)?>
                                        </div>                     
                                    </div>
                                </div>
                            </li>
                  <?php }else{ 
                          if($mostTimeSpent["user_id"] == $current_user_id){ ?>    
                              <li class="list-group-item">
                                <div class="row" style="color:blue;">
                                  <div class="col-md-2">
                                    <img src="<?php echo get_avatar_url($mostTimeSpent["user_id"])?>" class="img-circle img-responsive" alt="" /></div>
                                    <div class="col-md-9">
                                      <div>
                                        <label>Name: </label> <?php echo ucfirst($user_info->display_name); ?></br>
                                        <label>Time Spent: </label> <?php echo (learndash_seconds_to_time($mostTimeSpent["time_spent"]) ?learndash_seconds_to_time($mostTimeSpent["time_spent"]) : 0)?>
                                      </div>                     
                                    </div>
                                  </div>
                              </li>
                         <?php } else{ ?>
                              <li class="list-group-item">
                                <div class="row">
                                  <div class="col-md-2">
                                    <img src="<?php echo get_avatar_url($mostTimeSpent["user_id"])?>" class="img-circle img-responsive" alt="" /></div>
                                    <div class="col-md-9">
                                      <div>
                                        <label>Name: </label> <?php echo ucfirst($user_info->display_name); ?></br>
                                        <label>Time Spent: </label> <?php echo (learndash_seconds_to_time($mostTimeSpent["time_spent"]) ?learndash_seconds_to_time($mostTimeSpent["time_spent"]) : 0)?>
                                      </div>                     
                                    </div>
                                  </div>
                              </li>

                          <?php }
                        }
                    } 

                    $r = count($ids);

                    if(in_array('group_leader', $roles)){
                        foreach($student_details as $student_detail){
                          $current_user_id = $student_detail->ID;

                            if(!in_array($current_user_id,$ids)){
                            $r = $r + 1 ;
                            $get_json = json_decode($redis->getValue($current_user_id),true);
                            $get_data = $get_json['leaderboard']['mostTimeSpent']['all'][$current_user_id];
                            $user_data = get_userdata($current_user_id); ?>
                            <li class="list-group-item">
                                <div class="row"  style="color:blue;">
                                    <div class="col-md-2">
                                        <img src="<?php echo get_avatar_url($current_user_id)?>" class="img-circle img-responsive" alt="" /></div>
                                    <div class="col-md-9">
                                        <div>
                                            <label>Name: </label> <?php echo ucfirst($user_data->display_name) ?></br>
                                            <label>Time Spent: </label> <?php echo ($get_data["time_spent"] ? learndash_seconds_to_time($get_data["time_spent"]) : 0) ?>
                                        </div>                     
                                    </div>
                                </div>
                            </li>
                        <?php  }
                        }
                    }else{
                      if(!in_array($current_user_id,$ids)){
                       
                          $r = $r + 1 ;
                          $get_data = $get_data['leaderboard']['mostTimeSpent']['all'][$current_user_id];
                          $user_data = get_userdata($current_user_id); ?>
                          <li class="list-group-item">
                              <div class="row"  style="color:blue;">
                                  <div class="col-md-2">
                                      <img src="<?php echo get_avatar_url($current_user_id)?>" class="img-circle img-responsive" alt="" /></div>
                                  <div class="col-md-9">
                                      <div>
                                          <label>Name: </label> <?php echo ucfirst($user_data->display_name) ?></br>
                                          <label>Time Spent: </label> <?php echo ($get_data["time_spent"] ? learndash_seconds_to_time($get_data["time_spent"]) : 0) ?>
                                      </div>                     
                                  </div>
                              </div>
                          </li>
                     <?php }
                    }
                    ?>         
                    <?php 
                        $display = $display_count - $r;

                         if($display !== 0){
                        for ($i=0; $i < $display; $i++) { ?>
                            <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-md-2">
                                          <img src="<?php echo get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg');?>" class="img-circle img-responsive" alt="" /></div>
                                      <div class="col-md-9">
                                          <div>
                                              <label>Name: </label> NIL </br>
                                                  <label>Time Spent: </label> NIL
                                          </div>                     
                                      </div>
                                  </div>
                              </li>
                              <?php } 
                              }?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-6">
<div class="row bt-margin-ldb">
    <div class="panel panel-default widget">
        <div class="panel-heading tb-head text-center bt-ld">
            <h3 class="panel-title">
                <strong>MOST BADGES EARNED</strong></h3>
        </div>
        <div class="panel-body">
            <ul class="list-group bt-lb" id="lb-badges-earned">
            <?php
            // error_log(print_r($get_badges_earned,true));
            foreach($get_badges_earned as $get_badges){
                $user_ids[] =  $get_badges["user_id"];
                $user_info = get_userdata($get_badges["user_id"]);
                
                if(in_array('group_leader',$roles)) {

                   $style = in_array($get_badges["user_id"], $student_id) ? "color:blue;" : "";
                // if($get_badges['badges_earned'] != NULL){?>
                         <li class="list-group-item">
                            <div class="row" style="<?php echo $style;?>">
                                <div class="col-md-2">
                                    <img src="<?php echo get_avatar_url($get_badges["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                                <div class="col-md-9">
                                    <div>
                                        <label>Name: </label> <?php echo ucfirst($user_info->display_name);?></br>
                                        <label>Badges Earned: </label> <?php echo ($get_badges["badges_earned"]? $get_badges["badges_earned"] : 0);?>
                                    </div>                     
                                </div>
                            </div>
                        </li>           

                    <?php }else{
                          if($get_badges["user_id"] == $current_user_id){ ?>
                            <li class="list-group-item">
                              <div class="row" style="color:blue;">
                                <div class="col-md-2">
                                  <img src="<?php echo get_avatar_url($get_badges["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                                  <div class="col-md-9">
                                    <div>
                                        <label>Name: </label> <?php echo ucfirst($user_info->display_name);?></br>
                                        <label>Badges Earned: </label> <?php echo ($get_badges["badges_earned"]? $get_badges["badges_earned"] : 0);?>
                                    </div>                     
                                  </div>
                              </div>
                            </li>  

                          <?php }else{ ?>
                              <li class="list-group-item">
                                  <div class="row">
                                    <div class="col-md-2">
                                      <img src="<?php echo get_avatar_url($get_badges["user_id"]);?>" class="img-circle img-responsive" alt="" /></div>
                                      <div class="col-md-9">
                                        <div>
                                            <label>Name: </label> <?php echo ucfirst($user_info->display_name);?></br>
                                            <label>Badges Earned: </label> <?php echo ($get_badges["badges_earned"]? $get_badges["badges_earned"] : 0);?>
                                        </div>                     
                                      </div>
                                  </div>
                                </li>
                        <?php }   
                    }
            }
           
            $j = count($user_ids);

            if(in_array('group_leader', $roles)){
              foreach($student_details as $student_detail){
                $current_user_id = $student_detail->ID;
                if(!in_array($current_user_id,$user_ids)){
                  $j = $j + 1; 
                  $get_json = json_decode($redis->getValue($current_user_id),true);
                  $badges_earned_user = $get_json['leaderboard']['mostBadgesEarned']['all'][$current_user_id];
                  $user_inf = get_userdata($current_user_id);
                  ?>
                  <li class="list-group-item">
                      <div class="row"  style="color:blue;">
                          <div class="col-md-2">
                              <img src="<?php echo get_avatar_url($current_user_id);?>" class="img-circle img-responsive" alt="" /></div>
                          <div class="col-md-9">
                              <div>
                                  <label>Name: </label> <?php echo ucfirst($user_inf->display_name);?></br>
                                  <label>Badges Earned: </label> <?php echo ( $badges_earned_user['badges_earned']?  $badges_earned_user['badges_earned'] : 0);?>
                              </div>                     
                          </div>
                      </div>
                  </li>
              <?php 
                  }
              }
            }else{

               if(!in_array($current_user_id,$user_ids)){

                  $j = $j + 1; 
                  $badges_earned_user = $get_data['leaderboard']['mostBadgesEarned']['all'][$current_user_id];
                  $user_inf = get_userdata($current_user_id);
                  ?>
                  <li class="list-group-item">
                      <div class="row"  style="color:blue;">
                          <div class="col-md-2">
                              <img src="<?php echo get_avatar_url($current_user_id);?>" class="img-circle img-responsive" alt="" /></div>
                          <div class="col-md-9">
                              <div>
                                  <label>Name: </label> <?php echo ucfirst($user_inf->display_name);?></br>
                                  <label>Badges Earned: </label> <?php echo ( $badges_earned_user['badges_earned']?  $badges_earned_user['badges_earned'] : 0);?>
                              </div>                     
                          </div>
                      </div>
                  </li>
              <?php }
            }
             $shows = $display_count - $j;

             if($shows !== 0){
            for ($i=0; $i < $shows; $i++) { ?>
                <li class="list-group-item">
                      <div class="row">
                          <div class="col-md-2">
                              <img src="<?php echo get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg');?>" class="img-circle img-responsive" alt="" /></div>
                          <div class="col-md-9">
                              <div>
                                  <label>Name: </label> NIL </br>
                                      <label>Badges Earned: </label> NIL
                              </div>                     
                          </div>
                      </div>
                  </li>
                  <?php } 
                  }?>
            </ul>
        </div>

    </div>
</div>
</div>  
   <div class="col-md-6">
      <div class="row bt-margin-ldb">
      <div class="panel panel-default widget">
          <div class="panel-heading tb-head text-center bt-ld">
              <h3 class="panel-title ">
                  <strong>MOST QUESTIONS ANSWERED</strong></h3>
          </div>
          <div class="panel-body">
              <ul class="list-group bt-lb" id="lb-ques-answered"><?php
              foreach($get_mostQuesAnswered as $user_detail){

                  $usr_ids[] =  $user_detail["user_id"];
                  $get_userdata = get_userdata($user_detail["user_id"]);
                 
                  if(in_array('group_leader',$roles)) {
                    $style = in_array($user_detail["user_id"], $student_id) ? "color:blue;" : "";?>
                      
                     <li class="list-group-item">
                          <div class="row" style="<?php echo $style;?>">
                              <div class="col-md-2">
                                  <img src="<?php echo get_avatar_url($user_detail["user_id"])?>" class="img-circle img-responsive" alt="" /></div>
                              <div class="col-md-9">
                                  <div>
                                     <label>Name: </label> <?php echo ucfirst($get_userdata->display_name) ?></br>
                                     <label>Answered: </label> <?php echo ($user_detail['total_questions'] ? $user_detail['correct_ans'] : 0) ?> /
                                     <label>Total Questions: </label> <?php echo ($user_detail['total_questions'] ? $user_detail['total_questions'] : 0) ?>
                                  </div>
                              </div>
                          </div>
                      </li>
                  <?php 
                  }else{ 
                    if($user_detail["user_id"] == $current_user_id){ ?>
                      <li class="list-group-item">
                        <div class="row" style="color:blue;">
                          <div class="col-md-2">
                            <img src="<?php echo get_avatar_url($user_detail["user_id"])?>" class="img-circle img-responsive" alt="" /></div>
                              <div class="col-md-9">
                                <div>
                                   <label>Name: </label> <?php echo ucfirst($get_userdata->display_name) ?></br>
                                   <label>Answered: </label> <?php echo ($user_detail['total_questions'] ? $user_detail['correct_ans'] : 0) ?> /
                                   <label>Total Questions: </label> <?php echo ($user_detail['total_questions'] ? $user_detail['total_questions'] : 0) ?>
                                </div>
                            </div>
                          </div>
                        </li>
                    <?php }else{ ?>
                        <li class="list-group-item">
                        <div class="row" >
                          <div class="col-md-2">
                            <img src="<?php echo get_avatar_url($user_detail["user_id"])?>" class="img-circle img-responsive" alt="" /></div>
                              <div class="col-md-9">
                                <div>
                                   <label>Name: </label> <?php echo ucfirst($get_userdata->display_name) ?></br>
                                   <label>Answered: </label> <?php echo ($user_detail['total_questions'] ? $user_detail['correct_ans'] : 0) ?> /
                                   <label>Total Questions: </label> <?php echo ($user_detail['total_questions'] ? $user_detail['total_questions'] : 0) ?>
                                </div>
                            </div>
                          </div>
                        </li>
                    <?php }
                  }
                }

              $n = count($usr_ids);    

              if(in_array('group_leader', $roles)){
                foreach($student_details as $student_detail){
                  $current_user_id = $student_detail->ID;
                   if(!in_array($current_user_id,$usr_ids)){

                      $n = $n + 1 ;
                      $get_json = json_decode($redis->getValue($current_user_id),true);
                      $get_user_data =  $get_json['leaderboard']['mostQuesAnswered']['all'][$current_user_id];
                      $user_data = get_userdata($current_user_id); ?>

                      <li class="list-group-item">
                        <div class="row"  style="color:blue;">
                            <div class="col-md-2">
                                <img src="<?php echo get_avatar_url($current_user_id)?>" class="img-circle img-responsive" alt="" /></div>
                            <div class="col-md-9">
                                <div>
                                    <label>Name: </label> <?php echo ucfirst($user_data->display_name) ?></br>
                                    <label>Answered: </label> <?php echo ($get_user_data['ques_count'] ? $get_user_data['correct_ans'] : 0)?> /
                                     <label>Total Questions: </label> <?php echo ($get_user_data['ques_count'] ? $get_user_data['ques_count'] : 0) ?>
                                </div>                     
                            </div>
                        </div>
                      </li>

                  <?php }

                  } 
                }else{
                     if(!in_array($current_user_id,$usr_ids)){

                      $n = $n + 1 ;
                      $get_user_data =  $get_data['leaderboard']['mostQuesAnswered']['all'][$current_user_id];
                      $user_data = get_userdata($current_user_id); ?>

                      <li class="list-group-item">
                        <div class="row"  style="color:blue;">
                            <div class="col-md-2">
                                <img src="<?php echo get_avatar_url($current_user_id)?>" class="img-circle img-responsive" alt="" /></div>
                            <div class="col-md-9">
                                <div>
                                    <label>Name: </label> <?php echo ucfirst($user_data->display_name) ?></br>
                                    <label>Answered: </label> <?php echo ($get_user_data['ques_count'] ? $get_user_data['correct_ans'] : 0)?> /
                                     <label>Total Questions: </label> <?php echo ($get_user_data['ques_count'] ? $get_user_data['ques_count'] : 0) ?>
                                </div>                     
                            </div>
                        </div>
                      </li>
                  <?php }
                }

                ?>
             
            <?php   
                  $displays = $display_count - $n;

                   if($displays !== 0){
                  for ($i=0; $i < $displays; $i++) { ?>
                      <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="<?php echo get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg');?>" class="img-circle img-responsive" alt="" /></div>
                                <div class="col-md-9">
                                    <div>
                                        <label>Name: </label> NIL </br>
                                            <label>Answered: </label> NIL / 
                                            <label>Total Questions: </label> NIL
                                    </div>                     
                                </div>
                            </div>
                        </li>
                        <?php } 
                        }?>
                  </ul>
              </div>

          </div>
      </div>
  </div>
</div>
</div>
</div>
</div>   



