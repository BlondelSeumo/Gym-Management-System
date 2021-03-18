<?php
echo $this->Html->script('jQuery/jQuery-2.1.4.min.js');
echo $this->Html->script('jquery-ui.min');
echo $this->Html->css('bootstrap.min');
echo $this->Html->css('jquery-ui.css');	
$is_rtl = $this->Gym->getSettings("enable_rtl");
if($is_rtl)
{
	echo $this->Html->css('bootstrap-rtl.min');
}
echo $this->Html->script('bootstrap/js/bootstrap.min.js');
//echo $this->Html->css('plugins/datepicker/datepicker3');
//echo $this->Html->script('datepicker/bootstrap-datepicker.js');
$dtp_lang = $this->gym->getSettings("datepicker_lang");
//echo $this->Html->script("datepicker/locales/bootstrap-datepicker.{$dtp_lang}");
echo $this->Html->script("jQueryUI/ui/i18n/datepicker-{$dtp_lang}.js");
//echo $this->Html->css('bootstrap-datepicker.css');
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
echo $this->Html->css('validationEngine/validationEngine.jquery');
echo $this->Html->script('validationEngine/languages/jquery.validationEngine-en');
echo $this->Html->script('validationEngine/jquery.validationEngine'); 
?>
<style>
.content{   
   padding-bottom: 0;
}

body *{
	    font-family: "Roboto", sans-serif;
}
.datepicker.dropdown-menu {   
    max-width: 300px;
}
.form-control {
    height: 34px !important;
	font-size: 14px !important;
}
#form-head{
	color : #eee;
}
.ui-datepicker-title select{
Padding:0;
}
</style>
<script type="text/javascript">
$(document).ready(function() {	
$(".validateForm").validationEngine();
	$('.group_list').multiselect({
		includeSelectAllOption: true	
	});
	
	var box_height = $(".box").height();
	var box_height = box_height + 500 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	$('.class_list').multiselect({
		includeSelectAllOption: true	
	});
	$(".dob").datepicker({yearRange: "-100:+0",changeYear: true,changeMonth: true, dateFormat:"<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" ,"language" : "<?php echo $dtp_lang;?>"});
	//$(".datepick").datepicker({format: 'yyyy-mm-dd',"language" : "<?php echo $dtp_lang;?>"});
	$(".datepick").datepicker({dateFormat:"<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" ,"language" : "<?php echo $dtp_lang;?>",});
		
	$(".content-wrapper").css("height","2600px");
	
	$(".mem_valid_from").datepicker({yearRange: "-100:+0",changeYear: true,changeMonth: true,dateFormat: "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" ,"language" : "<?php echo $dtp_lang;?>"}).on("change",function(ev){
				var ajaxurl = $("#mem_date_check_path").val();
				var date = ev.target.value;	
				
				var membership = $(".membership_id option:selected").val();		
				if(membership != "")
				{
					var curr_data = { date : date, membership:membership};
					$(".valid_to").val("Calculatind date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response)
									{
										$(".valid_to,.check").datepicker({ language: "<?php echo $dtp_lang;?>",
											 dateFormat :"<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>",
											 
											}
											);
											$(".valid_to,.check").datepicker($.datepicker.regional['<?php echo $dtp_lang;?>']);
											$( ".valid_to,.check" ).datepicker( "setDate",  new Date(response) );
										//$(".valid_to").val(response);
										
									},
							error: function(e){
									console.log(e.responseText);
							}
						});
				}else{
					$(".valid_to").val("Select Membership");
				}
			});	
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h3 id='form-head'>
				<i class="fa fa-user"></i>
				<?php echo __("Member Registration");?>
			  </h3>			  
			</section>
		</div>
		<div class="panel">
		<?php				
			echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<fieldset><legend>". __('Personal Information')."</legend>";
			echo "<br>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Member ID").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"member_id","class"=>"form-control","disabled"=>"disabled","value"=>(($edit)?$data['member_id']:$member_id)]);
			echo "</div>";	
			echo "</div>";
			
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("First Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['first_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Middle Name").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"middle_name","class"=>"form-control validate[custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['middle_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Last Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"last_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[30]]]","value"=>(($edit)?$data['last_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Gender").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 checkbox">';
			$radio = [
						['value' => 'male', 'text' => 'Male'],
						['value' => 'female', 'text' => 'Female']
					];
			echo $this->Form->radio("gender",$radio,['default'=>'male']);			
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Date of birth").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"birth_date","class"=>"form-control dob validate[required] datepick","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['birth_date'])):''),"onkeydown"=>"return false"]);
			echo "</div>";	
			echo "</div>";		
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Group").'</label>';
			echo '<div class="col-md-2">';			
			echo @$this->Form->select("assign_group",$groups,["default"=>json_decode($data['assign_group']),"multiple"=>"multiple","class"=>"form-control group_list"]);
			echo "</div>";				
			echo "</div>";
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('Contact Information')."</legend>";
			echo "<br>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Address").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"address","class"=>"form-control validate[required,maxSize[150]]","value"=>(($edit)?$data['address']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("City").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"city","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[20]]","value"=>(($edit)?$data['city']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("state").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"state","class"=>"form-control validate[custom[onlyLetterSp],maxSize[20]]","value"=>(($edit)?$data['state']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Zip code").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"zipcode","class"=>"form-control validate[required ,custom[onlyNumberSp],maxSize[10]]]","value"=>(($edit)?$data['zipcode']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Mobile Number").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo '<div class="input-group">';
			echo '<div class="input-group-addon">+'.$this->Gym->getCountryCode($this->Gym->getSettings("country")).'</div>';
			echo $this->Form->input("",["label"=>false,"name"=>"mobile","class"=>"form-control validate[required,custom[onlyNumberSp],maxSize[15]]","value"=>(($edit)?$data['mobile']:'')]);
			echo "</div>";	
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Phone").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"phone","class"=>"form-control validate[custom[onlyNumberSp],maxSize[15]]","value"=>(($edit)?$data['phone']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Email").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"email","class"=>"form-control validate[required,custom[email]]","value"=>(($edit)?$data['email']:'')]);
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('Login Information')."</legend>";
			echo "<br>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Username").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"username","class"=>"form-control validate[required]","value"=>(($edit)?$data['username']:''),"readonly"=> (($edit)?true:false)]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Password").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->password("",["label"=>false,"name"=>"password","class"=>"form-control validate[required]","value"=>(($edit)?$data['password']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Display Image").'</label>';
			echo '<div class="col-md-4">';
			echo $this->Form->file("image",["class"=>"form-control"]);
			$image = ($edit && !empty($data['image'])) ? $data['image'] : "Thumbnail-img.png";
			echo "<br><img src='{$this->request->webroot}webroot/upload/{$image}'>";
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
			echo "<fieldset><legend>". __('More Information')."</legend>";			
			echo "<br>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Interested Area").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("intrested_area",$interest,["default"=>$data['intrested_area'],"empty"=>__("Select Interest"),"class"=>"form-control interest_list"]);
			echo "</div>";				
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Source").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("g_source",$source,["default"=>$data['source'],"empty"=>__("Select Source"),"class"=>"form-control source_list"]);
			echo "</div>";				
			echo "</div>";
			
			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("selected_membership",$membership,["default"=>$data['selected_membership'],"empty"=>__("Select Membership"),"class"=>"form-control validate[required] membership_id"]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Class").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("assign_class","",["default"=>$member_class,"class"=>"class_list form-control validate[required]","multiple"=>"multiple"]);
			echo "</div>";			
			echo "</div>";
			
			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Joining Date").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"membership_valid_from","class"=>"form-control validate[required] mem_valid_from","value"=>(($edit && $data['membership_valid_from']!="")?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_from']))):'')]);
			echo "</div>";
			echo '<div class="col-md-1 no-padding text-center">';
			// echo "To";
			echo "</div>";
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["type"=>"hidden","label"=>false,"name"=>"membership_valid_to","class"=>"form-control validate[required] valid_to","value"=>(($edit && $data['membership_valid_to']!="")?date("Y-m-d",strtotime($data['membership_valid_to'])):''),"readonly"=>true]);?>
			<input type='hidden' name='membership_valid_to' class='check' value='<?php ($edit && $data['membership_valid_to']!="")?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_to']))):''?>'>
			<?php echo "</div>";
			echo "</div>";
			
			
			echo "</fieldset>";

				
			echo "<br>";
			echo '<div class="form-group">';
			echo '<div class="col-md-4 col-sm-6 col-xs-6">';
			echo $this->Form->button(__("Save Member"),['class'=>"col-md-offset-2 btn btn-flat btn-success","name"=>"add_member"]);
			echo "</div>";
			echo '<div class="col-md-5 col-sm-6 col-xs-6 pull-right">';
			echo "<a href='".$this->request->base ."/Users/' class='btn btn-success'>".__('Go Back')."</a>";
			echo '</div>';
			echo '</div>';
			echo $this->Form->end();
		?>
		<input type="hidden" value="<?php echo $this->request->base;?>/MemberRegistration/getMembershipEndDate/" id="mem_date_check_path">
		<input type="hidden" value="<?php echo $this->request->base;?>/MemberRegistration/getMembershipClasses" id="mem_class_url">
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
$("body").on("change",".membership_id",function(){
	var m_id = $(this).val();
	var ajaxurl = $("#mem_class_url").val();
	var curr_data = { m_id : m_id};
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
});
</script>