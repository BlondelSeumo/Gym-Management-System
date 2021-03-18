<?php $session = $this->request->session()->read("User");?>
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
	                  {"bSortable": true,"sWidth":"1"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true},
	                  {"bSortable": false}],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
		});
} );
var box_height = $(".box").height();
var box_height = box_height + 200 ;
$(".content").css("height",box_height+"px");
	
</script>
<section class="content ">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
				<h1>
					<i class="fa fa-bars"></i>
					<?php echo __("Payment");?>
					<small><?php echo __("Membership Payment");?></small>
				</h1>
				 <?php
				if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member")
				{ ?>
				<ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("MembershipPayment","generatePaymentInvoice");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Generate Payment Invoice");?></a>
				</ol>
			<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
		 <table id="payment_list" class="table table-striped" cellspacing="0" width="100%">
        	<thead>
            <tr>
				<th><?php  echo __( 'Title', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Member Name', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Paid Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Due Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Membership Start Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Membership End Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Payment Status', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Action', 'gym_mgt' ) ;?></th>
            </tr>
			</thead>
			<tbody>
				<?php
					$confirmMsg = __("Are you sure,You want to delete this record?");
					if(!empty($data)) {
						foreach($data as $row) {
							$due = ($row['membership_amount']- $row['paid_amount']);
							echo "<tr>
									<td>{$row['membership']['membership_label']}</td>
									<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>
									<td>".$this->Gym->get_currency_symbol()." {$row['membership_amount']}</td>
									<td>".$this->Gym->get_currency_symbol()." {$row['paid_amount']}</td>
									<td>".$this->Gym->get_currency_symbol()." {$due}</td>
									<td>".$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["start_date"])))."</td>
									<td>".$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["end_date"])))."</td>
									<td><span class='bg-primary pay_status'>". __($this->Gym->get_membership_paymentstatus($row['mp_id']))."<span></td>
									"; 
									if($due == 0) {
										echo "
										<td>
										<a href='javascript:void(0)' class='btn1 btn btn-flat btn-default amt_pay' disabled data-url='".$this->request->base ."/GymAjax/gymPay/{$row['mp_id']}/{$due}'>".__('Pay')."</a>
										<a href='javascript:void(0)' class='btn1 btn btn-flat btn-info view_invoice' data-url='".$this->request->base ."/GymAjax/viewInvoice/{$row['mp_id']}'><i class='fa fa-eye'></i></a>";
										if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member") {
											echo " <a href='".$this->request->base ."/MembershipPayment/MembershipEdit/{$row['mp_id']}' class='btn1 btn btn-flat btn-primary' title='Edit'><i class='fa fa-edit'></i></a>
											<a href='".$this->request->base ."/MembershipPayment/deletePayment/{$row['mp_id']}' class='btn1 btn btn-flat btn-danger' onclick=\"return confirm('$confirmMsg')\"><i class='fa fa-trash'></i></a>";
										}
										echo "</td>
										</tr>";
									}else {
										echo "
										<td>
										<a href='javascript:void(0)' class='btn1 btn btn-flat btn-default amt_pay' data-url='".$this->request->base ."/GymAjax/gymPay/{$row['mp_id']}/{$due}'>".__('Pay')."</a>
										<a href='javascript:void(0)' class='btn1 btn btn-flat btn-info view_invoice' data-url='".$this->request->base ."/GymAjax/viewInvoice/{$row['mp_id']}'><i class='fa fa-eye'></i></a>";
										if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member") {
											echo " <a href='".$this->request->base ."/MembershipPayment/MembershipEdit/{$row['mp_id']}' class='btn1 btn btn-flat btn-primary' title='Edit'><i class='fa fa-edit'></i></a>
											<a href='".$this->request->base ."/MembershipPayment/deletePayment/{$row['mp_id']}' class='btn1 btn btn-flat btn-danger' onclick=\"return confirm('$confirmMsg')\"><i class='fa fa-trash'></i></a>";
										}
										echo "</td>
									</tr>";
									}
									
						}
					}
				?>
			</tbody>
			<tfoot>
            <tr>
				<th><?php  echo __( 'Title', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Member Name', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Paid Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Due Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Membership Start Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Membership End Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Payment Status', 'gym_mgt' ) ;?></th>
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