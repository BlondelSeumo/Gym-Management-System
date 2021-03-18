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
	                  {"bSortable": true},	                                            
	                  {"bSortable": false}],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}
	});
});		
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Notice List");?>
				<small><?php echo __("Notice");?></small>
			  </h1>
			  <?php
			if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member")
			{ ?>
			  <ol class="breadcrumb">				
				<a href="<?php echo $this->Gym->createurl("GymNotice","addNotice");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Notice");?></a>
			  </ol>
			<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<table class="mydataTable table table-striped" width="100%">
			<thead>
				<tr>
					<th><?php echo __("Notice Title");?></th>
					<th><?php echo __("Notice Comment");?></th>
					<th><?php echo __("Notice For");?></th>
					<th><?php echo __("Class");?></th>
					<th><?php echo __("Action");?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$confirmMsg = __("Are you sure you want to delete this product?");
			foreach($data as $row)
			{
				echo "<tr>";
				echo "<td>{$row['notice_title']}</td>
					  <td>{$row['comment']}</td>
					  <td>". ucwords(str_replace("_"," ",$row['notice_for']))."</td>
					  <td>".(($row['class_id']!=0)?$this->Gym->get_class_by_id($row['class_id']):'None')."</td>
					  <td>";
				if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member")
				{
					echo " <a href='".$this->request->base ."/GymNotice/editNotice/{$row['id']}' class='btn btn-flat btn-primary' title='".__('Edit')."'><i class='fa fa-edit'></i></a>
						<a href='{$this->request->base}/GymNotice/deleteNotice/{$row['id']}' class='btn btn-flat btn-danger' title='".__('Delete')."' onclick=\"return confirm('$confirmMsg')\"><i class='fa fa-trash'></i></a>";
				}
				echo  " <a href='javascript:void(0)' id='{$row['id']}' data-url='".$this->request->base ."/GymAjax/view_notice' class='view_notice btn btn-flat btn-info' title='".__('View')."' ><i class='fa fa-eye'></i></a>";    
				echo  "</td>";
				echo  "</tr>";
			}
			?>
			<tfoot>
				<tr>
					<th><?php echo __("Notice Title");?></th>
					<th><?php echo __("Notice Comment");?></th>
					<th><?php echo __("Notice For");?></th>
					<th><?php echo __("Class");?></th>
					<th><?php echo __("Action");?></th>
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
