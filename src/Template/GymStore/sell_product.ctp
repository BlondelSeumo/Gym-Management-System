<?php $session = $this->request->session()->read("User");?>
<?php
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
?>
<script>
$(document).ready(function(){
	$(".sell-date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
	<?php
	if($edit){?>
	$( ".sell-date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($data['sell_date'])); ?>" ));
	<?php } ?>
	$(".mem_list").select2({
		containerCssClass: function(e) { 
     return $(e).attr('required') ? 'required' : '';
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
				<?php if($edit){
					echo __("Edit Sell Product");
				}else{
					echo __("Add Sell Product");
				}
				?>
				
				<small><?php echo __("Store");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymStore","sellRecord");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Sell Records");?></a>
			 </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">					
		<form class="validateForm form-horizontal" method="post" role="form">		
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Member");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
		<?php 
		echo $this->Form->select("member_id",$members,["default"=>($edit)?array($data['member_id']):"","class"=>"mem_list"]);
		?>
		</div>	
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Date");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
			<input type="text" name="sell_date" class="sell-date form-control validate[required]" value="<?php echo ($edit)?date($this->Gym->getSettings("date_format"),strtotime($data["sell_date"])) : "";?>">
		</div>	
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Product");?><span class="text-danger"> *</span></label>
		<div class="col-md-6 module_padding">
		<?php 
		echo $this->Form->select("product_id",$products,["empty"=>__("Select Product"),"default"=>($edit)?array($data['product_id']):"","class"=>"form-control validate[required]"]);
		?>
		</div>
		<?php
		if($session["role_name"] == "administrator" || $session['role_name'] == "staff_member")
		{?>
		<div class="col-md-3">
			<a href="<?php echo $this->request->base ."/GymProduct/addProduct";?>" class="btn btn-flat btn-default"><?php echo __("Add Product");?></a>
		</div>
	<?php } ?>
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Quantity");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
			<input type="text" name="quantity" class="form-control validate[required,custom[integer,min[0]]]" value="<?php echo ($edit)?$data["quantity"] : "";?>" maxlength="5">
		</div>
		<?php if($edit){ ?>
		<input type="hidden" name="old_quantity" class="form-control" value="<?php echo $data["quantity"];?>">
		<?php } ?>
		</div>
		<div class="col-md-offset-2 col-md-6 add_product_save">
			<input type="submit" value="<?php echo __("Save");?>" name="save_product" class="btn btn-flat btn-success">
		</div>
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
