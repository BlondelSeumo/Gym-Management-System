<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
	});
});		
</script>
<?php
if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant")
{ ?>
<script>

$(document).ready(function(){
	var table = $(".mydataTable").DataTable();
  
});
</script>
<?php } ?>

<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Product List");?>
				<small><?php echo __("Products");?></small>
			  </h1>
			  <?php
			if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant")
			{ ?>
			  <ol class="breadcrumb">				
				<a href="<?php echo $this->Gym->createurl("GymProduct","addProduct");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Product");?></a>
			  </ol>
		<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<table class="mydataTable table table-striped" width="100%">
			<thead>
				<tr>
					<th><?php echo __("Product Name");?></th>
					<th><?php echo __("Product Price");?></th>
					<th><?php echo __("Product Quantity");?></th>
					<?php if($session["role_name"] != "member"){ ?>
					<th><?php echo __("Action");?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($data as $row)
			{
				echo "<tr>";
				echo "<td>{$row['product_name']}</td>
					  <td>".$this->Gym->get_currency_symbol()." {$row['price']}</td>
					  <td>{$row['quantity']}</td>";
				if($session["role_name"] != "member"){	
				echo "<td>
						<a href='".$this->request->base ."/GymProduct/editProduct/{$row['id']}' class='btn1 btn btn-flat btn-primary' title='".__('Edit')."'><i class='fa fa-edit'></i></a>";
				if($role == 'administrator' || $role == "staff_member"){
				echo "<a href='{$this->request->base}/GymProduct/deleteProduct/{$row['id']}' class='btn1 btn btn-flat btn-danger' title='".__('Delete')."' onclick=\"return confirm('Are you sure you want to delete this product?')\"><i class='fa fa-trash'></i></a>"; 
				}				
				echo "</td>";
				}
				echo "</tr>";
			}
			?>
			<tfoot>
				<tr>
					<th><?php echo __("Product Name");?></th>
					<th><?php echo __("Product Price");?></th>
					<th><?php echo __("Product Quantity");?></th>
					<?php if($session["role_name"] != "member"){ ?>
					<th><?php echo __("Action");?></th>
					<?php } ?>
				</tr>
			</tfoot>	
			</table>
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
