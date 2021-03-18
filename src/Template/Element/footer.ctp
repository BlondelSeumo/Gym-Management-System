<?php 
	$session = $this->request->session();
	$footer = $session->read("User.footer");
	$version = $session->read("User.version");
	$pull = ($session->read("User.is_rtl") == "1") ? "pull-left" : "pull-right"	;

 ?>
    <footer class="main-footer">
        <div class="<?php echo $pull;?> hidden-xs">
          <b><?php echo __("Version");?></b> <?php echo $version;?>
        </div>

	  <span><?php echo $footer;?></span>
      </footer>