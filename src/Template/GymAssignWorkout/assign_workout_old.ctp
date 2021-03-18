<?php $session = $this->request->session()->read("User");?>
<?php
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
?>
<script>
$(document).ready(function() {
$(".mem_list_workout").select2();

$("#startDate").datepicker({
			minDate:0,
		dateFormat: '<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>',
		changeMonth: true,
		changeYear: true,
		onSelect: function() {
			var date = $('#startDate').datepicker('getDate');  
			date.setDate(date.getDate());
			$("#endDate").datepicker("option","minDate", date);  
		}
	}); 
	$("#endDate").datepicker({
	
		
		dateFormat: '<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>',
		changeMonth: true,
		changeYear: true,
	}); 
var box_height = $(".box").height();
var box_height = box_height + 100 ;

/* FETCH Activity On Page Load */

	var member_id = $(".mem_list_workout option:selected").val()	
	var ajaxurl = $("#getcategory").attr("data-url");
	var curr_data = {member_id:member_id};
	$.ajax({
		url : ajaxurl,
		type : "POST",
		data : curr_data,
		success : function(result)
		{
			$("#append").html("");			
			$("#append").append(result);			
		},
		error : function(e)
		{
			console.log(e.responseText);
		}
	});
	
/* FETCH Activity On Page Load */


});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-hand-grab-o"></i>
				<?php echo $title;?>
				<small><?php echo __("Assign Workout");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymAssignWorkout","workoutLog");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Workout Logs");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
			echo $this->Form->create("assignWorkout",["class"=>"validateForm form-horizontal","role"=>"form"]);
		?>
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Select Member");?><span class="text-danger"> *</span></label>
			<div class="col-md-6 module_padding">
				<?php 
					echo $this->Form->select("user_id",$members,["default"=>($edit)?$this->request->params["pass"]:"","class"=>"mem_list_workout"]);
				?>
			<input type="hidden" id="getcategory" data-url="<?php echo $this->request->base;?>/GymAjax/getCategoriesByMember" >
			</div>
			<?php if($session["role_name"] == "administrator"){ ?>
			<div class="col-md-3">
				<a href="<?php echo $this->request->base;?>/GymMember/addMember" class="btn btn-default btn-flat"><?php echo __("Add Member");?></a>
			</div>
			<?php } ?>
		</div>		
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Level");?><span class="text-danger"> *</span></label>
			<div class="col-md-6 module_padding">
				<?php 
					echo $this->Form->select("level_id",$levels,["empty"=>__("Select Level"),"class"=>"form-control level_list validate[required]"]);
				?>
			</div>
			<div class="col-md-3">
				<a href="#" class="btn btn-default btn-flat level-list" data-url="<?php echo $this->request->base;?>/GymAjax/levelsList"><?php echo __("Add Level");?></a>
			</div>
		</div>		
		<div class="form-group">
			<label class="col-md-3 control-label" for="description"><?php echo __('Description');?></label>
			<div class="col-md-8">
				<textarea id="description" class="form-control" name="description"></textarea>
			</div>
		</div>	
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Start Date");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"start_date","class"=>"validate[required] form-control",'id'=>'startDate']);
				?>
			</div>	
		</div>
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("End Date");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"end_date","class"=>" validate[required] form-control",'id'=>'endDate']);
				?>
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-1 control-label"></label>
			<div class="col-sm-10 border">
				<br>
				<div class="col-md-3">
					<label class="list-group-item bg-default"><?php echo __("Select Days");?></label>
					<?php foreach ($this->Gym->days_array() as $key=>$name)
					{ ?>
						<div class="checkbox">
						<label><input type="checkbox" value="" class="days_checkbox" name="day[]" value="<?php echo $key;?>" id="<?php echo $key;?>" data-val="day"><?php echo __($name); ?> </label>
						</div>
				<?php }?>
				</div>
				<div class="col-md-8 activity_list">
				<label class="col-md-8 list-group-item bg-default"><?php echo __("Select workout activity to add on selected days");?></label>
			
				<div class="clearfix"></div>
				<div id="append">			
				</div>
				<br>
				<input type="button" value="<?php echo __('Step-1 Add Workout');?>" name="sadd_workouttype" id="add_workouttype" class="pull-left btn btn-flat btn-info"/>
				</div>
			</div>
		</div>	
		<div class="col-sm-offset-2 col-sm-8">
			<div class="form-group">
				<div class="col-md-8">
				
				</div>
			</div>
		</div>
		<div id="display_rout_list"></div>		
		<br><br>
		<div class="col-md-offset-2 col-sm-8 schedule-save-button">
        	
        	<input type="submit" value="<?php if($edit){ echo __('Step-2 Save Workout'); }else{ echo __('Save Workout');}?>" name="save_workouttype" class="btn btn-flat btn-success" id = "save-workout"/>
        </div>
		<input type="hidden" id="add_workout_url" value="<?php echo $this->request->base;?>/GymAjax/gmgt_add_workout">
		<div class='clear'>
		<br><br>
		<?php 
		$this->Form->end();
		
		if($edit)
		{
			foreach($work_outdata as $data=>$row)
			{				
				foreach($row as $r)
				{
					if(is_array($r))
					{
						$days_array[$data]["start_date"] = $row["start_date"];
						$days_array[$data]["end_date"] = $row["end_date"];
						$day = $r["day_name"];
						$days_array[$data][$day][] = $r;
					}
				}					
			}
			
			
			foreach($days_array as $data=>$row)
			{?>
				<div class="panel panel-default workout-block" id="remove_panel_<?php echo $data;?>">				
				  <div class="panel-heading">
					<i class="fa fa-calendar"></i> <?php echo __("Start From")." <span class='work_date'>".$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["start_date"])))."</span> ".__("TO")." <span class='work_date'>".$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["end_date"])))."</span>";?>
					<span class="del_panel" del_id="<?php echo $data;?>" data-url="<?php echo $this->request->base;?>/GymAjax/deleteWorkoutData/<?php echo $data;?>"><i class='fa fa-times-circle' aria-hidden="true"></i></span>
				  </div>
				  <br>
				<div class="work_out_datalist_header">
					<div class="col-md-2 col-sm-2">  
						<strong><?php echo __("Day name");?></strong>
					</div>
					<div class="col-md-10 col-sm-10 hidden-xs">
						<span class="col-md-3"><?php echo __("Activity");?></span>
						<span class="col-md-3"><?php echo __("Sets");?></span>
						<span class="col-md-2"><?php echo __("Reps");?></span>
						<span class="col-md-2"><?php echo __("KG");?></span>
						<span class="col-md-2"><?php echo __("Rest Time");?></span>
					</div>
				</div>				
				<?php 
				foreach($row as $day=>$value)
				{
					if(is_array($value))
					{ 
					?>
						<div class="work_out_datalist">
						<div class="col-md-2 day_name"><?php echo __($day);?></div>
						<div class="col-md-10 col-xs-12">
						<?php foreach($value as $r)
							{?>
							<div class="col-md-12">
							<span class="col-md-3 col-sm-3 col-xs-12"><?php echo $this->Gym->get_activity_by_id($r["workout_name"]);?></span>   
							<span class="col-md-3 col-sm-3 col-xs-6"><?php echo $r["sets"];?></span>
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["reps"];?> </span>
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["kg"];?> </span>
							<span class="col-md-2 col-sm-2  col-xs-6"><?php echo $r["time"];?> </span>
							</div>
						<?php } ?>
						</div>
						</div>
					<?php } 
				}?>				
				</div>
	  <?php } 
		}?>		
		<br><br>
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
<script>
jQuery("body").on("click", "#add_workouttype", function(event){
		 var count = $("#display_rout_list div").length;		
		
		 var day = '';
		 var activity = '';
		 var check_val = '';
		 jsonObj1 = [];
		 jsonObj2 = [];
		 jsonObj = [];
		 var day_check = $(".days_checkbox:checked").size();	
		 if(day_check == 0)
		 {
			alert("Please select days.");
			//$("#save-workout").hide();
			return false;
		 }else{
			$(".achilactiveadd").hide();
			 $("#save-workout").show();
		 }
		 
		 var activity_check = $(".activity_check:checked").size();
		 if(activity_check == 0)
		 {
			alert("Please select activity.");
			return false;
		 }
		  var is_empty = 0;
		 $(".activity_value_box").each(function(o){
			 var activity_val = $(this).val();
			 if(activity_val == "")
			 {
				is_empty = 1;
				is_empty--;
			 }
		 });
		 
		 if(is_empty == 1)
		 {
			alert("Please Fill All The Fields.");
			$("#save-workout").hide();
			
			return false;
		 } 
		 $(":checkbox:checked").each(function(o){
			
			  var chkID = $(this).attr("id");
			  var check_val = $(this).attr("data-val");			  
			  if(check_val == 'day')
			  { 
				  day += add_day(chkID,chkID);
				  item = {}
			        item ["day_name"] =chkID;
			       
			        jsonObj1.push(item);
			        //$(this).prop("disabled", true);
			  }
			  
			  if(check_val == 'activity')
			  {
				  activity_name = $(this).attr("activity_title");
				  item = {};
			        item ["activity"] = {"activity":activity_name,"sets":$("#sets_"+chkID).val(),"reps":$("#reps_"+chkID).val(),"kg":$("#kg_"+chkID).val(),"time":$("#time_"+chkID).val()};
				  activity += add_activity(activity_name,chkID);
				 
			       
			        jsonObj2.push(item);
			  }
			  $(this).prop('checked', false);
			 
			 // $("#"+chkID+"summ").removeAttr("disabled");
			  /* ... */
			  jsonObj = {"days":jsonObj1,"activity":jsonObj2};
			});
		var ajaxurl = $("#add_workout_url").val();
		 var curr_data = {					
						data_array: jsonObj											
						};
		$.ajax({
			url:ajaxurl,
			type:"POST",
			data:curr_data,
			success:function(response){
						var list_workout =  workout_list(day,activity,count,response);						 
						$("#display_rout_list").append(list_workout);
						return false;
					}
		});
		return false;					
		var list_workout =  workout_list(day,activity);
		 $("#display_rout_list").append(list_workout);
		 $(".achilactiveadd").hide();
	}); 
	
function workout_list(day,activity,id,response)
{	
	var string = '';
	string += "<div class='activity border' id='block_"+id+"'>";
	string += '<div class="col-md-4">'+day+'</div>';
	string += '<div class="col-md-6">'+activity +'</div>';
	string += '<span>'+ response+'</span>';
	string += "<div id='"+id+"' class='removethis col-md-2'><span did='"+id+"' class='badge badge-delete pull-right del_box'>X</span></div></div>";
	return string;
}
function transalte_day(day)
{
	var day_name;
	switch(day)
	{
		case 'Sunday':
			day_name = moment.weekdays(7);
		break;
		case 'Monday':
			day_name = moment.weekdays(1);
		break;
		case 'Tuesday':
			day_name = moment.weekdays(2);
		break;
		case 'Wednesday':
			day_name = moment.weekdays(3);
		break;
		case 'Thursday':
			day_name = moment.weekdays(4);
		break;
		case 'Friday':
			day_name = moment.weekdays(5);
		break;
		case 'Saturday':
			day_name = moment.weekdays(6);
		break;
	}
	
	return day_name;
}
function add_day(day,id)
 {
	var string = '';
	string = '<span id="'+id+'">'+transalte_day(day)+'</span>, ';
	string += '<input type="hidden" name="day[day]['+day+']" value="'+day+'">';
	return string;
 }
 
function add_activity(activity,id)
{
	var string = '';
	var sets = '';
	var reps = '';
	var kg = '';
	var time = '';
	sets = $("#sets_"+id).val();
	reps = $("#reps_"+id).val();
	kg = $("#kg_"+id).val();
	time = $("#time_"+id).val();
	var s1 =$.isNumeric(sets);
	var r1 =$.isNumeric(reps);
	var k1 =$.isNumeric(kg);
	var t1 =$.isNumeric(time);
	if(s1 == true && r1 == true && k1 == true && t1 == true){
		string += '<p id="'+id+'"><strong>'+activity+' </strong>: ';
		string += '<span id="sets_'+id+'"> Sets '+sets+', </span>';
		string += '<span id="reps_'+id+'"> Reps '+reps+', </span>';
		string += '<span id="kg_'+id+'"> KG '+kg+', </span>';
		string += '<span id="time_'+id+'"> Rest Time '+time+', </span></p>';
		string += '<input type="hidden" name="sets[]" value="'+sets+'">';
		string += '<input type="hidden" name="reps[]" value="'+reps+'">';
		string += '<input type="hidden" name="kg[]" value="'+kg+'">';
		string += '<input type="hidden" name="time[]" value="'+time+'">';
		string += '<input type="hidden" name="activity[]" value="'+activity+'">';
		sets = $("#sets_"+id).val('');
		reps = $("#reps_"+id).val('');
		kg = $("#kg_"+id).val('');
		time = $("#time_"+id).val('');
		return string;
	}else{
		alert("Please Enter Valid Value");
		//break;
		(".workout-block").hide();
		$("#save-workout").hide();
	}
}


$("body").on("change",".activity_check",function(){
			

		 if($(this).is(":checked"))
		{
			 
			 id = $(this).attr('id');
				
			 string = '';
			
			string += '<div class="achilactiveadd "><span class="label"> Sets </span><input type="text" name = "sets_' + id + '" id = "sets_' + id + '" placeholder="Sets" class="activity_value_box validate[custom[integer]]" maxLength=5></div>';
			string += '<div class="achilactiveadd"><span class="label"> Reps</span> <input type="text" name = "reps_' + id + '" id = "reps_' + id + '" placeholder="Reps" class="activity_value_box validate[custom[integer]]" maxLength=5></div>';
			string += '<div class="achilactiveadd"><span class="label"> KG </span><input type="text" name = "kg_' + id + '" id = "kg_' + id + '" placeholder="KG" class="activity_value_box validate[custom[integer]]" maxLength=5></div>';
			string += '<div class="achilactiveadd"><span class="label">Rest Time </span><input type="text" name = "time_' + id + '" id = "time_' + id + '" placeholder="Min" class="activity_value_box validate[custom[integer]]" maxLength=5></div>';
			
				$("#reps_sets_"+id).html(string);
			 
		}
		 else
		{
			id = $(this).attr('id');
			$("#reps_sets_"+id).html('');
			$(".achilactiveadd").hide();
		}
	 });

$("body").on("click",".badge-delete",function(){		
	var remove = $(this).attr("did");
	$("#block_"+remove).remove(); 
	$("#save-workout").hide();
	
});
jQuery("body").on("click", ".days_checkbox", function(event){
	$(".activity_list").css('display','block');
});
jQuery("body").on("click", ".activity_check", function(event){
	$("#add_workouttype").css('display','block');
});
jQuery("body").on("click", "#add_workouttype", function(event){
	$(".schedule-save-button").css('display','block');
});
</script>
<style>
.activity_list,
#add_workouttype,
.schedule-save-button
{
	display: none;
}
</style>