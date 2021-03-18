<?php $session = $this->request->session()->read("User");?>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-calendar"></i>
				<?php echo __("Class Schedules");?>
				<small><?php echo __("Class Schedule");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("ClassSchedule","classList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Class List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<table class="table table-bordered table-hover">
				<?php
				$days = ["Sunday"=>"Sunday","Monday"=>"Monday","Tuesday"=>"Tuesday","Wednesday"=>"Wednesday","Thursday"=>"Thursday","Friday"=>"Friday","Saturday"=>"Saturday"];
				foreach($days as $day)
				{
					echo "<tr><th width='50' height='50'>". __($day) ."</th><td>";
					foreach($classes as $class)
					{
						$classname=$this->Gym->get_class_by_id($class['class_id']);

						$days = json_decode($class['days']);
						if(in_array($day,$days))
						{ 
					?>					
							<div class="btn-group m-b-sm">
								<?php if($classname!="Classdeleted")
								{ ?>
								<button class="btn btn-flat btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id="<?php echo $class['id'];?>"><?php echo $this->Gym->get_class_by_id($class['class_id']);?><span class="time"> <?php echo "(".$class['start_time']." - ".$class['end_time'].")";?> </span></span><span class="caret"></span></button>
								<?php } ?>
								<?php if($classname!="Classdeleted")
								{ ?>
								
									<?php if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" )
									{ 
									?>
										<ul role="menu" class="dropdown-menu">
											<li><a href="<?php echo "{$this->request->base}/ClassSchedule/editClass/{$class['class_id']}";?>"><?php echo __("Edit");?></a></li>
									<?php  } else{
									echo "<script>$('.caret').hide();</script>"; ?>
									
										</ul> <?php }?>
								
								<?php }else{ ?>
									<ul role="menu" class="dropdown-menu">
										<li><a href="#">This class is deleted</a></li>
									</ul>
								<?php } ?>
							</div>
				<?php	}
					}	
					echo "</td></tr>";
				}
				?>
			</table>
		</div>		
	</div>
</section>