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
		<!-- <form class="register-form">
			<div class="logging"><?php echo __("Logging you in"); ?>
				<i class="fa-li fa fa-spinner fa-spin"></i>
			</div>
		</form> -->
        <div class="box-header">
			<section class="content-header">
			  <h3 id='form-head'>
				<?php echo __("Reset Password");?>
			  </h3>			  
			</section>
		</div>
		<br>
		<form class="login-form" method="post" id="login-form"> 
			<input type="password" placeholder="<?php echo __("New Password");?>"  name="password" class="validate[required] password" id="password"/>
            <input type="password" placeholder="<?php echo __("Confirm Password");?>" name="confirmPassword" class="validate[required,equals[password]]" id="confirmPassword"/>
			<button id="btn_login"><?php echo __("Submit");?></button>
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

