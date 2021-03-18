<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<?= $this->Element('header') ?>
<?= $this->Html->meta('icon', 'webroot/favicon.icon'); ?>
<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
       <?= $this->Element('topbar') ?>	  
     <aside class="main-sidebar">
        <section class="sidebar">
		<?= ""/* $this->Element('sidebar')*/ ?>
		<?php 
		$session = $this->request->session()->read("User");
		$role_name = $session["role_name"];
		switch($role_name)
		{
			CASE "administrator":
				$menu_cell = $this->cell('GymRenderMenu::adminMenu');
			break;
			
			CASE "member":
				$menu_cell = $this->cell('GymRenderMenu::memberMenu');
			break;
			
			CASE "staff_member":
				$menu_cell = $this->cell('GymRenderMenu::staffMenu');
			break;
			
			CASE "accountant":
				$menu_cell = $this->cell('GymRenderMenu::accMenu');
			break;
		}	
		?>
		  
		<?= $menu_cell ?>
		
		</section>
	 </aside>	 
      <div class="content-wrapper" style="min-height: 1273px !important">	 
		<div class="body-overlay">
		  <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
		</div>
		 <script>
		   $(".body-overlay").css("display","block");
		   $("body").css("overflow-y","hidden");
		 </script>
			<?= $this->Flash->Render() ?>
            <?= $this->fetch('content') ?>	
			<div class="modal fade gym-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
			  <div class="modal-dialog modal-lg gym-modal">
				<div class="modal-content">			
				
				</div>
			  </div>
			</div>
      </div>		  
		 <?= $this->Element('footer') ?>

      <div class="control-sidebar-bg"></div>
    </div>
	<script>
		
	</script>
</body>
</html>