
<script type="text/javascript">
$(document).ready(function() {
	
	$(".date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
	<?php
	if($edit)
	{?>
	$( ".date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($data['event_date'])); ?>" ));
	<?php } ?>
	
	$('#time').timepicker({
		timeFormat: "HH:mm:ss",
		showMeridian:false
	});
	$('#timepicker').timepicker({
		timeFormat: "HH:mm:ss",
		showMeridian:false
	});
	$("#event").submit(function(){
		var n=new Date();
			   var st = Date.parse( n.getMonth()+1+'/'+n.getDate()+'/'+n.getFullYear() + ' ' + $("#time").val());
			   var nd = Date.parse( n.getMonth()+1+'/'+n.getDate()+'/'+n.getFullYear() + ' ' + $("#timepicker").val());
		if(st >= nd){
			alert("Plese enter valid time.");
			return false;
		}
	});
	
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php if($edit){
					echo __("Edit Event");
				}else{
					echo __("Add Event");
				}
				?>
				<small><?php echo __("Schedule");?></small>
			  </h1>
			  <ol class="breadcrumb">				
				<a href="<?php echo $this->Gym->createurl("GymReservation","reservationList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Event List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
			echo $this->Form->create("reseravtion_Add",["class"=>"validateForm form-horizontal","role"=>"form","id"=>"event"]);
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Event Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"event_name","class"=>"form-control validate[required,custom[onlyLetterSp,maxSize[50]]]","value"=>(($edit)?$data['event_name']:'')]);
			echo "</div>";	
			echo "</div>";

			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Event Date").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"event_date","class"=>"form-control validate[required] date"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Event Place").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 module_padding">';			
			echo @$this->Form->select("place_id",$event_places,["default"=>$data['place_id'],"empty"=>__("Select Event Places"),"class"=>"form-control events_place_list validate[required]"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='javascript:void(0)' data-url='{$this->request->base}/GymAjax/EventPlaceList' id='eventplace_list' class='btn btn-flat btn-default'>".__("Add or Remove")."</a>";
			echo "</div>";	
			echo "</div>";			
			
			
			
			echo "<div class='form-group'>";
			echo '<label class="control-label col-md-2" for="start time">'. __("Start Time").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 ">';
			echo $this->Form->input('',array('label'=>false,'id'=>'time','name'=>'starttime','class'=>'form-control validate[required]  text-input',"value"=>(($edit)?$data['start_time']:'')));
			echo "</div>";
			echo '</div>';
			
			echo "<div class='form-group'>";
			echo '<label class="control-label col-md-2" for="start time">'. __("End Time").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 ">';
			echo $this->Form->input('',array('label'=>false,'id'=>'timepicker','name'=>'endtime','class'=>'form-control validate[required]  text-input',"value"=>(($edit)?$data['end_time']:'')));
			echo "</div>";
			echo '</div>';
			
			
			echo "<br>";
			echo $this->Form->button(__("Save Class"),['class'=>"btn btn-flat btn-success col-md-offset-2","name"=>"add_class"]);
			echo $this->Form->end();
		?>
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>