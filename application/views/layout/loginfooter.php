
<!-- jQuery 3 -->
<script src="<?php echo site_url($CUSTOME_PATH['assets_bower'] .'jquery/dist/jquery.min.js');?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo site_url($CUSTOME_PATH['assets_bower'] .'bootstrap/dist/js/bootstrap.min.js');?>"></script>
<!-- iCheck -->
<script src="<?php echo site_url($CUSTOME_PATH['assets_plugins'] .'iCheck/icheck.min.js');?>"></script>
<!-- jQuery 3 -->
<script src="<?php echo site_url($CUSTOME_PATH['assets_bower'] .'jquery/dist/jquery.validate.js');?>"></script>
<script src="<?php echo site_url($CUSTOME_PATH['javascripts'] .'login.js');?>"></script>

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
