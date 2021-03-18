<?php $session = $this->request->session(); ?>
  <br>

  <ul class="sidebar-menu">
	<li class= "treeview <?php echo ($this->request->controller == "Dashboard") ? "active" : "";?>">
	  <a href="<?php echo $this->Gym->createurl("Dashboard","index");?>">
		<i class="icone"><img src="<?php echo $this->request->base ."/webroot/img/icon/dashboard.png";?>"></i>
		<span>&nbsp;<?php echo __('Dashboard');?></span>
	  </a>             
	</li>
	<?php
	$img_path = $this->request->base ."/webroot/img/icon/";	
	foreach($menus as $menu)
	{
		if($menu["controller"] == "MembershipPayment")
		{ ?>
			<li class= "treeview <?php echo ($this->request->here == $menu["page_link"]) ? "active" : "";?>">
				<a href="<?php echo $menu["page_link"]; ?>">
					<i class="icone"><img src="<?php echo $img_path.$menu["menu_icon"];?>"></i>
					<span>&nbsp;<?php echo __($menu["menu_title"]);?></span>
				</a>             
			</li>
		<?php	
		}
		else
		{ ?>
			<li class= "treeview <?php echo ($this->request->controller == $menu["controller"]) ? "active" : "";?>">
				<a href="<?php echo $menu["page_link"]; ?>">
					<i class="icone"><img src="<?php echo $img_path.$menu["menu_icon"];?>"></i>
					<span>&nbsp;<?php echo __($menu["menu_title"]);?></span>
				</a>             
			</li>
		<?php } ?>
<?php } ?>	
  </ul>

