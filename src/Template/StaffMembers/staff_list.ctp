<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	var table = $(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
	}); 
});		
</script>
<?php
if($session["role_name"] == "administrator")
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
				<?php echo __("Staff Member List");?>
				<small><?php echo __("Staff Member");?></small>
			  </h1>
			  <?php
				if($session["role_name"] == "administrator")
				{ ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("StaffMembers","addStaff");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Staff Member");?></a>
			  </ol>
			  <?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<table class="mydataTable table table-striped" width="100%">
				<thead>
					<tr>
						<th><?php echo __("Photo");?></th>
						<th><?php echo __("Staff Member Name");?></th>
						<th><?php echo __("Role");?></th>					
						<th><?php echo __("Staff Member Email");?></th>					
						<th><?php echo __("Mobile No.");?></th>	
						<?php if($session['role_name'] == "administrator"){ ?>
						<th><?php echo __("Action");?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
				<?php			
				foreach($data as $row)
				{				
					echo "<tr>";					
					echo "<td><img src='".$this->request->webroot ."upload/{$row['image']}' class='membership-img img-circle'></img></td>";
					echo "<td>{$row['first_name']} {$row['last_name']}</td>";
					echo "<td>{$row['gym_role']['name']}</td>";
					echo "<td class='emailid'>{$row['email']}</td>";
					echo "<td>{$row['mobile']}</td>";
					
					if($session['role_name'] == "administrator"){
						$confirmMsg = __("Are you sure,You want to delete this record?");
					echo "<td>
						<a href='".$this->Gym->createurl('StaffMembers','editStaff')."/{$row['id']}' class='btn btn-flat btn-primary' title='Edit'><i class='fa fa-edit'></i></a>
						<a href='".$this->Gym->createurl('StaffMembers','deleteStaff')."/{$row['id']}' class='btn btn-flat btn-danger' title='Delete' onClick=\"return confirm('$confirmMsg')\"><i class='fa fa-trash-o'></i></a>
						</td>";
					}
					echo "</tr>";
				} 
				?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php echo __("Photo");?></th>
						<th><?php echo __("Staff Member Name");?></th>
						<th><?php echo __("Role");?></th>					
						<th><?php echo __("Staff Member Email");?></th>					
						<th><?php echo __("Mobile No.");?></th>	
						<?php if($session['role_name'] == "administrator"){ ?>
						<th><?php echo __("Action");?></th>
						<?php } ?>
					</tr>
				</tfoot>
			</table>
		</div>	
		<div class="overlay gym-overlay">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>