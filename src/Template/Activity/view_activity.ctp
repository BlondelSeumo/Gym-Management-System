<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo $activity_title;?>
				<small>&nbsp;<?php echo __("Activity");?></small>
			  </h1>
			  
			</section>
		</div>
		<hr>
		<div class="box-body">
            <div style="padding-bottom:56.25%; position:relative; display:block; width: 100%">
            <?php 
            
            if($video != NULL){
                $video = json_decode($video);
            foreach($video as $data){ ?>
                <?php echo $data; ?>
               
           <?php }
            }
            else{
                echo "<h4>". __('No Any Video') ."</h4>";
            }
            ?>
            </div>
            
		</div>
	</div>
</section>