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
<head>
	<?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<?= $this->Html->meta(
    'favicon.ico',
    '/favicon.ico',
    ['type' => 'icon']
	);
	?>	
    <title><?= $this->fetch('title') ?></title>
     <?= $this->Html->css('jq-steps/normalize'); ?>	
	<?= $this->Html->css('jq-steps/main'); ?>	
	<?= $this->Html->css('jq-steps/jquery.steps'); ?>	
	<?= $this->Html->css('bootstrap.min'); ?>
	<?= $this->Html->css('jq-steps/custom_style'); ?>	
	<?= $this->Html->css('gym_install_cake.css'); ?>		
	<?= $this->Html->script('jq-steps/modernizr-2.6.2.min');?>
	<?= $this->Html->script('jq-steps/jquery-1.11.1.min'); ?>
	<?= $this->Html->script('jq-steps/jquery.cookie-1.3.1');?>
	<?= $this->Html->script('jq-steps/jquery.steps');?>	
	<?= $this->Html->script('jq-steps/jquery.validate.min');?>
	<?= $this->Html->script('bootstrap/js/bootstrap.min');?>
	<link rel="stylesheet prefetch" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900">
	<?php 
		echo $this->fetch('script');
	?>
	<style>
	
	</style>
</head>

<body class="hold-transition skin-green sidebar-mini">   
			
		<?= $this->Flash->Render() ?>
            <?= $this->fetch('content') ?>		      	  
	<!--	$this->Element('footer') -->
</body>
</html>