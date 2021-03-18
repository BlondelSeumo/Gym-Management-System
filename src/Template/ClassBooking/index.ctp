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
.ui-datepicker th {
	font-size:13px;
}
.ui-datepicker td {
	font-size: 13px;
}
.form-control {
    height: 34px !important;
	font-size: 14px !important;
}
table tr {
	border-bottom : unset;
}
table thead tr th, table tfoot tr th, table tfoot tr td, table tbody tr th, table tbody tr td, table tr td {
	line-height:unset !important;
}
#form-head{
	color : #eee;
}
.ui-datepicker-title select{
Padding:0;
}

.book_fee{
	z-index: auto !important;
}

.gender{
	margin-left: 10px !important;
}
</style>

<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h3 id='form-head'>
				<i class="fa fa-user"></i>
				<?php echo __("Class Booking");?>
			  </h3>			  
			</section>
		</div>
		<div class="panel">
		<?php				
			echo $this->Form->create("",["type"=>"file","id"=>"booking_form","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<label 'id'='payment-errors' class=''></label>";
			echo "<fieldset><legend>". __('Personal Information')."</legend>";
			echo "<br>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="full_name">'. __("Full Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"full_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['full_name']:''),"id"=>'full_name']);
			echo "</div>";	
			echo "</div>";		
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="gender">'. __("Gender").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 checkbox">';
			$radio = [
						['value' => 'male', 'text' => __(' Male'),"class"=>"gender"],
						['value' => 'female', 'text' => __(' Female'),"class"=>"gender"]
					];
			echo $this->Form->radio("gender",$radio,['default'=>'male']);			
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="mobile">'. __("Mobile Number").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo '<div class="input-group">';
			echo '<div class="input-group-addon">+'.$this->Gym->getCountryCode($this->Gym->getSettings("country")).'</div>';
			echo $this->Form->input("",["label"=>false,"name"=>"mobile_no","class"=>"form-control validate[required,custom[onlyNumberSp],maxSize[15]]","value"=>(($edit)?$data['mobile_no']:''),"id"=>'mobile']);
			echo "</div>";	
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Email").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"email","class"=>"form-control validate[required,custom[email]]","value"=>(($edit)?$data['email']:''),"id"=>'email']);
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
			echo "<fieldset><legend>". __('Contact Information')."</legend>";
			echo "<br>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="address">'. __("Address").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"address","class"=>"form-control validate[required,maxSize[150]]","value"=>(($edit)?$data['address']:''),"id"=>'address']);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="city">'. __("City").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"city","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[20]]","value"=>(($edit)?$data['city']:''),"id"=>'city']);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="state">'. __("state").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"state","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[20]]","value"=>(($edit)?$data['state']:''),"id"=>'state']);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="zipcode">'. __("Zip code").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"zipcode","class"=>"form-control validate[required ,custom[onlyNumberSp],maxSize[10]]]","value"=>(($edit)?$data['zipcode']:''),"id"=>'zipcode']);
			echo "</div>";	
			echo "</div>";
			echo "</fieldset>";

			echo "<fieldset><legend>". __('Class Information')."</legend>";				
			echo "<br>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="class">'. __("Class").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';	

			echo $this->Form->select("class_id",$class,["default"=>($edit)?$data["class_id"]:"","empty"=>__("Select Class"),"class"=>"validation[required] form-control class",'required'=>'true','data-url'=>$this->request->base.'/ClassBooking/get_class_fees','id'=>'class_id']);
			echo "</div>";			
			echo "</div>";
			
			$book_type = [
						['value' => 'Demo', 'text' => __('Demo')],
						['value' => 'Paid Booking', 'text' => __('Paid Booking')]
					];
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="booking_type">'. __("Booking Type").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo $this->Form->select("booking_type",$book_type,["default"=>($edit)?$data["booking_type"]:"","empty"=>__("Select Booking Type"),"class"=>"validation[required] form-control book_type",'id'=>'booking_type']);
			echo "</div>";			
			echo "</div>";

			echo "<div class='form-group book_fee'>";	
			echo '<label class="control-label col-md-2" for="Booking_Fees">'. __("Class Booking Fees").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo "<div class='input-group'>";	
			echo "<span class='input-group-addon'>".$this->Gym->get_currency_symbol()."</span>";			
			echo "<input type='hidden' name='booking_amount' value='' class='amount'>"; 
			echo $this->Form->input("",["label"=>false,"name"=>"class_booking_fees","class"=>"form-control validate[required,custom[email]] class_booking_fees book_fee","value"=>(($edit)?$data['class_booking_fees']:''),"disabled","id"=>'booking_fees']);
			echo "</div>";			
			echo "</div>";
			echo "</div>";

			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="Booking Date">'. __("Booking Date").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo "<input type='hidden' id='abc'>";
			echo $this->Form->input("",["label"=>false,"name"=>"booking_date","class"=>"form-control dob validate[required] datepick","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['booking_date'])):''),"onkeydown"=>"return false",'id'=>'booking_date',"autocomplete"=>"off"]);
			echo "</div>";
			echo "</div>";
			echo "</fieldset>";

			$payment = [
						['value' => 'Paypal', 'text' => __('Paypal')],
						['value' => 'Stripe', 'text' => __('Stripe')]
					];
			echo "<div class='form-group payment'>";	
			echo '<label class="control-label col-md-2" for="class_type">'. __("Payment By").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo $this->Form->select("payment_by",$payment,["default"=>($edit)?$data["payment_by"]:"","empty"=>__("Select Payment"),"class"=>'form-control','id'=>'payment_by']);
			echo "</div>";			
			echo "</div>";
				
			echo "<br>";
			echo '<div class="form-group">';
			echo '<div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-2">';
			echo $this->Form->button(__("Save Booking"),['type'=>'button','id'=>'submit_button','class'=>"btn btn-flat btn-success submit_button","name"=>"save_booking"]);
			echo '<div class="stripe">
	        	 <script src="https://checkout.stripe.com/checkout.js" id="stripe" class="stripe-button"
		          data-key="'.$this->GYM->getSettings("stripe_publishable_key") .'"
		          data-description="Access for a year"
		          data-locale="auto"
		          ></script>
	      	</div>';
			echo "</div>";
			echo '<div class="col-md-5 col-sm-6 col-xs-6 pull-right">';
			echo "<a href='".$this->request->base ."/Users/' class='btn btn-success'>".__('Go Back')."</a>";
			echo '</div>';
			echo '</div>';
			echo $this->Form->end();
		?>
		</div>	
		
	</div>
</section>

<script>
$(document).ready(function(){
	$(".validateForm").validationEngine();
	
	var box_height = $(".box").height();
	var box_height = box_height + 500 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	//$(".datepick").datepicker({format: 'yyyy-mm-dd',"language" : "<?php echo $dtp_lang;?>"});
	/*$(".datepick").datepicker({yearRange: "+0:+0",changeYear: true,changeMonth: true, dateFormat:"<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" ,"language" : "<?php echo $dtp_lang;?>"});*/
		
	//$(".content-wrapper").css("height","2600px");

	$('.class').on('change',function(){
		var class_id = $(this).val();
		var url = $(this).attr('data-url');

		$.ajax({
			url : url,
			type : 'POST',
			data : {class_id:class_id},
			success : function(response){
				$('.class_booking_fees').val(response);
				$('.amount').val(response);
			}  
		});
		
	});

	$('.class').on('change',function(){
		var class_id = $(this).val();
		var url = '<?php  echo $this->request->base.'/ClassBooking/getClassDay';?>'

		$.ajax({
			url : url,
			type : 'POST',
			data : {class_id:class_id},
			success : function(response){
				var data = new Array();

				var days = JSON.parse(response);
				
				$('#abc').val(response.split(","));
				$('#booking_date').datepicker({
						yearRange: "+0:+0",
						changeYear: true,
						changeMonth: true,
						minDate : new Date(),
						dateFormat:"<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" ,
						"language" : "<?php echo $dtp_lang;?>",

						beforeShowDay:enableAllTheseDays 
					});	
			}  
		});
	});

	function enableAllTheseDays(date){
		var day = date.getDay();
				
		var dbdays = {
			    'Sunday' : 0,
			    'Monday' : 1,
			    'Tuesday' : 2,
			    'Wednesday' : 3,
			    'Thursday' : 4,
			    'Friday' : 5,
			    'Saturday' : 6
			};

			var enableDays = JSON.parse($('#abc').val());


			var a1 = [];
			var i = 0;

			var sdate = jQuery.datepicker.formatDate('yy-mm-dd', date);
      
	        if(jQuery.inArray(sdate, enableDays) != -1) {
				
	            return [true];
	        }
			return [false];
		
	}

	$('.book_fee').hide();
	$('.payment').hide();
	$('.stripe').hide();

	$('.book_type').on('change',function(){
		var book_type = $(this).val();

		if(book_type == 'Demo'){
			$('.book_fee').hide();
			$('.payment').hide();
			$('.stripe').hide();
			//$(".stripe").remove();
			//$("iframe").remove();
			$('#submit_button').addClass('submit_button');
			$('#submit_button').removeAttr("type").attr("type", "button");
			$('#stripe').removeClass('stripe-button active');
			
			$('.btn-flat').show();
		}else{
			$('.book_fee').show();
			$('.payment').show();
			/*$('.stripe').show();
			$('#submit_button').removeClass('submit_button');
			$('#submit_button').removeAttr("type").attr("type", "submit");
			$('.btn-flat').hide();
			$('#stripe').addClass('stripe-button active');*/
			$('#payment_by').addClass('validation[required]');
		}
	});
	
	$('#payment_by').on('change',function(){
		var payment = $(this).val();

		if(payment == 'Paypal'){
			
			$('.stripe').hide();
			$('#submit_button').addClass('submit_button');
			$('#submit_button').removeAttr("type").attr("type", "button");
			$('#stripe').removeClass('stripe-button active');
			
			$('.btn-flat').show();
		}else{
			$('.book_fee').show();
			$('.payment').show();
			$('.stripe').show();
			$('#submit_button').removeClass('submit_button');
			$('#submit_button').removeAttr("type").attr("type", "submit");
			$('.btn-flat').hide();
			$('#stripe').addClass('stripe-button active');
			$('#payment_by').addClass('validation[required]');
		}
	});

	$('.submit_button').on('click',function(){
		var class_id = $('#class_id').val();
		var booking_type = $('#booking_type').val();

		if(class_id != '' && booking_type != ''){
			$("#booking_form").submit();
		}else{
			alert('Select Class And Booking Type.')
		}
		
	});
});

function stripeResponseHandler(status, response){
 	if(response.error) {
 		$('#payment-errors').addClass('alert alert-danger');
 		$('#payment-errors').html(response.error.message);
 	}else{
 		var form$ = $('#class-booking');
 		var token = response['id'];

 		alert()
 		form$.append("<input type='hidden' name='stripeToken' value='"+token+"'>");
 		form$.get(0).submit();
 	}
 }

/*$(document).keydown(function(e){
    if(e.which === 123){
 
       return false;
 
    }
});
 $(document).bind("contextmenu",function(e) { 
	e.preventDefault();
 
});*/

function getDateFromDay(value){
	var today = new Date();
	//var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
	var date = today.setDate(today.getDate() + 30);
	//var date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
	
	console.log(date);
}
</script>