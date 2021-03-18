<!DOCTYPE html>
<html>
 <?= $this->Element('header') ?>
  <body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
		 <?= $this->Element('topbar') ?>	
     
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
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
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
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
              
      </div><!-- /.content-wrapper -->
	  <?= $this->Element('footer') ?>
	  
	  
      <div class="control-sidebar-bg"></div>

     
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.4 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
  </body>
</html>
