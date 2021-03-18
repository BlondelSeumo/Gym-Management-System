<?php 
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.day_list').multiselect({
		includeSelectAllOption: true	
	});
	$(".dob").datepicker({format: '<?php echo $this->Gym->getSettings("date_format"); ?>'});
	
	$('#time').timepicker({
		timeFormat: "HH:mm:ss",
		showMeridian:false
	});
	$('#timepicker').timepicker({
		timeFormat: "HH:mm:ss",
		showMeridian:false
	});
	
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo $title;?>
				<small><?php echo __("Class Schedule");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("ClassSchedule","classList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Class List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
			echo $this->Form->create("addClass",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Class Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"class_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[50]]","value"=>(($edit)?$data['class_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Staff Member").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("assign_staff_mem",$staff,["default"=>$data['assign_staff_mem'],"empty"=>__("Select Staff Member"),"class"=>"form-control validate[required]"]);
			echo "</div>";	
			
			if($this->request->session()->read("User.role_name") == "Administrator")
			{
				echo '<div class="col-md-2">';
				echo "<a href='{$this->request->base}/StaffMembers/addStaff' class='btn btn-flat btn-primary'>".__("Add")."</a>";
				echo "</div>";	
				
			}
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Assistant Staff Member").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("assistant_staff_member",$assistant_staff,["default"=>$data['assistant_staff_member'],"empty"=>__("Select Staff Member"),"class"=>"form-control"]);
			echo "</div>";	
			
			if($this->request->session()->read("User.role_name") == "Administrator")
			{
				echo '<div class="col-md-2">';
				echo "<a href='{$this->request->base}/StaffMembers/addStaff' class='btn btn-flat btn-primary'>".__("Add")."</a>";
				echo "</div>";	
			}
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Location").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"location","class"=>"form-control validate[custom[onlyLetterSp],maxSize[50]]","value"=>(($edit)?$data['location']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Days").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			$days = ["Sunday"=>__("Sunday"),"Monday"=>__("Monday"),"Tuesday"=>__("Tuesday"),"Wednesday"=>__("Wednesday"),"Thursday"=>__("Thursday"),"Friday"=>__("Friday"),"Saturday"=>__("Saturday")];
			echo @$this->Form->select("days",$days,["default"=>json_decode($data['days']),"multiple"=>"multiple","class"=>"form-control validate[required] day_list"]);
			echo "</div>";				
			echo "</div>";	
			
			echo "<div class='form-group'>";
			echo '<label class="control-label col-md-2" for="start time">'. __("Start Time").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 ">';
			echo $this->Form->input('',array('label'=>false,'id'=>'time','name'=>'start_time','class'=>'form-control validate[required]  text-input start_time',"value"=>(($edit)?$data['start_time']:'')));
			echo "</div>";
			echo '</div>';
			
			echo "<div class='form-group'>";
			echo '<label class="control-label col-md-2" for="end time">'. __("End Time").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 ">';
			echo $this->Form->input('',array('label'=>false,'id'=>'timepicker','name'=>'end_time','class'=>'form-control validate[required]  text-input end_time',"value"=>(($edit)?$data['end_time']:'')));
			echo "</div>";
			echo '</div>';
			echo $this->Form->button(__("Add Time"),['type'=>'button','id'=>'add_time','class'=>"btn btn-flat btn-success col-md-offset-2","name"=>"add_class"]);
			echo "<br><br>";
			echo "<div class='time_list col-md-10 col-md-offset-2'>";?>
			<table class="table">
				<tr class="table-head"><th><?php echo __("Days");?></th><th><?php echo __("Start Time");?></th><th><?php echo __("End Time");?></th><th><?php echo __("Action");?></th></tr>
				<tbody class="time_table">
					
					<?php
					if($edit)
					{
						
						foreach($schedule_list as $schedule)
						{
							$days_time=	json_decode($schedule["days"]);

							?>
							<tr>
								<td><?php echo implode(",",json_decode($schedule["days"]));?></td>
								<td><?php echo $schedule["start_time"];?></td>
								<td><?php echo $schedule["end_time"];?>
								<input type="hidden" name="time_list[]" value='[<?php echo $schedule["days"].",&quot;".$schedule["start_time"]."&quot;,&quot;".$schedule["end_time"] ."&quot;"; ?>]' class="123">	
								<input type="hidden" name="demo[]" value='[<?php echo "&quot;". $days_time[0] ."&quot;,&quot;".$days_time[1] ."&quot;,&quot;".$schedule["start_time"]."&quot;,&quot;".$schedule["end_time"] ."&quot;"; ?>]' class="time_schedule">								
								</td>								
								<td>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-danger class_sch_del_row"><i class="fa fa-times-circle"></i></span></td>
							</tr>							
					<?php }
					}?>
				</tbody>
			</table>
			<?php
			echo "</div>";			
			
			echo "<hr>";
			echo "<br>";
			echo "<br>";
			echo $this->Form->button(__("Save Class"),['class'=>"btn btn-flat btn-success col-md-offset-2 svcls","name"=>"add_class"]);
			echo $this->Form->end();
		?>
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
		<input type="hidden" value="" id="hidchkval"> 
	</div>
</section>
<?php 
if(!$edit)
{?>
	<script>
		$(".time_list").css("display","none");
	</script>
<?php }
?>

<script>
$("#add_time").click(function(){
		
	var time_list = [];
	var tmp_list = [];
	var days = $(".day_list").val();
	
	if(days == null || $(".start_time").val() == "" || $(".end_time").val() == "")
	{
		alert("Please select days,start time and end time");
		return false;
	}
	$(".time_list").css("display","block");
	var json_days =  JSON.stringify(days);	
	
	
	var time_schedule =[];
	var start_time = $(".start_time").val();	
	var end_time = $(".end_time").val();	
	
	var i=0;
	
	temp='';
	time_list[0] = days;
	time_list[1] = start_time;
	time_list[2] = end_time;
	k=0
	 $.each(days, function() {
		tmp_list[k] = days[k];
		k++;
	});
	var productIds = [];
	tmp_list[k] = start_time;
	tmp_list[k+1] = end_time;
	$(".time_schedule").each(function() {
		var j=0;
		time_schedule[i]=$(this).val();
		var value =  jQuery.parseJSON(time_schedule[i]);
						 
		for (var i=0; i<value.length; i++) {
			index = tmp_list.indexOf(value[i]);
			if (index > -1) {
				tmp_list.splice(index, 1);
			}
		}				
		i++;				
	});
	
	if(tmp_list!=''){
		var val = JSON.stringify(time_list);	
		var val1 = JSON.stringify(tmp_list);	
	
		var time = gettime();
							
		if(time == true)
		{	
			$(".time_table").append('<tr><td>'+days+'</td><td>'+start_time+'</td><td>'+end_time+'<input type="hidden" name="time_list[]" value='+val+' class="123"><input type="hidden" name="demo[]" value='+val1+' class="time_schedule"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-danger class_sch_del_row"><i class="fa fa-times-circle"></i></span></td></tr>');
								
			var hidchkval = $("#hidchkval").val();
			hidchkval++;
			$("#hidchkval").val(hidchkval);
			hidchkval = $("#hidchkval").val();
								
			if(hidchkval == 0)
			{
				$(".svcls").hide();
			}
			else
			{
				$(".svcls").show();
			}
		}						
	}
	else{
		alert('This class schedule already added.');	
		}
});


function gettime(){
	var n=new Date();
	var st = Date.parse( n.getMonth()+1+'/'+n.getDate()+'/'+n.getFullYear() + ' ' + $("#time").val());
	var nd = Date.parse( n.getMonth()+1+'/'+n.getDate()+'/'+n.getFullYear() + ' ' + $("#timepicker").val());
	if(st >= nd){
		alert("Plese enter valid time.");
		return false;
	}else{
		return true;
	}
}

$(document).ready(function(){
	
	$("#hidchkval").val(0)
	var hidchkval = $("#hidchkval").val();

	if(hidchkval <= 0 )
	{
		$(".svcls").hide();
	}
	else
	{
		$(".svcls").show();
	}
	
	$("body").on("click",".class_sch_del_row",function(){		
		$(this).parents("tr").remove();
		
		var hidchkval = $("#hidchkval").val();
		hidchkval--;
		$("#hidchkval").val(hidchkval);
		
		if(hidchkval <= 0)
		{
			$(".svcls").hide();
		}
		else
		{
			$(".svcls").show();
		}
	});	
});


</script>