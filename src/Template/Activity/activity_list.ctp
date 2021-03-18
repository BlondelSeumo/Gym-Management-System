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
		var msg = "<?php echo __("Are you sure,You want to delete this record?") ?>";
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
				<?php echo __("Activity List");?>
				<small>&nbsp;<?php echo __("Activity");?></small>
			  </h1>
			  <?php
				if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant")
				{ ?>
				  <ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("Activity","addActivity");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Activity");?></a>
				  </ol>
				<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<table class="mydataTable table table-striped" width="100%">
				<thead>
					<tr>
						<th><?php echo __("Activity Name");?></th>
						<th><?php echo __("Activity Category");?></th>
						<th><?php echo __("Activity Trainer");?></th>
						<th><?php echo __("Action");?></th>
						
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($data as $row)
						{
							echo "<tr>
									<td>{$row['title']}</td>
									<td>{$row['category']['name']}</td>
									<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>
									";
									
								echo "<td>
										<a href='{$this->request->base}/Activity/viewActivity/{$row['id']}' title='View' class='btn btn-flat btn-info'><i class='fa fa-eye'></i></a>&nbsp;";
										if($session["role_name"] != "member"){
										echo "<a href='{$this->request->base}/Activity/editActivity/{$row['id']}' title='Edit' class='btn btn-flat btn-primary'><i class='fa fa-edit'></i></a>
										<a href='{$this->request->base}/Activity/deleteActivity/{$row['id']}' title='Delete' class='btn btn-flat btn-danger' onClick=\"return confirm(msg);\"><i class='fa fa-trash-o'></i></a>";						
										}
									"</td>";		
								
							echo "</tr>";
						}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php echo __("Activity Name");?></th>
						<th><?php echo __("Activity Category");?></th>
						<th><?php echo __("Activity Trainer");?></th>
						
						<th><?php echo __("Action");?></th>
						
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</section>