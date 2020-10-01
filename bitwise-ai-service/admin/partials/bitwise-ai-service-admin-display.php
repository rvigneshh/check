<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.bitwise.academy
 * @since      1.0.0
 *
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div>

		<h1>bitWise - AI Service</h1><strong>Version <?php echo BITWISE_AI_SERVICE_VERSION; ?></strong>
		
		<div class="wrap">
	    
	       <div id="icon-themes" class="icon32"></div>
	       <?php settings_errors(); ?>
	        
			<h2 class="nav-tab-wrapper bitwise-tabs">
			    <a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=welcome" class="nav-tab nav-tab-active" id="welcome" data-tab-id="welcome">
			    	Welcome <span class="dashicons dashicons-admin-home"></span>
			    </a>
			    <a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=retention-score" class="nav-tab" id="retention-score" data-tab-id="retention-score">
			    	Retention Scores <span class="dashicons dashicons-id-alt"></span>
			    </a>
			    <a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=concept-mastery" class="nav-tab" id="session-report" 
			    	data-tab-id="session-report">
			    	Session report <span class="dashicons dashicons-id"></span>
			    </a>
				<a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=plugin-settings" class="nav-tab" id="plugin-settings" data-tab-id="plugin-settings">
			    	Settings <span class="dashicons  dashicons-admin-settings"></span>
			    </a>  
			</h2>

			<!--Welcome-->
			<div id="content-welcome" class="content content-tab-active">
				<h2>Welcome to bitWise AI Service</h2>
				<p class="about-text">Thanks for installing bitWise AI elearning service.</p>

					<ul class="services">
						<h3>Services</h3>
						<li>Concept Mastery</li>
						<li>Co.efficient of retention</li>
					</ul>
					<h4>Concept Mastery</h4>
					<p>To get the know the how much time spent by the student in the topic.</p>
					<h4>Co.efficient of Retention</h4>
					<p>To get the know the retention score of the student in the topic.</p>
			</div>
			<!--Welcome-->

			<!--Concept mastery-->
			<div id="content-session-report" class="content">
				<h3>Session report</h3>

				<table class="wp-list-table widefat fixed striped" id="sesion-list">

					<thead>
					<tr>
					<!--	<th>#</th>
						<th>User</th>
						<th>Course</th>
						<th>Topic</th>
						<th>Time spent(sec)</th>!--> 
						<th data-name="user_id">User_id</th>
						<th data-name="course_id">Course</th>
						<th data-name="post_id">Post_id</th>
						<th data-name="time_spent">Time_spent</th>
						<th data-name="cron_status">Cron_status</th>
						<th data-name="cron_updated_on">Cron_updated_on</th>
						<th data-name="created_on">Created_on</th>
					</tr>
					</thead>
					<tbody></tbody>

					<!--<?php if( count($mastery_results) ): ?>
						<?php foreach ($mastery_results as $result) { ?>
							<tr>
								<td>
									<?php echo $result['id']; ?>
								</td>
								<td>
									<?php echo ucfirst($result['user']); ?>
								</td>
								<td>
									<?php echo $result['course']; ?>
								</td>
								<td>
									<?php echo $result['topic']; ?>
								</td>
								<td>
									<?php echo $result['time_spent']; ?>
								</td>
							</tr>
						<?php } ?>

					
					<?php else: ?>
						<tr>
							<td colspan="5"><center><strong>No data available</strong></center></td>
						</tr>
					<?php endif; ?>
					</tbody> !-->
				</table>
			</div>
			<!--Concept mastery-->

			<!--Retention Score-->
			<div id="content-retention-score" class="content">
				<h3>Retention Score</h3>
				<table class="wp-list-table widefat fixed striped" id="retention-list">
					
					<thead>
					<tr>
					<!--	<th>#</th>
						<th>User</th>
						<th>Course id</th>
						<th>Retention score</th>
						<th>Retention category</th>
						<th>Status</th>
						<th>Updated on</th>   !-->
						<th data-name="id">User_id</th>   
						<th data-name="course_id">Course id</th>
						<th data-name="retention_score">Retention score</th>
						<th data-name="retention_category">Retention category</th>
						<th data-name="status">Status</th>
						<th data-name="created_on">Created on</th>
					</tr>
					</thead>

			<!--		<?php if( count($retention_results) ): ?>
						<?php foreach ($retention_results as $result) { ?>
							<tr>
								<td>
									<?php echo $result['id']; ?>
								</td>
								<td>
									<?php echo ucfirst($result['user']); ?>
								</td>
								<td>
									<?php echo $result['course']; ?>
								</td>
								<td>
									<?php echo $result['retention_score']; ?>
								</td>
								<td>
									<?php echo $result['retention_category']; ?>
								</td>
								<td>
									<?php echo $result['status']; ?>
								</td>
								<td>
									<?php echo date('m/d/Y h:m:s', strtotime($result['created_on'])); ?>
								</td>
							</tr>
						<?php } ?>
					<?php else: ?>
						<tr>
							<td colspan="7"><center><strong>No data available</strong></center></td>
						</tr>
					<?php endif; ?>     !-->


				<tbody>	</tbody>
				</table>
			</div>
		<!-- </div>
	</div>
		 -->	<!--Retention Score-->

			<!--Settings-->
			
 <div id="content-plugin-settings" class="content">

				<h2 class="nav-tab-wrapper bitwise-tabs-inner" id="settings">
			    <a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=redis" class="nav-tab inner" id="redis-inner" data-tab-id="redis">
			    	Redis Cache Settings <span class="dashicons dashicons-id-alt"></span>
			    </a>
			    <a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=rabbit" class="nav-tab inner" id="rabbit-inner" data-tab-id="rabbit">
			    	RabbitMq Settings <span class="dashicons dashicons-id-alt"></span>
			    </a>
			    <a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=beem" class="nav-tab inner" id="beem-inner" 
			    	data-tab-id="beem">
			    	Beem ChatBot Settings <span class="dashicons dashicons-id-alt"></span>
			    </a>
				<a href="<?php echo admin_url() ?>?page=bitwise-ai-service&tab=prerequisite" class="nav-tab inner" id="prerequisite-inner" data-tab-id="prerequisite">
			    	Grade Level Prerequisite <span class="dashicons dashicons-id-alt"></span>
			    </a>  
			</h2>
		</div>
		<div id="content-redis-inner" class="content content inner">
				<form action="options.php" method="post" id="validate1">	
					<?php settings_fields( 'bitwise_redis_settings' ); ?>
					<?php do_settings_sections( 'bitwise_redis_settings' ); ?>
					<?php submit_button(); ?>
				</form>
				<hr/>
			</div>
			<div id="content-rabbit-inner" class="content inner">

				<form action="options.php" method="post" id="validate2">	
					<?php settings_fields( 'bitwise_rabbitMQ_settings' ); ?>
					<?php do_settings_sections( 'bitwise_rabbitMQ_settings' ); ?>
					<?php submit_button(); ?>
				</form>
				<hr/>
				</div>
				<div id="content-beem-inner" class="content inner">
				<form action="options.php" method="post" id="validate3">	
					<?php settings_fields( 'bitwise_beem_settings' ); ?>
					<?php do_settings_sections( 'bitwise_beem_settings' ); ?>
					<?php submit_button(); ?>
				</form>
				<hr/>
				</div>
			 	<div id="content-prerequisite-inner" class="content inner">
				<form action="options.php" method="post" id="validate4">	
					<?php settings_fields( 'bitwise_grade_prerequisties_settings' ); ?>
					<?php do_settings_sections( 'bitwise_grade_prerequisties_settings' ); ?>
					<?php submit_button(); ?>
				</form>
				<hr/>
			</div> 
			</div>


	        
	   </div><!-- /.wrap -->
<!-- </div> -->
<script src="//code.jquery.com/jquery-1.12.1.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function(){

 $('#validate1').validate();
 $('#validate2').validate();
 $('#validate3').validate();
 $('#validate4').validate();
 

});
  </script>
