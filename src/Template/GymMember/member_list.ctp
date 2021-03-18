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
if($session["role_name"] == "administrator" || $session["role_name"] == "member" || $session["role_name"] == "staff_member")
{ ?>
<script>
$(document).ready(function(){
	var table = $(".mydataTable").DataTable();
	
});
</script>
<?php } 

if($session["role_name"] == "administrator")
{?>
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
				<?php echo __("Members List");?>
				<small><?php echo __("Member");?></small>
			  </h1>
			   <?php
			   
				if($session["role_name"] == "administrator" || $session['role_name'] == "staff_member")
				{ ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymMember","addMember");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Member");?></a>
			  </ol>
			   <?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<?php if($session["role_name"] == "administrator"){?>
			<div class="col-md-12">
				<div class="col-md-10 col-sm-12">
					<form action="<?php echo $this->request->base.'/GymMember/import'; ?>" method="post"  enctype="multipart/form-data">
					<div class="col-md-2 col-sm-2 im_ex">
						
						<select class="form-control" name="import_export" id="import_export">
							<option value="export"><?php echo __('Export'); ?></option>
							<option value="import"><?php echo __('Import'); ?></option>
						</select>
						
					</div>
					<div class="col-md-6"  id="user">
						<input type="file" name="import" class="form-control" >
					</div>
					<div class="col-md-2">
					<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading..."><?php echo __('GO'); ?></button>
					</div>
					</form>
				</div>
				<div class="col-md-2 col-sm-2">
				<!-- <a href="<?php echo $this->request->base.'/GymMember/export'; ?>" class='btn btn-flat btn-info export'><?php echo __('GO');?></a> -->
				<a href="<?php echo $this->request->base.'/GymMember/export'; ?>" class='btn btn-flat btn-info export'><?php echo __('Sample Download');?></a>
				</div>
				
			</div>
		<?php } ?>
		<br>
		<table class="mydataTable table table-striped" width="100%">
			<thead>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member ID");?></th>					
					<th><?php echo __("Joining Date");?></th>					
					<th><?php echo __("Expire Date");?></th>					
					<th><?php echo __("Member Type");?></th>					
					<th><?php echo __("Membership Status");?></th>					
					<th><?php echo __("Action");?></th>
					<?php if($session['role_name'] == "administrator") {?>
					<th><?php echo __("Status");?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach($data as $row) {
					$membershipType = __($row['member_type']);
					$membershipStatus = __($row['membership_status']);
					echo "<tr>
					<td><img src='{$this->request->base}/webroot/upload/{$row['image']}' class='membership-img img-circle'></td>
					<td>{$row['first_name']} {$row['last_name']}</td>
					<td><p style='display:none'>{$row['id']}</p>{$row['member_id']}</td>
					<td>".(($row['membership_valid_from'] != '')?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row['membership_valid_from']))):'Null')."</td>
					<td>".(($row['membership_valid_to']!= '')?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row['membership_valid_to']))):'Null')."</td>
					<td>{$membershipType}</td>
					<td>{$membershipStatus}</td>
					<td>
					
						<a href='{$this->request->base}/GymMember/viewMember/{$row['id']}' title='View' class='btn btn-flat btn-info'><i class='fa fa-eye'></i></a>";
					if($session["role_name"] == "administrator" )
					{	
						$confirmMsg = __("Are you sure,You want to delete this record?");
					echo " <a href='{$this->request->base}/GymMember/editMember/{$row['id']}' title='Edit' class='btn btn-flat btn-primary'><i class='fa fa-edit'></i></a>
						<a href='{$this->request->base}/GymMember/deleteMember/{$row['id']}' title='Delete' class='btn btn-flat btn-danger' onClick=\"return confirm('$confirmMsg');\"><i class='fa fa-trash-o'></i></a>";
					}
					echo " <a href='{$this->request->base}/GymMember/viewAttendance/{$row['id']}' title='Attendance' class='btn btn-flat btn-default  member_attendance'><i class='fa fa-eye'></i>".__('Attendance')."</a>";
					
					echo "</td>";
					if($session['role_name'] == "administrator") {
					echo "<td>";
						if($row["activated"] == 0) {
							echo "<a class='btn btn-success btn-flat' onclick=\"return confirm('Are you sure,you want to activate this account?');\" href='".$this->request->base ."/GymMember/activateMember/{$row['id']}'>".__('Activate')."</a>";
						}elseif($row["membership_valid_to"] < date("Y-m-d")) {
							echo "<a class='btn btn-danger btn-flat' href='#'>".__('Deactivate')."</a>";
						}else {
							echo "<span class='btn btn-flat btn-default'>".__('Activated')."</span>";
						}
					echo "</td>";
					}
					echo "</tr>";
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member ID");?></th>					
					<th><?php echo __("Joining Date");?></th>					
					<th><?php echo __("Expire Date");?></th>					
					<th><?php echo __("Member Type");?></th>					
					<th><?php echo __("Membership Status");?></th>					
					<th><?php echo __("Action");?></th>
					<?php if($session['role_name'] == "administrator"){ ?>
					<th><?php echo __("Status");?></th>
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
<script>
$(document).ready(function(){
	$('#user').hide();
	$('#import_export').on('change',function(){
		
		var data = $('#import_export').val();
		
		if(data == 'export') {
			$('.import').hide();
			$('.export').show();
			$('#user').hide();
		}
		if(data == 'import') {
			$('.export').hide();
			$('.import').show();
			$('#user').show();
		}
	})
})
</script>
