<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php	if($edit){
							echo __("Edit Product");
						}else{
							echo __("Add Product");
						}
				?>
				
				<small><?php echo __("Products");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymProduct","productList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Product List");?></a>
			 </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">					
		<form class="validateForm form-horizontal" method="post" role="form">		
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Product Name");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
			<input type="text" name="product_name" class="form-control validate[required,custom[onlyLetterNumber]]" value="<?php echo ($edit)?$data["product_name"] : "";?>" maxlength="40">
		</div>	
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Product Price");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
			<div class='input-group'>
				<span class='input-group-addon'><?php echo $this->Gym->get_currency_symbol();?></span>
				<input type="text" name="price" class="form-control validate[required,custom[integer,min[0]]]" value="<?php echo ($edit)?$data["price"] : "";?>" maxlength = "10">
			</div>	
		</div>	
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Product Quantity");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
			<input type="text" name="quantity" class="form-control validate[required,custom[integer,min[0]]]" value="<?php echo ($edit)?$data["quantity"] : "";?>" maxlength = "5">
		</div>	
		</div>
		<div class="col-md-offset-2 col-md-6 add_product_save">
			<input type="submit" value="<?php echo __("Save");?>" name="save_product" class="btn btn-flat btn-success">
		</div>
		</form>
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
