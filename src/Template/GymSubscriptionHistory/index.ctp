<?php $session = $this->request->session()->read("User");?>
<script>
$( function() {
    $( document ).tooltip();
  } );
  </script>
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
				<i class="fa fa-history"></i>
				<?php echo __("Subscription History");?>
				<small><?php echo __("Subscription History");?></small>
			  </h1>			 
			</section>
		</div>
		<hr>
		<div class="box-body">
			<table class="mydataTable table table-striped" width="100%">
			<thead>
				<tr>
					<th><?php echo __("Member Name"); ?></th>
					<th><?php echo __("Membership Title");?></th>
					<th><?php echo __("Amount");?></th>
					<th><?php echo __("Due Amount");?></th>
					<th><?php echo __("Membership Start Date");?></th>
					<th><?php echo __("Membership End Date");?></th>
					<th><?php echo __("Payment Status");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!empty($data))
				{
					foreach($data as $row)
					{
						echo "<tr>
							<td>".$this->Gym->get_member_name($row['member_id'])."</td>
							<td>{$row["Membership"]["membership_label"]}</td>
							<td>{$row["membership_amount"]}</td>
							<td>". ($row["membership_amount"] - $row["paid_amount"]) ."</td>
							<td>".$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["start_date"])))."</td>
							<td>".$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["end_date"])))."</td>
							<td><span class='bg-primary pay_status'>".$this->Gym->get_membership_paymentstatus($row["mp_id"])."</span></td>
						</tr>";
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Member Name"); ?></th>
					<th><?php echo __("Membership Title");?></th>
					<th><?php echo __("Amount");?></th>
					<th><?php echo __("Due Amount");?></th>
					<th><?php echo __("Membership Start Date");?></th>
					<th><?php echo __("Membership End Date");?></th>
					<th><?php echo __("Payment Status");?></th>
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
