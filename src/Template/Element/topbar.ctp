<?php $session = $this->request->session(); 

$header_color = $this->Gym->getHeaderColor();
$sidemenu_color = $this->Gym->getSidemenuColor();

?>
<style type="text/css">
  
</style>
<header class="main-header">        
        <a href="#" class="logo">
	
		   <?php  $left_header = $session->read("User.left_header");?>
		   <span class="logo-lg"><?php echo $left_header;?></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
		  <span class="top-title">	
			 <?php $logo = $session->read("User.logo"); ?>
			<span style="height: 100%;max-width: 80px;display: inline-block;">
			<?php echo $this->Html->image($logo,["style"=>"max-height: 100%;max-width: 100%;"]);?>
			</span>
			<span class="title_gym">
			 &nbsp;&nbsp;&nbsp;				
			 <?php echo $session->read("User.name");?>	
           </span>			 
		  </span>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<?php 
					$user_img = $session->read("User.profile_img");
					echo $this->Html->image("../webroot/upload/{$user_img}",array("class"=>"user-image","alt"=>"User Image")); ?>                
                  <span class="hidden-xs"><?php echo $session->read("User.display_name");?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header">
				  <?php
				  echo $this->Html->image("../webroot/upload/{$user_img}",array("class"=>"img-circle","alt"=>"User Image")); ?>
                    <p>
                      <?php echo $session->read("User.display_name") . "-" . ucwords(str_replace("_"," ",$session->read("User.role_name")));?>
                      <small><?php echo __("Member since"); ?> <?php echo $this->Gym->get_db_format(date("F 'y",strtotime($session->read("User.join_date"))));?></small>
                    </p>
                  </li>
				  <li class="user-footer callout callout-info lead">	
				  <?php if($session->read("User.role_name") == "administrator")
				  {?>
					  <div class="pull-left">                     
					  <a href="<?php echo $this->request->base; ?>/AdminProfile/editProfile/<?php echo $session->read("User.id");?>" class="btn btn-default btn-flat"><?php echo __("Profile"); ?></a>
					</div> 
				 <?php  
				  }
				  else{ ?>		 
                    <div class="pull-left">                     
					  <a href="<?php echo $this->request->base; ?>/GymProfile/viewProfile" class="btn btn-default btn-flat"><?php echo __("Profile"); ?></a>
					</div>
				  <?php } ?>	
                    <div class="pull-right">
                      <a href="<?php echo $this->request->base;?>/Users/logout" class="btn btn-default btn-flat"><?php echo __("Sign out"); ?></a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>

<script>
  $(document).ready(function(){

    /* header color */
    var header = '<?php echo $header_color; ?>';
    var rgbacol = 'rgba('+parseInt(header.slice(-6,-4),16)+ ',' +parseInt(header.slice(-4,-2),16)+ ',' +parseInt(header.slice(-2),16)+',0.35)';
    $('.skin-green .main-header .navbar').css('background-color',header);
    $('.skin-green .main-header .logo').css('background-color',header);
    $('.skin-green .main-header li.user-header').css('background-color',header);
    $('.callout.callout-info').css('background-color',header+" !important");
    $('.callout.callout-info').css('border-color',header);
    $('.box').css('border-top', '3px solid'+ header);
    $('.skin-green .main-header .navbar .sidebar-toggle').hover(function(){
      $(this).css('background-color',header);
    });
    $('.skin-green .sidebar-menu>li.active>a').css('border-left-color',header);
    $('.skin-green .sidebar-menu>li.active>a').css('background',rgbacol);
    $('.panel-heading').css('background-color',header);
    $('.btn-success').css('background',header);
    $('.btn-success').css('border-color',header);
    
    /* sidemenu color */
    var sidemenu = '<?php echo $sidemenu_color; ?>';
    $('.skin-green .main-sidebar').css('background-color',sidemenu);
    //$('body').css('color',sidemenu);
    $('.skin-green .wrapper').css('background-color',sidemenu);


  })
</script>