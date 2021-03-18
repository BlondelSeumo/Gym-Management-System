<script>window.onload = function(){ window.print();};</script>

	<div class="modal-body">
		<div id="invoice_print" style="width: 90%;margin:0 auto;"> 
		<div class="modal-header">
			<h4 class="modal-title"><?php echo $sys_data["name"];?></h4>
			</div>
				<?php $sys_data["gym_logo"] = (!empty($sys_data["gym_logo"])) ? $this->request->base . "/webroot/upload/".  $sys_data["gym_logo"] : $this->request->base . "/webroot/img/Thumbnail-img.png"; ?>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td width="70%">
									<img style="max-height:80px;" src="<?php echo $sys_data["gym_logo"]; ?>">
								</td>
								<td align="right" width="24%">
									<h5><?php $issue_date='DD-MM-YYYY';
												if(!empty($income_data)){
													$issue_date=$income_data["invoice_date"];
													$payment_status=$income_data["payment_status"];}
												if(!empty($invoice_data)){
													$invoice_no = $invoice_no;
													echo __('Invoice No.')." : ".$invoice_no."<br>"; 
													$issue_date=$this->Gym->get_db_format(date($this->Gym->getSettings('date_format'),strtotime($invoice_data['created_date'])));
													$payment_status=$this->GYM->get_membership_paymentstatus($invoice_data['mp_id']);}
												if(!empty($expense_data)){
													$issue_date=$expense_data["invoice_date"];
													$payment_status=$expense_data["payment_status"];}
									echo __('Issue Date')." : ".$this->Gym->get_db_format(date($sys_data["date_format"]),strtotime($issue_date));
									?></h5>
									
									<h5><?php echo __('Status')." : ".$payment_status;?></h5>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td align="left">
									<h4><?php echo __('Payment To');?> </h4>
								</td>
								<td align="right">
									<h4><?php echo __('Bill To');?> </h4>
								</td>
							</tr>
							<tr>
								<td valign="top" align="left">
									<?php echo $sys_data["name"]."<br>"; 
									 echo $sys_data["address"].","; 
									 echo $sys_data["country"]."<br>"; 
									 echo $sys_data["office_number"]."<br>"; 
									?>
								</td>
								<td valign="top" align="right">
									<?php
									if(!empty($expense_data)){
									echo $party_name=$expense_data["supplier_name"]; 
									}
									else
									{
										if(!empty($income_data))
										{
											$member_id=$income_data["supplier_name"];
											 echo $income_data["gym_member"]["first_name"]." ".$income_data["gym_member"]["last_name"]."<br>"; 
											 echo $income_data["gym_member"]["address"].","; 
											 echo $income_data["gym_member"]["city"].","; 
											echo $income_data["gym_member"]["mobile"]."<br>"; 
										}
										if(!empty($invoice_data))
										{
											$member_id=$invoice_data["member_id"];
			
										
										echo $invoice_data["gym_member"]["first_name"]." ".$invoice_data["gym_member"]["last_name"]."<br>"; 
										 echo $invoice_data["gym_member"]["address"].",<br>"; 
										 echo $invoice_data["gym_member"]["city"].",<br>"; 
										echo $invoice_data["gym_member"]["mobile"]."<br>"; 
										}
									}
									?>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<h4><?php echo __('Invoice Entries');?></h4>
					<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
						<thead>
						<?php
						 if(!empty($invoice_data)){
							 ?>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center"> <?php echo __('Membership Type');?></th>
								<th class="text-center"><?php echo __('Membership Fee');?> </th>
								<th class="text-center"><?php echo __('Total');?></th>
							</tr>
						<?php
						 }
						 else{
							 ?>
							 <tr>
								
								<th class="text-center"> <?php echo __('Date');?></th>
								<th width="60%"><?php echo __('Entry');?> </th>
								<th><?php echo __('Price');?></th>
								<th class="text-center"> <?php echo __('Username');?> </th>
							</tr>
						<?php
						 }
						 ?>
						</thead>
						<tbody>
						<?php 
							$id=1;
							$total_amount=0;
						if(!empty($income_data) || !empty($expense_data)){
							if(!empty($income_data))
							{
								$entries = json_decode($income_data["entry"]);
								$i = 1 ;
								foreach($entries as $entry)
								{ ?>
									<tr>
									<!--<td><?php echo $i; ?></td>-->
									<td align="center"><?php echo $this->Gym->get_db_format(date($this->GYM->getSettings("date_format"),strtotime($income_data["invoice_date"])));?></td>
									<td align="center"><?php echo $entry->entry;?></td>
									<td align="center"><?php echo $this->Gym->get_currency_symbol();?> <?php echo $entry->amount;?></td>
									<td align="center"><?php echo $income_data["gym_member"]["first_name"] . " ". $income_data["gym_member"]["last_name"];?></td>
									</tr>
								<?php	$i++;
								}							
							
							}else if(!empty($expense_data)){
								$entries = json_decode($expense_data["entry"]);
								$i = 1 ;
								foreach($entries as $entry)
								{ ?>
									<tr>
									
									<td align="center"><?php echo $this->Gym->get_db_format(date($this->GYM->getSettings("date_format"),strtotime($expense_data["invoice_date"])));?></td>
									<td align="center"><?php echo $entry->entry;?></td>
									<td align="center"><?php echo $this->Gym->get_currency_symbol();?> <?php echo $entry->amount;?></td>
									<td align="center"><?php echo $expense_data["gym_member"]["first_name"] . " ". $expense_data["gym_member"]["last_name"];?></td>
									</tr>
								<?php $i++;
								} 
							}						
														
						}
						 if(!empty($invoice_data)){
							
							 ?>
							<tr>
								<td align="center"><?php echo $id;?></td>
								<td align="center"><?php echo $invoice_data["membership"]["membership_label"];?></td>
								<td align="center"><?php echo $this->GYM->get_currency_symbol();?> <?php echo $invoice_data["membership"]["signup_fee"]; ?> </td>
								<td align="center"><?php echo $this->GYM->get_currency_symbol();?> <?php echo $subtotal = intval($invoice_data["membership"]["membership_amount"]);?></td>
							</tr>
							<?php 
							}?>
						</tbody>
					</table>
					<table width="100%" border="0">
						<tbody>
							<?php if(!empty($invoice_data))
							{
								echo "<BR>";
								
								?>
							<tr>
								<td width="80%" align="right"><?php echo __('Subtotal :');?></td>
								<td align="right"><?php echo $this->Gym->get_currency_symbol();?> <?php echo $subtotal;?></td>
							</tr>
							<tr>
								<td width="80%" align="right"><?php echo __('Payment Made :');?></td>
								<td align="right"><?php echo $this->Gym->get_currency_symbol();?> <?php echo $invoice_data['paid_amount'];?></td>
							</tr>
							<tr>
								<td colspan="2">
									<hr style="margin:0px;">
								</td>
							</tr>
							<?php
							}
							if(!empty($invoice_data)){
								$grand_total=$subtotal - $invoice_data["paid_amount"];
							}
							else if(!empty($income_data)){
								$grand_total=$income_data["total_amount"];
							}
							else if(!empty($expense_data)){
								$grand_total=$expense_data["total_amount"];
							}
							?>								
							<tr>
								<td width="80%" align="right"><?php echo __('Grand Total :');?></td>
								<td align="right"><h4 style="padding-bottom:0px;margin-bottom:0px;"><?php echo $this->Gym->get_currency_symbol();?> <?php echo $grand_total; ?></h4></td>
							</tr>
						</tbody>
					</table>
					
					<?php if(!empty($history_data))
					{?>
					<hr>
					<h4><?php echo __('Payment History');?></h4>
					<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
					<thead>
							<tr>
								<th class="text-center"><?php echo __('Date');?></th>
								<th class="text-center"> <?php echo __('Amount');?></th>
								<th class="text-center"><?php echo __('Method');?> </th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach($history_data as  $retrive_date)
							{?>
								<tr>
								<td align="center"><?php echo $this->Gym->get_db_format(date($this->GYM->getSettings("date_format"),strtotime($retrive_date["paid_by_date"])));?></td>
								<td align="center"><?php echo $this->GYM->get_currency_symbol();?> <?php echo $retrive_date["amount"];?></td>
								<td align="center"><?php echo $retrive_date["payment_method"];?></td>
								</tr>
					  <?php }?>
						</tbody>
					</table>
					<?php }?>
				</div>
	</div>
	<?php die; ?>