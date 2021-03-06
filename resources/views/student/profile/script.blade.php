<script src="{{asset('bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script src="{{asset('stisla/assets/js/page/bootstrap-modal.js')}}"></script>
<!-- Jasny -->
<script src="{{asset('jasny/jasny-bootstrap.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            keepEmptyValues: true,
            language: 'id'
        });
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        $("#datepicker").bind("keypress", function(e){
            var keyCode = e.which ? e.which : e.keyCode;
            if (!(keyCode >= 45 && keyCode <= 57)) {
                return false;
            }else {
                return true;
            }
        })
        $("#nisn").bind("keypress", function(e){
            var keyCode = e.which ? e.which : e.keyCode;
            if (!(keyCode >= 48 && keyCode <= 57)) {
                return false;
            }else {
                return true;
            }
        })
        $("#nik").bind("keypress", function(e){
            var keyCode = e.which ? e.which : e.keyCode;
            if (!(keyCode >= 48 && keyCode <= 57)) {
                return false;
            }else {
                return true;
            }
        })
        $("#phone").bind("keypress", function(e){
            var keyCode = e.which ? e.which : e.keyCode;
            if (!(keyCode >= 48 && keyCode <= 57)) {
                return false;
            }else {
                return true;
            }
        })
        $("#start_year").bind("keypress", function(e){
            var keyCode = e.which ? e.which : e.keyCode;
            if (!(keyCode >= 48 && keyCode <= 57)) {
                return false;
            }else {
                return true;
            }
        })
        $("#email").blur(function(){
          var email   = $("#email").val();
          if (email.search('@')>=0) {
            var pesan2   = "Email Terverifikasi";
            $("#labelEmail").remmoveClass('text-danger').text('Email');
            $("#email").remmoveClass('border border-danger');
            $("#textEmail").text(pesan2);
          }else {
            var pesan3   = "Email harus sesuai standar";
            $("#labelEmail").addClass('text-danger').text('Email *');
            $("#email").addClass('border border-danger');
            $("#textEmail").text(pesan3);
          }
        })
        $("#password").blur(function(){
          var passNew   = $("#password").val();
          var konfirPass  = $("#confirmation_password").val();
          if (passNew == "" && konfirPass == "") {
              $(".text-muted").remove();
              $("#textPassword").removeClass('text-danger').text("").append("<i>Password minimal 8 karakter</i>");
              $("#labelPass").removeClass('text-danger').text('Password');
              $("#password").removeClass('border border-danger');
              document.getElementById("tombol").disabled = false;
          }else if (passNew.length < 8) {
            $(".text-muted").remove();
            $("#labelPass").addClass('text-danger').text('Password *');
            $("#password").addClass('border border-danger');
            $("#textPassword").addClass('text-danger').text("Masukkan minimal 8 Karakter");
            document.getElementById("tombol").disabled = true;
          }else {
            $(".text-muted").remove();
            $("#labelPass").removeClass('text-danger').text('Password');
            $("#password").removeClass('border border-danger');
            $("#textPassword").removeClass('text-danger').text("");
            document.getElementById("tombol").disabled = true;
          }
        })
        $("#confirmation_password").blur(function(){
          var passNew     = $("#password").val();
          var konfirPass  = $("#confirmation_password").val();
          if (passNew == "" && konfirPass == "") {
              $("#textCPassword").removeClass('text-danger').text("").append("<i>Password minimal 8 karakter</i>");
              $("#konfirPass").removeClass('text-danger').text('Konfirmasi Password');
              $("#confirmation_password").removeClass('border border-danger');
              $(".text-muted").text("Password minimal 8 karakter");
              document.getElementById("tombol").disabled = false;
          }else if (konfirPass !== passNew) {
            $(".text-muted").remove();
            $("#labelPass").addClass('text-danger').text('Password *');
            $("#password").addClass('border border-danger');
            $("#konfirPass").addClass('text-danger').text('Konfirmasi Password *');
            $("#confirmation_password").addClass('border border-danger');
            $("#textCPassword").addClass('text-danger').text("Password tidak sama");
            document.getElementById("tombol").disabled = true;
          }else if (konfirPass.length < 8) {
            $(".text-muted").remove();
            $("#konfirPass").addClass('text-danger').text('Konfirmasi Password *');
            $("#confirmation_password").addClass('border border-danger');
            $("#textCPassword").addClass('text-danger').text("Lengkapi Password anda");
            document.getElementById("tombol").disabled = true;
          }else {
            $(".text-muted").remove();
            $("#konfirPass").removeClass('text-danger').text('Konfirmasi Password');
            $("#confirmation_password").removeClass('border border-danger');
            $("#textCPassword").removeClass('text-danger').text("");
            document.getElementById("tombol").disabled = false;
          }
        })
        $("#name").blur(function(){
            var name = $("#name").val();
            if (name == "") {
                $("#labelName").addClass('text-danger').text('Nama *');
                $("#name").addClass('border border-danger');
                $("#noticeName").addClass('text-danger').text('Nama Wajib diisi');
                document.getElementById("tombol").disabled = true;
            }else if (name.length < 4 || name.length >= 100) {
                $("#labelName").addClass('text-danger').text('Nama *');
                $("#name").addClass('border border-danger');
                $("#noticeName").addClass('text-danger').text('Nama minimal 4 karakter dan maksimal 100 karakter');
                document.getElementById("tombol").disabled = true;
            }else {
                $("#labelName").removeClass('text-danger').text('Nama');
                $("#name").removeClass('border border-danger');
                $("#noticeName").removeClass('text-danger').text("");
                document.getElementById("tombol").disabled = false;
            }
        })
    })
</script>
