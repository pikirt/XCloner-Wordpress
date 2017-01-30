<?php 
$xcloner_settings = new Xcloner_Settings();
$tab = 1;
?>

<script>var xcloner_backup = new Xcloner_Backup();</script>

<h1><?= esc_html(get_admin_page_title()); ?></h1>
         
<ul class="nav-tab-wrapper content row">
	<li><a href="#backup_options" class="nav-tab col s12 m3 l2 nav-tab-active"><?php echo $tab.". ".__('Backup Options')?></a></li>
	<?php if($xcloner_settings->get_enable_mysql_backup()):?>
		<li><a href="#database_options" class="nav-tab col s12 m3 l2 "><?php echo ++$tab.". ".__('Database Options')?></a></li>
	<?php endif?>
	<li><a href="#files_options" class="nav-tab col s12 m3 l2 "><?php echo ++$tab.". ".__('Files Options')?></a></li>
	<li><a href="#generate_backup" class="nav-tab col s12 m3 l2 "><?php echo ++$tab.". ".__('Generate Backup')?></a></li>
	<li><a href="#schedule_backup" class="nav-tab col s12 m3 l2 "><?php echo ++$tab.". ".__('Schedule Backup')?></a></li>
</ul>

<form action="" method="POST" id="generate_backup_form">
	<div class="nav-tab-wrapper-content">
		<!-- Backup Options Content Tab-->
		<div id="backup_options" class="tab-content active">
			<div class="row">
		        <div class="input-field inline col s12 m10 l6">
					<i class="material-icons prefix">input</i>
					<input name="backup_name" id="backup_name" type="text" value=<?php echo $xcloner_settings->get_default_backup_name() ?> >
					<label for="backup_name"><?php echo __('Backup Name')?></label>
				</div>
				<div class="hide-on-small-only m2">
					<a class="btn-floating tooltipped btn-small" data-position="right" data-delay="50" data-tooltip="<?php echo __('The default backup name, supported tags [time], [hostname], [domain]')?>" data-tooltip-id=""><i class="material-icons">help_outline</i></a>
				</div>
		     </div>
		     
		     <div class="row">
		        <div class="input-field inline col s12 m10 l6">
					<i class="material-icons prefix">input</i>
					<input name="email_notification" id="email_notification" type="text" value="<?php echo get_option('admin_email');?>" >
					<label for="email_notification"><?php echo __('Send Email Notification To')?></label>
				</div>
				<div class="hide-on-small-only m2">
					<a class="btn-floating tooltipped btn-small" data-position="right" data-delay="50" data-tooltip="<?php echo __('If left blank, no notification will be sent')?>" data-tooltip-id=""><i class="material-icons">help_outline</i></a>
				</div>
		     </div>
		     
		     <div class="row">
				<div class="input-field col s12 m10 l6">
					<i class="material-icons prefix">input</i>
					<textarea name="backup_comments" id="backup_comments" class="materialize-textarea"></textarea>
					<label for="backup_comments"><?php echo __('Backup Comments')?></label>
				</div>
				<div class="hide-on-small-only m2">
					<a class="btn-floating tooltipped btn-small" data-position="right" data-delay="50" data-tooltip="<?php echo __('Some default backup comments that will be stored inside the backup archive')?>" data-tooltip-id=""><i class="material-icons">help_outline</i></a>
				</div>
		     </div>
		     
		     <div class="row">
				<div class="input-field col s12 m10 l6 right-align">
					<a class="waves-effect waves-light btn" onclick="next_tab('#database_options');"><i class="material-icons right">skip_next</i>Next</a>
				</div>
			 </div>
		</div>
		
		<?php if($xcloner_settings->get_enable_mysql_backup()):?>
		<div id="database_options" class="tab-content">
			<h2><?php echo __('Select database data to include in the backup')?>:
				<a class="btn-floating tooltipped btn-small" data-position="right" data-delay="50" data-tooltip="<?php echo __('Disable the \'Backup only WP tables\' setting if you don\'t want to show all other databases and tables not related to this Wordpress install');?>" data-tooltip-id=""><i class="material-icons">help_outline</i></a>
			</h2>
			
			<!-- database/tables tree -->
			<div class="row">
				<div class="col s12 l6">
					<div id="jstree_database_container"></div>
				</div>
			</div>
		    
		    <div class="row">
				<div class="input-field col s12 m10 l6 right-align">
					<a class="waves-effect waves-light btn" onclick="next_tab('#files_options');"><i class="material-icons right">skip_next</i>Next</a>
				</div>
			</div>
			
		</div>
		<?php endif ?>
		
		<div id="files_options" class="tab-content">
			<h2><?php echo __('Select from below the files/folders you want to exclude from your Backup Archive')?>:
				<a class="btn-floating tooltipped btn-small" data-position="bottom" data-delay="50" data-tooltip="<?php echo __('You can navigate below through all your site structure(Backup Start Location) to exclude any file/folder you need by clicking the checkbox near it');?>" data-tooltip-id=""><i class="material-icons">help_outline</i></a>
			</h2>
			
			<!-- Files System Container -->
			<div class="row">
				<div class="col s12 l6">
					<div id="jstree_files_container"></div>
				</div>
			</div>
			
			<div class="row">
				<div class="input-field col s12 m10 l6 right-align">
					<a class="waves-effect waves-light btn" onclick="next_tab('#generate_backup');"><i class="material-icons right">skip_next</i>Next</a>
				</div>
			</div>
			
		</div>
		<div id="generate_backup" class="tab-content">
			<div class="row ">
				 <div class="col s12 l8 center action-buttons">
					<a class="waves-effect waves-light btn-large teal darken-1 start" onclick="xcloner_backup.start_backup()">Start Backup<i class="material-icons left">forward</i></a>
					<a class="waves-effect waves-light btn-large teal darken-1 restart" onclick="xcloner_backup.start_backup()">Restart Backup<i class="material-icons left">cached</i></a>
					<a class="waves-effect waves-light btn-large red darken-1 cancel" onclick="xcloner_backup.cancel_backup()">Cancel Backup<i class="material-icons left">cancel</i></a>
				</div>
				<div class="col l8 s12">
					<ul class="backup-status collapsible" data-collapsible="accordion">
					    <li class="file-system">
						      <div class="collapsible-header">
									<i class="material-icons">folder</i><?php echo __('Scanning The File System...')?>
									
									<p class="right"><?php echo __(sprintf('Found %s files (%s)', '<span class="file-counter">0</span>', '<span  class="file-size-total">0</span>MB'))?></p>

									<div>
										<p class="right"><span class="last-logged-file"></span></p>
									</div>	
									
									<div class="progress">
										<div class="indeterminate"></div>
									</div>
								</div>	
						      <div class="collapsible-body status-body"></div>
					    </li>
					    <?php if($xcloner_settings->get_enable_mysql_backup()):?>
					    <li class="database-backup">
						      <div class="collapsible-header">
									<i class="material-icons">storage</i><?php echo __('Generating the Mysql Backup...')?>
									
									<p class="right"><?php echo __(sprintf('Found %s tables in %s databases (%s)', '<span class="table-counter">0</span>', '<span class="database-counter">0</span>', '<span data-processed="0" class="total-records">0</span> records'))?></p>
									
									<div>
										<p class="right"><span class="last-logged-table"></span></p>
									</div>	
									
									<div class="progress">
										<div class="determinate" style="width:0%"></div>
									</div>
								</div>	
						      <div class="collapsible-body status-body">
									<div class="row">
										<div class="col l7 s12">
											<ul class="logged-tables"></ul>
										</div>
										<div class="col l5 s12">
											<ul class="logged-databases right"></ul>
										</div>
									</div>
							</div>
					    </li>
					    <?php endif?>
					    <li class="files-backup">
						      <div class="collapsible-header">
									<i class="material-icons">archive</i><?php echo __('Adding Files to Archive...')?>
									
									<p class="right"><?php echo __(sprintf('Adding %s files (%s)', '<span class="file-counter">0</span>', '<span  data-processed="0" class="file-size-total">0</span>MB'))?></p>

									<div>
										<p class="right"><span class="last-logged-file"></span></p>
									</div>	
									
									<div class="progress">
										<div class="determinate" style="width:0%"></div>
									</div>
								</div>	
						      <div class="collapsible-body status-body">
									<div class="row">
										<div class="col l3 s12">
											<h2><?php echo __("Backup Parts")?>: </h2>
										</div>	
										<div class="col l9 s12">
											<ul class="backup-name"></ul>
										</div>	
									</div>
							  </div>
					    </li>
					    <li class="backup-done">
						      <div class="collapsible-header">
									<i class="material-icons">done</i><?php echo __('Backup Done')?>
									
									<p class="right">
										 
									</p>

									<div>
										<p class="right"><span class="last-logged-file"></span></p>
									</div>	
									
									<div class="progress">
										<div class="determinate" style="width:0%"></div>
									</div>
								</div>	
						      <div class="collapsible-body">
									<div class="row">
										<div class="input-field col s12 m6">
										    <select class="">
										      <option disabled value="" selected>Send backup to</option>
										      <option value="" data-icon2="images/sample-1.jpg" class="circle">Ftp Server</option>
										      <option value="" data-icon2="images/office.jpg" class="circle">Dropbox</option>
										      <option value="" data-icon2="images/yuna.jpg" class="circle">Amazon S3</option>
										    </select>
										    <label>Images in select</label>
										  </div>
									</div>
							  </div>
					    </li>
				  </ul>
				 </div> 
				
			</div>
		</div>
		
		<div id="schedule_backup" class="tab-content">
			
			<div class="row">
				<div id="schedule_backup_success" class="col s12 l6 updated settings-error notice is-dismissible"> 
					<p><strong><?php echo __('Schedule Saved', 'xcloner')?></strong></p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __('(Dismiss this notice.')?></span></button>
				</div>
			</div>
			
			<div class="row server-time">
				<div class="col s12 m10 l6 teal lighten-1">
					<h2><?php echo __('Current Server Time', 'xcloner')?>: <span class="right"><?php echo date("Y/m/d H:i")?></span></h2>
				</div>
			</div>
			<div class="row">
				 <div class="input-field inline col s12 m6 l4">
					  <input type="datetime-local" id="datepicker" class="datepicker" name="schedule_start_date" >
					  <label for="datepicker"><?php echo __('Schedule Backup To Start At:')?></label>
				</div>
				 <div class="input-field inline col s12 m4 l2">
					  <input id="timepicker_ampm_dark" class="timepicker" type="time" name="schedule_start_time">
					  <label for="timepicker_ampm_dark"><?php echo __('Time:')?></label>
				</div>
			</div>
			
			<div class="row">
				<div class="input-field col s12 m10 l6">
					<select name="schedule_frequency" id="schedule_frequency" class="validate" required>
						<option value="" disabled selected><?php echo __('Please Select The Recurrence Schedule', 'xcloner') ?></option>
						<option value="one_time">One time</option>
						<option value="hourly">Hourly</option>
						<option value="daily">Daily</option>
						<option value="weekly">Weekly</option>
						<option value="monthly">Monthly</option>
						</select>
						<label>Frequency To Run</label>
				</div>
			</div>	
			
			<div class="row">
				 <div class="input-field inline col s12 m10 l6">
					  <input type="text" id="schedule_name" class="" name="schedule_name" required>
					  <label for="schedule_name">Schedule Name</label>
				</div>
			</div>
			<div class="row">
				<div class="col s12 m10 l6">
					<button class="right btn waves-effect waves-light" type="submit" name="action">Submit
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>
		</div>	
	</div>
</form>

<!-- Error Modal Structure -->
<div id="error_modal" class="modal">
	<a title="Online Help" href="https://wordpress.org/support/plugin/xcloner-backup-and-restore" target="_blank"><i class="material-icons medium right">help</i></a>
	<div class="modal-content">
		<h4 class="title_line"><span class="title"></span></h4>
		<!--<h5 class="title_line"><?php echo __('Message')?>: <span class="msg.old"></span></h5>-->
		<h5><?php echo __('Response Code')?>: <span class="status"></span></h5>
		<textarea  class="body" rows="5"></textarea>
	</div>
	<div class="modal-footer">
		<a class=" modal-action modal-close waves-effect waves-green btn-flat  red darken-2"><?php echo __('Close')?></a>
	</div>
</div>
  
<script>
jQuery(function () { 
	
	jQuery('select').material_select();
	jQuery("select[required]").css({display: "block", height: 0, padding: 0, width: 0, position: 'absolute'});

	
	
	jQuery('.timepicker').pickatime({
	    default: 'now',
	    min: [7,30],
	    twelvehour: false, // change to 12 hour AM/PM clock from 24 hour
	    donetext: 'OK',
		autoclose: false,
		vibrate: true // vibrate the device when dragging clock hand
	});

	var date_picker = jQuery('.datepicker').pickadate({
		format: 'd mmmm yyyy',
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 15, // Creates a dropdown of 15 years to control year
		min: +0.1,
		onSet: function() {
			this.close();
		}
	});
	
	<?php if($xcloner_settings->get_enable_mysql_backup()):?>
	jQuery('#jstree_database_container').jstree({
			'core' : {
				'check_callback' : true,
				'data' : {
					'method': 'POST',
					'dataType': 'json',
					'url' : ajaxurl,
					'data' : function (node) {
								var data = { 
									'action': 'get_database_tables_action',
									'id' : node.id
									}
								return data;
							}
				},		
					
			'error' : function (err) { 
				//alert("We have encountered a communication error with the server, please review the javascript console."); 
				var json = jQuery.parseJSON( err.data )
				show_ajax_error("Error Loading Database Structure ", err.reason, json.xhr);
				},
			 
			'strings' : { 'Loading ...' : 'Loading the database structure...' },			
			'themes' : {
					"variant" : "default"
				},
			},
			'checkbox': {
				  three_state: true
			},
			'plugins' : [
					"checkbox",
					"massload",
					"search",
					//"sort",
					//"state",
					"types",
					"unique",
					"wholerow"
				]
		});
	<?php endif ?>
		
	jQuery('#jstree_files_container').jstree({
			'core' : {
				'check_callback' : true,
				'data' : {
					'method': 'POST',
					'dataType': 'json',
					'url' : ajaxurl,
					'data' : function (node) {
								var data = { 
									'action': 'get_file_system_action',
									'id' : node.id
									}
								return data;
							}
				},		
					
			'error' : function (err) {
				//alert("We have encountered a communication error with the server, please review the javascript console."); 
				var json = jQuery.parseJSON( err.data )
				show_ajax_error("Error Loading Files Structure ", err.reason, json.xhr);
				},
			 
			'strings' : { 'Loading ...' : 'Loading the database structure...' },			
			'themes' : {
					"variant" : "default"
				},
			},
			'checkbox': {
				  three_state: true
			},
			'plugins' : [
					"checkbox",
					"massload",
					"search",
					//"sort",
					//"state",
					"types",
					"unique",
					"wholerow"
				]
		});
});


</script>