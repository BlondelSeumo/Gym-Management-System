<?php
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
$session = $this->request->session();
$dtp_lang = $session->read("User.dtp_lang");
?>
<script>
$(document).ready(function(){
$(".mem_list").select2();

$(".mem_valid_from").datepicker( "option", "dateFormat", "<?php echo $this->Gym->get_db_format($this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format"))); ?>" );
<?php
if($edit)
{?>
$( ".mem_valid_from" ).datepicker( "setDate", new Date("<?php echo $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['start_date']))); ?>" ));
<?php } ?>

$(".mem_valid_from").on("change",function(ev){
				
				var ajaxurl = $("#mem_date_check_path").val();
				var date = ev.target.value;	
				//alert(ajaxurl);
				var membership = $(".gen_membership_id option:selected").val();			
				if(membership != "")
				{
					var curr_data = { date : date, membership:membership};
					$(".valid_to").val("Calculatind date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response){
								
								/* moment.locale('<?php echo $dtp_lang;?>');
								var march = moment(response);
								var formate='<?php echo $this->Gym->dateformat_PHP_to_jQueryformate($this->Gym->getSettings("date_format")); ?>';
										
								$(".valid_to").val(march.format(formate)); */
								//$(".valid_to").val(response);	
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
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
				<h1>
					<i class="fa fa-plus"></i>
					<?php echo __("Generate Payment Invoice");?>
					<small><?php echo __("Payment");?></small>
				</h1>
				<ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("MembershipPayment","paymentList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Membership Payment List");?></a>
				</ol>
			</section>
		</div>
		<hr>
		<div class="box-body">		
		<form name="payment_form" action="" method="post" class="form-horizontal validateForm" id="payment_form">
        <input type="hidden" name="action" value="insert">
		<input type="hidden" name="mp_id" value="0">
		<input type="hidden" name="created_by" value="1">
		<div class="form-group">
			<label class="col-md-2 control-label" for="day"><?php echo __("Member");?><span class="text-danger">*</span></label>	
			<div class="col-md-8">
				<?php
				if($edit)
				{
					echo $this->Form->input("",["type"=>"hidden","name"=>"user_id","label"=>false,"class"=>"form-control","value"=>$data["member_id"]]);
				}
				echo $this->Form->select("user_id",$members,["default"=>($edit)?$data["member_id"]:"","empty"=>__("Select Member"),"class"=>"mem_list","required"=>"true",($edit)?"disabled":""]);
				?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label" for="membership"><?php echo __("Membership");?><span class="text-danger">*</span></label>
			<div class="col-md-8">
				<?php echo $this->Form->select("membership_id",$membership,["default"=>($edit)?$data["membership_id"]:"","empty"=>__("Select Membership"),"class"=>"form-control gen_membership_id","data-url"=>$this->request->base . "/GymAjax/get_amount_by_memberships"]);?>		
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label" for="total_amount"><?php echo __("Total Amount");?><span class="text-danger">*</span></label>
			<div class="col-md-8">
				<div class='input-group'>
					<span class='input-group-addon'><?php echo $this->Gym->get_currency_symbol();?></span>
					<input id="total_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit)?$data["membership_amount"]:"";?>" name="membership_amount" readonly="">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label" for="begin_date"><?php echo __("Membership Valid From");?><span class="text-danger">*</span></label>
			<div class="col-md-3">
				<?php echo $this->Form->input("",["autocomplete"=>"off","label"=>false,"name"=>"membership_valid_from","class"=>"form-control validate[required] mem_valid_from","value"=>($edit)?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data["start_date"]))):""]); ?>				
			</div>
			<div class="col-md-1 text-center">	<?php echo __("To");?>			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input("",["label"=>false,"name"=>"membership_valid","class"=>"form-control validate[required] valid_to","value"=>(($edit)?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['end_date']))):''),"readonly"=>true]);
				?>
				<input type='hidden' name='membership_valid_to' class='check' value='<?php ($edit && $data['end_date']!="")?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['end_date']))):''?>'>
			</div>
		</div>		
		<div class="col-md-offset-2 col-md-8">
        	<input type="submit" value="Save" name="<?php echo __("save_membership_payment");?>" class="btn btn-flat btn-success">
        </div>
		</form>
			
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_end_date" id="mem_date_check_path">
		
		
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>