<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                 
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                           
	                  {"bSortable": false,"visible":false}],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
	});
});		
</script>
<?php
if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member")
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
				<?php echo __("Workout Log");?>
				<small><?php echo __("Assign Workout");?></small>
			  </h1>
			   <?php 
				if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member")
				{ ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymAssignWorkout","assignWorkout");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Assign Workout");?></a>
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
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member Goal");?></th>						
					<th><?php echo __("Action");?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($data as $row)
			{
				$confirmMsg = __("Are you sure,You want to delete this record?");
				echo "<tr>
					<td><img src='".$this->request->webroot ."webroot/upload/{$row['gym_member']['image']}' class='membership-img img-circle'></td>
					<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']} ({$row['gym_member']['member_id']})</td>
					<td>".$this->Gym->get_interest_by_id($row['gym_member']['intrested_area'])."</td>
					<td>
						<a href='".$this->request->base ."/GymAssignWorkout/viewWorkouts/{$row['user_id']}' class='btn btn-primary btn-flat' title='View'><i class='fa fa-eye'></i> ".__("View Workouts")."</a> 
						<a href='".$this->request->base ."/GymAssignWorkout/deleteWorkout/{$row['user_id']}' class='btn btn-danger btn-flat' title='Delete' onclick=\"return confirm('$confirmMsg');\"><i class='fa fa-trash'></i></a>
					</td>
				</tr>";
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member Goal");?></th>						
					<th><?php echo __("Action");?></th>
				</tr>
			</tfoot>
		</table>
		<br><br>
		</div>
	</div>
</section>