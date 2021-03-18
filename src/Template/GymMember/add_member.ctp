<?php
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
$session = $this->request->session();
$dtp_lang = $session->read("User.dtp_lang");

$inquiry_date = (($edit)?$data['inquiry_date']:'');
$i_date = date("Y-m-d",strtotime($inquiry_date));
$inquiry_date = (($i_date != '1970-01-01' && $inquiry_date != '' )?$inquiry_date:'');

$trial_end_date = (($edit)?$data['trial_end_date']:'');
$t_date = date("Y-m-d",strtotime($trial_end_date));
$trial_end_date = (($t_date != '1970-01-01' && $trial_end_date != '' )?$trial_end_date:'');

$first_pay_date = (($edit)?$data['first_pay_date']:'');
$p_date = date("Y-m-d",strtotime($first_pay_date));
$first_pay_date = (($p_date != '1970-01-01' && $first_pay_date != '' )?$first_pay_date:'');
$assign_group = json_encode(($edit)?$member_class:'');

?>
<script type="text/javascript">
$(document).ready(function() {
	var m_id = $('.membership_id').val();
	var ajaxurl = $("#mem_class_url").val();
	var group = <?php echo $assign_group; ?>;
	var curr_data = { m_id : m_id,group:group};
	
	$(".class_list").html("");
	$.ajax({
		url : ajaxurl,
		type : "POST",
		data : curr_data,
		success : function(response){
			$(".class_list").append(response);
			$(".class_list").multiselect("rebuild");
			
			return false;
		},
		error : function(e){

			console.log(e.responseText);
		}
	});


	$('.inquiry_date').on('change',function(){
		var date = $('.inquiry_date').datepicker('getDate'); 
		date.setDate(date.getDate());
			$(".trial_end_date").datepicker("option","minDate", date); 
	})
});
$(document).ready(function() {
	
	<?php
	if($edit)
	{
	$date = $data['birth_date'];
	$birthday = $date->format($this->Gym->getSettings("date_format"));	
	//$birthday = $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format"));	
	
	?>
	$(".valid_datepicker").datepicker("setDate")
	$( ".dob" ).datepicker( "setDate", new Date("<?php echo $birthday; ?>" ));
	
	<?php } ?>
	<?php 
	if($edit && $inquiry_date != '')
	{
		?>
		$( ".inquiry_date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($inquiry_date)); ?>" )
		);

	<?php } ?>
	
	<?php 
	if($edit && $trial_end_date != '')
	{ 
		?>
		$( ".trial_end_date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($trial_end_date)); ?>" ));
 
	<?php } ?>
	
	<?php 
	if($edit && $first_pay_date != '')
	{?>
	$( ".first_pay_date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($first_pay_date)); ?>" ));
	<?php } ?>
	
	$('.group_list').multiselect({
		includeSelectAllOption: true	
	});
	
	var box_height = $(".box").height();
	var box_height = box_height + 100 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	$('.class_list').multiselect({
		includeSelectAllOption: true		
	});
	
	
	$(".mem_valid_from").on("change",function(ev){
				
		var ajaxurl = $("#mem_date_check_path").val();
		
		var date = $('.mem_valid_from').datepicker('getDate');  
		date.setDate(date.getDate());
		date1=formatDate(date);
	
		var membership = $(".membership_id option:selected").val();	
		
		if(membership != "")
		{
			var curr_data = { date : date1, membership:membership};
			$(".valid_to").val("Calculating date..");
			$.ajax({
				url :ajaxurl,
				type : 'POST',
				data : curr_data,
				success : function(response)
				{
			
					$(".valid_to,.check").datepicker({ language: "<?php echo $dtp_lang;?>",
						 dateFormat :"<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>",
						 
					});
					$(".valid_to,.check").datepicker($.datepicker.regional['<?php echo $dtp_lang;?>']);
					$( ".valid_to,.check" ).datepicker( "setDate",  new Date(response) );
				}
			});
		}else{
			$(".valid_to").val("Select Membership");
		}
	});		
});

function validate_multiselect()
{		
		var classes = $("#class_list").val();
		if(classes == null)
		{
			alert("Please Select Class or Add class class first.");
			return false;
		}else{
			return true;
		}		
}
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

</script>

<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-user"></i>
				<?php echo $title;
				
				?>
				<small><?php echo __("Member");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymMember","memberList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Members List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php	

			echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","onsubmit"=>"return validate_multiselect()"]);
			echo "<fieldset><legend>". __('Personal Information')."</legend>";
						
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="member_id">'. __("Member ID").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"member_id","class"=>"form-control","disabled"=>"disabled","value"=>(($edit)?$data['member_id']:$member_id),"id"=>"member_id"]);
			echo "</div>";	
			echo "</div>";
			
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="first_name">'. __("First Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['first_name']:''),"id"=>"first_name"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="middle_name">'. __("Middle Name").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"middle_name","class"=>"form-control validate[custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['middle_name']:''),"id"=>"middle_name"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="last_name">'. __("Last Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"last_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['last_name']:''),"id"=>"last_name"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="gender">'. __("Gender").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 checkbox">';
			$radio = [
						['value' => 'male', 'text' => __('Male'),"class"=>"gender"],
						['value' => 'female', 'text' => __('Female'),"class"=>"gender"]
					];
			echo $this->Form->radio("gender",$radio,['default'=>($edit)?$data["gender"]:"male"]);			
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="birth_date">'. __("Date of birth").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"birth_date","class"=>"dob form-control  validate[required] ","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['birth_date'])):''),"id"=>"birth_date","autocomplete"=>"off"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo '<label class="control-label col-md-2" for="assign_group">'. __("Group").'</label>';
			echo '<div class="col-md-6 module_padding">';			
			echo @$this->Form->select("assign_group",$groups,["default"=>json_decode($data['assign_group']),"multiple"=>"multiple","class"=>"form-control group_list","id"=>"assign_group"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/GymGroup/addGroup/' class='btn btn-flat btn-default'>".__("Add Group")."</a>";
			echo "</div>";	
			echo "</div>";
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('Contact Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="address">'. __("Address").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"address","class"=>"form-control validate[required,maxSize[150]]","value"=>(($edit)?$data['address']:''),"id"=>"address"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="city">'. __("City").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"city","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[20]]","value"=>(($edit)?$data['city']:''),"id"=>"city"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="state">'. __("State").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"state","class"=>"form-control validate[custom[onlyLetterSp],maxSize[20]]","value"=>(($edit)?$data['state']:''),"id"=>"state"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="zipcode">'. __("Zip code").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"zipcode","class"=>"form-control validate[required,custom[onlyNumberSp],maxSize[10]]","value"=>(($edit)?$data['zipcode']:''),"id"=>"zipcode"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="mobile">'. __("Mobile Number").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo '<div class="input-group">';
			echo '<div class="input-group-addon">+'.$this->Gym->getCountryCode($this->Gym->getSettings("country")).'</div>';
			echo $this->Form->input("",["label"=>false,"name"=>"mobile","class"=>"form-control validate[required,custom[onlyNumberSp],maxSize[15]]","value"=>(($edit)?$data['mobile']:''),"id"=>"mobile"]);
			echo "</div>";	
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="phone">'. __("Phone").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"phone","class"=>"form-control validate[custom[onlyNumberSp],maxSize[15]]","value"=>(($edit)?$data['phone']:''),"id"=>"phone"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Email").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"email","class"=>"form-control validate[required,custom[email]]","value"=>(($edit)?$data['email']:''),"id"=>"email"]);
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
					
			echo "<fieldset><legend>". __('Physical Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="weight">'. __("Weight").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"weight","class"=>"form-control validate[custom[onlyNumberSp],maxSize[3]]","placeholder"=>$this->Gym->getSettings("weight"),"value"=>(($edit)?$data['weight']:''),"id"=>"weight"]);
			echo "</div>";	
			echo "</div>";

			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="height">'. __("Height").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"height","class"=>"form-control validate[custom[onlyNumberSp],maxSize[3]]","placeholder"=>__($this->Gym->getSettings("height")),"value"=>(($edit)?$data['height']:''),"id"=>"height"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="chest">'. __("Chest").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"chest","class"=>"form-control validate[custom[onlyNumberSp],maxSize[3]]","placeholder"=>__($this->Gym->getSettings("chest")),"value"=>(($edit)?$data['chest']:''),"id"=>"chest"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="waist">'. __("Waist").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"waist","class"=>"form-control validate[custom[onlyNumberSp],maxSize[3]]","placeholder"=>__($this->Gym->getSettings("waist")),"value"=>(($edit)?$data['waist']:''),"id"=>"waist"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="thing">'. __("Thigh").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"thing","class"=>"form-control validate[custom[onlyNumberSp],maxSize[3]]","placeholder"=>__($this->Gym->getSettings("thing")),"value"=>(($edit)?$data['thing']:''),"id"=>"thing"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="arms">'. __("Arms").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"arms","class"=>"form-control validate[custom[onlyNumberSp],maxSize[3]]","placeholder"=>__($this->Gym->getSettings("arms")),"value"=>(($edit)?$data['arms']:''),"id"=>"arms"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="fat">'. __("Fat").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"fat","class"=>"form-control validate[custom[onlyNumberSp],maxSize[3]]","placeholder"=>__($this->Gym->getSettings("fat")),"value"=>(($edit)?$data['fat']:''),"id"=>"fat"]);
			echo "</div>";	
			echo "</div>";	
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('Login Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="username">'. __("Username").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"username","class"=>"form-control validate[required]","value"=>(($edit)?$data['username']:''),"readonly"=> (($edit)?true:false),"id"=>"username"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="password">'. __("Password").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->password("",["label"=>false,"name"=>"password","class"=>"form-control validate[required]","value"=>(($edit)?$data['password']:''),"id"=>"password"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="image">'. __("Display Image").'</label>';
			echo '<div class="col-md-4">';
			echo $this->Form->file("image",["class"=>"form-control"]);
			$image = ($edit && !empty($data['image'])) ? $data['image'] : "Thumbnail-img.png";
			echo "<br><img src='{$this->request->webroot}webroot/upload/{$image}' class='img-responsive'>";
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
								
			echo "<fieldset><legend>". __('More Information')."</legend>";			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="assign_staff_mem">'. __("Select Staff Member").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 module_padding">';			
			echo @$this->Form->select("assign_staff_mem",$staff,["default"=>$data['assign_staff_mem'],"empty"=>__("Select Staff Member"),"class"=>"form-control validate[required]","id"=>"assign_staff_mem"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/StaffMembers/addStaff/' class='btn btn-flat btn-default'>".__("Add Staff")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="intrested_area">'. __("Interested Area").'</label>';
			echo '<div class="col-md-6 module_padding">';			
			echo @$this->Form->select("intrested_area",$interest,["default"=>$data['intrested_area'],"empty"=>__("Select Interest"),"class"=>"form-control interest_list","id"=>"intrested_area"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='javascript:void(0)' class='btn btn-flat btn-default interest-list' data-url='{$this->request->base}/GymAjax/interestList'>".__("Add/Remove")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="g_source">'. __("Source").'</label>';
			echo '<div class="col-md-6 module_padding">';			
			echo @$this->Form->select("g_source",$source,["default"=>$data['g_source'],"empty"=>__("Select Source"),"class"=>"form-control source_list","id"=>"g_source"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='javascript:void(0)' class='btn btn-flat btn-default source-list' data-url='{$this->request->base}/GymAjax/sourceList'>".__("Add/Remove")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="referrer_by">'. __("Referred By").'</label>';
			echo '<div class="col-md-6 module_padding">';			
			echo @$this->Form->select("referrer_by",$referrer_by,["default"=>$data['referrer_by'],"empty"=>__("Select Staff Member"),"class"=>"form-control","id"=>"referrer_by"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/StaffMembers/addStaff/' class='btn btn-flat btn-default'>".__("Add Staff")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Inquiry Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"inquiry_date","class"=>"form-control inquiry_date date","value"=>$inquiry_date,"autocomplete"=>"off"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Trial End Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"trial_end_date","class"=>"form-control trial_end_date date","value"=>$trial_end_date,"autocomplete"=>"off"]);
			echo "</div>";	
			echo "</div>";
			?>
			
			<div class="form-group">
				<div class="control-label col-md-2">
					<label><?php echo __("Member Type");?></label>
				</div>
				<div class="col-md-6">
					<label class="radio-inline"><input type="radio" name="member_type" value="Member" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Member") ? "checked":"checked";?>><?php echo __("Member");?></label>
					<label class="radio-inline"><input type="radio" name="member_type" value="Prospect" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Prospect") ? "checked":"";?>><?php echo __("Prospect");?></label>
					<label class="radio-inline"><input type="radio" name="member_type" value="Alumni" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Alumni") ? "checked":"";?>><?php echo __("Alumni");?></label>
				</div>
			</div>	
			
			<?php
			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 module_padding">';			
			echo @$this->Form->select("selected_membership",$membership,["default"=>$data['selected_membership'],"empty"=>__("Select Membership"),"class"=>"form-control validate[required] membership_id"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/Membership/add/' class='btn btn-flat btn-default'>".__("Add Membership")."</a>";
			echo "</div>";	
			echo "</div>";				
					
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Class").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 module_padding">';

			//debug($member_class);die;
			echo $this->Form->select("assign_class",($edit)?$classes:"",["default"=>($edit)?$member_class:"","class"=>"class_list form-control","id"=>"class_list","multiple"=>"multiple"]);
			echo "</div>";		
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/ClassSchedule/addClass/' class='btn btn-flat btn-default'>".__("Add Class")."</a>";
			echo "</div>";	
			echo "</div>";
			
			if($edit)
			{
			?>
				<div class="form-group">
					<div class="control-label col-md-2">
						<label><?php echo __("Membership Status");?></label>
					</div>
					<div class="col-md-6">
						<label class="radio-inline"><input type="radio" name="membership_status" value="Prospect" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Prospect") ? "checked":"";?>><?php echo __("Prospect");?></label>
						<label class="radio-inline"><input type="radio" name="membership_status" value="Continue" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Continue") ? "checked":"";?>><?php echo __("Continue");?></label>
						<label class="radio-inline"><input type="radio" name="membership_status" value="Expired" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Expired") ? "checked":"";?>><?php echo __("Expired");?></label>
						<label class="radio-inline"><input type="radio" name="membership_status" value="Dropped" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Dropped") ? "checked":"";?>><?php echo __("Dropped");?></label>
					</div>
				</div>	
			<?php
			}
			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership Valid From").'<span class="text-danger">*</span></label>';
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["autocomplete"=>"off","label"=>false,"name"=>"membership_valid_from","class"=>"form-control validate[required] date mem_valid_from","value"=>(($edit && $data['membership_valid_from']!="")?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_from']))):'')]);
			
			echo "</div>";
			echo '<div class="col-md-1 no-padding text-center">';
			echo "To";
			echo "</div>";
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"membership_valid","class"=>"form-control validate[required] valid_to","value"=>(($edit && $data['membership_valid_to']!="")?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_to']))):''),"disabled"=>true]);?>
			<input type='hidden' name='membership_valid_to' class='check' value='<?php echo ($edit && $data['membership_valid_to']!="")?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_to']))):''?>'>
			<?php echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("First Payment Date").'</label>';
			echo '<div class="col-md-6">';
			//echo $this->Form->input("",["label"=>false,"name"=>"first_pay_date","class"=>"form-control first_pay_date date","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['first_pay_date'])):'')]);
			echo $this->Form->input("",["label"=>false,"name"=>"first_pay_date","class"=>"form-control first_pay_date date","value"=>$first_pay_date,"autocomplete"=>"off"]);
			echo "</div>";	
			echo "</div>";
			echo "</fieldset>";
			
			echo "<br>";
			echo $this->Form->button(__("Save Member"),['class'=>"col-md-offset-2 btn btn-flat btn-success","name"=>"add_member"]);
			echo $this->Form->end();
		?>
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_end_date" id="mem_date_check_path">
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_classes" id="mem_class_url">
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
 <script>
$(".membership_status_type").change(function(){
	if($(this).val() == "Prospect" || $(this).val() == "Alumni" )
	{
		$(".class-member").hide("SlideDown");
		$(".class-member input,.class-member select").attr("disabled", "disabled");				
	}else{
		$(".class-member").show("SlideUp");
		$(".class-member input,.class-member select").removeAttr("disabled");	
		$("#available_classes").attr("disabled", "disabled");
	}
});
if($(".membership_status_type:checked").val() == "Prospect" || $(".membership_status_type:checked").val() == "Alumni")
{ 
$(".class-member").hide("SlideDown");
$(".class-member input,.class-member select").attr("disabled", "disabled");		
}

	
</script>