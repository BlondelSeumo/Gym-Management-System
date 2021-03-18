<div class="container-gym">
  <div class="info">
    <h1><?php echo $this->Gym->getSettings("name");?></h1>
  </div>
</div>
<?php 

$logo =$this->Gym->getSettings("gym_logo");
$logo = (!empty($logo)) ? $this->request->base ."/webroot/upload/" . $logo : $this->request->base ."/webroot/img/Thumbnail-img2.png";

?>

<div class="form">
	<div class="logo"><img src="<?php echo $logo;?>"/></div>
		<div class="box-header">
			<section class="content-header">
			  <h3 id='form-head'>
				<?php echo __("Forgot Password");?>
			  </h3>			  
			</section>
		</div>
		<br>
		<form class="login-form" method="post" id="login-form"> 
			<input type="text" placeholder="<?php echo __("Enter Registered Email");?>" name="email" class="validate[required,custom[email]] " id="unm"/>
			<button id="btn_login"><?php echo __("Submit");?></button>
            <p class="message"><a href="<?php echo $this->request->base;?>/users/login/"><?php echo __("Login");?></a></p>

		</form>
</div>
<script>

 $(document).load(function(){
	/* $("div.message").hide(); */
});
$('.message a').click(function(){
	/* $('form').animate({height: "toggle", opacity: "toggle"}, "slow"); */
});
$("div.message").click(function(){
	/* $(this).slideUp("slow"); */
	$(this).hide();
});
 
</script> 

