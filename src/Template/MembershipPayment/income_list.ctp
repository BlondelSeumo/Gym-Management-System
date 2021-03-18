	<script type="text/javascript">
	 $( function() {
    $( document ).tooltip();
  } );
$(document).ready(function() {
	jQuery(".expense_form").validationEngine();
	jQuery('#payment_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
		});
} );
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
				<h1>
					<i class="fa fa-bars"></i>
					<?php echo __("Income List");?>
					<small><?php echo __("Income");?></small>
				</h1>
				<ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("MembershipPayment","addIncome");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Income");?></a>
				</ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		 <table id="payment_list" class="table table-striped" cellspacing="0" width="100%">
        	<thead>
            <tr>				
				<th><?php  echo __( 'Member Name', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Amount', 'gym_mgt' ) ;?></th>				
				<th><?php  echo __( 'Date', 'gym_mgt' ) ;?></th>				
				<th><?php  echo __( 'Action', 'gym_mgt' ) ;?></th>
            </tr>
			</thead>
			<tbody>
				<?php
					$confirmMsg = __("Are you sure,You want to delete this record?");

				if(!empty($data))
				{
					foreach($data as $row)
					{			
						echo "<tr>
								<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>
								<td>". $this->Gym->get_currency_symbol() ." {$row['total_amount']}</td>
								<td>".$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["invoice_date"])))."</td>
								<td>
								<a href='javascript:void(0)' class='btn btn-flat btn-info view_income_expense' data-url='".$this->request->base ."/GymAjax/viewIncomeExpense/{$row['id']}' type='income'><i class='fa fa-eye'></i></a>
								<a href='".$this->request->base ."/MembershipPayment/incomeEdit/{$row['id']}' class='btn btn-flat btn-primary' title='Edit'><i class='fa fa-edit'></i></a>
								<a href='".$this->request->base ."/MembershipPayment/deleteIncome/{$row['id']}' class='btn btn-flat btn-danger' onclick=\"return confirm('$confirmMsg')\"><i class='fa fa-trash'></i></a>
								</td>
						</tr>";
					}
				}
				?>
			</tbody>
			<tfoot>
            <tr>
				<th><?php  echo __( 'Member Name', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Amount', 'gym_mgt' ) ;?></th>				
				<th><?php  echo __( 'Date', 'gym_mgt' ) ;?></th>				
				<th><?php  echo __( 'Action', 'gym_mgt' ) ;?></th>
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