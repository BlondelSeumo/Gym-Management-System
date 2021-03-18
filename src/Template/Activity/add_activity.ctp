<?php
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script>
$(document).ready(function(){
	$('.membership_list').multiselect({
		includeSelectAllOption: true	
	});
});
function validate_multiselect() {		
		var classes = $("#membership").val();
		var msg = '<?php echo __("Please select membership first.") ?>';
		if(classes == null) {
			alert(msg);
			return false;
		}else{
			return true;
		}		
}
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bicycle"></i>
				<?php echo $title;?>
				<small><?php echo __("Activity");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("Activity","activityList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Activity List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<div id="video" style="display: none;" class="video_div video_div_empty">			
			<div class="form-group">
				<label class="control-label col-md-3" for="email"><?php echo __("Add Youtube Embaded Link");?></label>
			
				<div class="col-md-6 module_padding">
				<?php echo $this->Form->textarea("video[]",["rows"=>"5","cols"=>"70","class"=>"form-control resize"]); ?>
				</div>						
				<div class="col-md-2">
					<button type="button" class="btn btn-flat btn-default" onclick="deleteParentElement(this)">
					<i class="entypo-trash"><?php echo __("Delete"); ?></i>
					</button>
				</div>
			</div>				
		</div>
			<?php 
				echo $this->Form->create("addactivity",["class"=>"validateForm form-horizontal","role"=>"form","onsubmit"=>"return validate_multiselect()"]);
			?>			
			<div class='form-group'>
				<label class="control-label col-md-3" for="email"><?php echo __("Select Category");?><span class="text-danger"> *</span></label>
				<div class="col-md-6 add_listcat module_padding">
				<?php 
					echo $this->Form->select("cat_id",$categories,["default"=>($edit)?array($data['cat_id']):"","empty"=>__("Select Category"),"class"=>"validate[required] cat_list form-control"]);
				?>
				</div>
				<div class="col-md-2">
					<button class="form-control add_category btn btn-default btn-flat" type="button" data-url="<?php echo $this->Gym->createurl("GymAjax","addCategory"); ?>"><?php echo __("Add Category");?></button>
				</div>
			</div>
			<div class='form-group'>
				<label class="control-label col-md-3" for="email"><?php echo __("Activity Title");?><span class="text-danger"> *</span></label>
				<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"title","class"=>"validate[required,maxSize[40]] form-control","value"=>(($edit)?$data['title']:""),]);
				?>
				</div>	
			</div>
			<div class='form-group'>
				<label class="control-label col-md-3 " for="email"><?php echo __("Assign to Staff Member");?><span class="text-danger"> *</span></label>
			
				<div class="col-md-6 module_padding">
				<?php 
					echo $this->Form->select("assigned_to",$staff,["default"=>($edit)?array($data['assigned_to']):"","empty"=>__("Select Staff Member"),"class"=>"validate[required] form-control"]);
				?>
				</div>
			
				<?php if($role == 'administrator'){ ?>
				<div class="col-md-3">
					<a href="<?php echo $this->request->base;?>/StaffMembers/addStaff" class="btn btn-flat btn-default"><?php echo __("Add Staff Member");?></a>
				</div>
				<?php } ?>
			</div>
			<div class='form-group'>
				<label class="control-label col-md-3" for="email"><?php echo __("Select Membership");?><span class="text-danger"> *</span></label>
				<div class="col-md-6 module_padding">
				<?php 
					echo $this->Form->select("membership_id",$membership,["default"=>($edit)?$data['membership_ids']:"","multiple"=>"multiple","class"=>"validate[required] form-control membership_list","id"=>"membership"]);
				?>
				</div>
				<div class="col-md-3">
					<a href="<?php echo $this->request->base;?>/Membership/add" class="btn btn-flat btn-default"><?php echo __("Add Membership");?></a>
				</div>			
			</div>

			<?php 
				if(!$edit)
				{?>
					<div id="video" class="video_div">			
						<div class="form-group">
							<label class="control-label col-md-3" for="email"><?php echo __("Add Youtube Embaded Link");?></label>
						
							<div class="col-md-6 module_padding">
							<?php echo $this->Form->textarea("video[]",["rows"=>"5","cols"=>"70","class"=>"form-control resize"]); ?>
							</div>						
							<div class="col-md-2">
								<button type="button" class="btn btn-flat btn-default" onclick="deleteParentElement(this)">
								<i class="entypo-trash"><?php echo __("Delete"); ?></i>
								</button>
							</div>
						</div>				
					</div>		
				<?php 
					}else
					{
						$entries = json_decode($video['video']);
						if($video == NULL){ ?>
							<div id="video" class="video_div">			
								<div class="form-group">
									<label class="control-label col-md-3" for="email"><?php echo __("Add Youtube Embaded Link");?></label>
								
									<div class="col-md-6 module_padding">
									<?php echo $this->Form->textarea("video[]",["rows"=>"5","cols"=>"70","class"=>"form-control resize"]); ?>
									</div>						
									<div class="col-md-2">
										<button type="button" class="btn btn-flat btn-default" onclick="deleteParentElement(this)">
										<i class="entypo-trash"><?php echo __("Delete"); ?></i>
										</button>
									</div>
								</div>				
							</div>
						<?php }
						if(!empty($entries))
						{
							foreach($entries as $entry)
							{
							 ?>	
								<div id="video" class="video_div">			
									<div class="form-group">
										<label class="control-label col-md-3" for="email"><?php echo __("Add Youtube Embaded Link");?></label>
									
										<div class="col-md-6 module_padding">
										<?php echo $this->Form->textarea("video[]",["rows"=>"5","cols"=>"70","class"=>"form-control resize","value"=>($edit)?$entry:""]); ?>
										</div>						
										<div class="col-md-2">
											<button type="button" class="btn btn-flat btn-default" onclick="deleteParentElement(this)">
											<i class="entypo-trash"><?php echo __("Delete"); ?></i>
											</button>
										</div>
									</div>				
								</div>	
					  <?php }
						}
					}
				?>	
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-3">	
						<input type="hidden" id="count" value="1">			
						<button id="add_new_entry" class="btn btn-flat btn-default btn-sm btn-icon icon-left " type="button" name="add_new_entry" onclick="add_entry()"><?php echo __("Add More");?></button>
					</div>
				</div>	
			<?php 
			echo $this->Form->button(__("Save Activity"),['class'=>"col-md-offset-3 btn btn-flat btn-success","name"=>"add_activity"]);
					
			echo $this->Form->end();?>
		<br><br>
		</div>
	</div>
</section>
<script>
// CREATING BLANK INVOICE ENTRY
var blank_income_entry ='';
$(document).ready(function() { 
	blank_income_entry = $('.video_div_empty:first').html();
}); 
function add_entry()
{
	var count = document.getElementById('count').value;
	count++;
	$("#count").val(count);
	
	if(count >= 1){
		$('.save').show();
	}else{
		$('.save').hide();
	}
	
	$(".video_div:last").append(blank_income_entry);
	$(".video_div:last").val("");
}

// REMOVING INVOICE ENTRY
function deleteParentElement(n){
	var count = document.getElementById('count').value;
	count--;
	$("#count").val(count);
	
	if(count >= 1){
		$('.save').show();
	}else{
		$('.save').hide();
	}
	n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
}
       </script> 