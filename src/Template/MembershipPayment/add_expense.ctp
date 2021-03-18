<script>
$(document).ready(function(){
	$(".date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
	<?php if($edit){ ?>
		$( ".date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($data['invoice_date'])); ?>" ));
	<?php } ?>
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
				<h1>
					<i class="fa fa-plus"></i>
					<?php if($edit){
							echo __("Edit Expense");
						}else{
							echo __("Add Expense");
						}
					?>
					<small><?php echo __("Expense");?></small>
				</h1>
				<ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("MembershipPayment","expenseList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Expense List");?></a>
				</ol>
			</section>
		</div>
		<hr>
		<div class="box-body">		
		<form name="income_form" action="" method="post" class="form-horizontal validateForm" id="income_form">
        <input type="hidden" name="invoice_type" value="expense">		
		<div class="form-group">
			<label class="col-md-2 control-label" for="day"><?php echo __("Supplier Name"); ?><span class="text-danger">*</span></label>
			<div class="col-md-8">
				<input id="supplier_name" class="form-control validate[required,custom[onlyLetterSp],maxSize[50]] text-input" type="text" value="<?php echo ($edit)?$data["supplier_name"]:"";?>" name="supplier_name">
			
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label" for="payment_status"><?php echo __("Status"); ?><span class="text-danger">*</span></label>
			<div class="col-md-8">
				<?php 
				$status = ["Paid"=>__("Paid"),"Part Paid"=>__("Part Paid"),"Unpaid"=>__("Unpaid")];
				echo $this->Form->select("payment_status",$status,["default"=>($edit)?$data["payment_status"]:"","class"=>"form-control"]);
				?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label" for="invoice_date"><?php echo __("Date"); ?><span class="text-danger">*</span></label>
			<div class="col-md-8">
				<input id="invoice_date" autocomplete="off" class="form-control  date" type="text" value="" name="invoice_date">
			</div>
		</div>
		<hr>
		<?php 
		if(!$edit)
		{?>
		<div id="income_entry" class="income_entry_div">			
			<div class="form-group">
				<label class="col-md-2 control-label" for="income_entry"><?php echo __("Expense Entry"); ?><span class="text-danger">*</span></label>
				<div class="col-md-2 module_padding">
					<input id="income_amount" class="form-control validate[required,custom[integer],maxSize[10]] text-input" type="text" value="" name="income_amount[]"  placeholder="<?php echo __("Expense Amount");?>">
				</div>
				<div class="col-md-4 module_padding">
					<input id="income_entry" class="form-control validate[required,maxSize[50]] text-input" type="text" value="" name="income_entry[]" placeholder="<?php echo __("Expense Entry Label");?>">
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
				$entries = json_decode($data["entry"]);
				foreach($entries as $entry)
				{?>
					<div id="income_entry" class="income_entry_div">
					<div class="form-group">
						<label class="col-md-2 control-label" for="income_entry"><?php echo __("Expense Entry"); ?><span class="text-danger">*</span></label>
						<div class="col-md-2 module_padding">
							<input id="" class="form-control validate[required,custom[integer],maxSize[10]] text-input income_amount" type="text" value="<?php echo $entry->amount;?>" name="income_amount[]" placeholder="<?php echo __("Expense Amount");?>">
						</div>
						<div class="col-md-4 module_padding">
							<input id="" class="form-control validate[required] text-input income_entry" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]" placeholder="<?php echo __("Expense Entry Label");?>">
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
		?>		
		<div class="form-group">
			<label class="col-md-2 control-label" for="income_entry"></label>
			<div class="col-md-3">
				<input type="hidden" id="count" value="1">				
				<button id="add_new_entry" class="btn btn-flat btn-default btn-sm btn-icon icon-left" type="button" name="add_new_entry" onclick="add_entry()"><?php echo __("Add Expense Entry");?>				</button>
			</div>
		</div>
		<hr>
		<div class="col-md-offset-2 col-md-8" style="padding-left: 5px;">
        	<input type="submit" value="<?php echo __("Create Expense Entry");?>" name="save_income" class="btn btn-flat btn-success save">
        </div>
        </form>
		
		
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
<script>
// CREATING BLANK INVOICE ENTRY
var blank_income_entry ='';
$(document).ready(function() { 
	blank_income_entry = $('.income_entry_div:last').html();	
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
	$(".income_entry_div:last").append(blank_income_entry);
	$(".income_amount:last").val("");
	$(".income_entry:last").val("");
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