@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@section('style')
    <link href="{{asset('inspinia/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('inspinia/css/plugins/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css')}}" rel="stylesheet">
    <style media="screen">
        .fileinput-preview.fileinput-exists.img-thumbnail img{
            max-width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="row wrapper white-bg page-heading">
        <div class="col-lg-10">
            <h2>Data Siswa</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home.index') }}">Beranda</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('student.index') }}">Data Siswa</a>
                </li>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Tambah Siswa</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Tambah Data Siswa</h5>
                    </div>
                    <div class="ibox-content">
                        <h2>
                            Data Siswa
                        </h2>
                        <p>
                            Data Siswa ini diambil dari daftar nama-nama siswa sekolah ini
                        </p>

                        <form id="form" action="{{route('student.store')}}" class="wizard-big" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>Informasi Dasar</h3>
                                    <div class="form-group">
                                        <label id="labelNisn" class="{{$errors->has('nisn')?"text-danger":""}}" for="nisn">NISN {{$errors->has('nisn')?"*":""}}</label>
                                        <input id="nisn" name="nisn" maxlength="15" value="{{old('nisn')}}" type="text" class="form-control {{$errors->has('nisn')?"border border-danger":""}}">
                                        <span id="noticeNisn"></span>
                                        @if ($errors->has('nisn'))
                                            <span class="text-danger">{{$errors->first('nisn')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label id="labelName" class="{{$errors->has('name')?"text-danger":""}}" for="name">Nama {{$errors->has('name')?"*":""}}</label>
                                        <input id="name" name="name" value="{{old('name')}}" type="text" class="form-control {{$errors->has('name')?"border border-danger":""}}">
                                        <span id="noticeName"></span>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{$errors->first('name')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label id="labelSYear" class="{{$errors->has('start_year')?"text-danger":""}}" for="start_year">Tahun Masuk {{$errors->has('start_year')?"*":""}}</label>
                                        <input id="start_year" maxlength="4" name="start_year" value="{{old('start_year')}}" type="text" class="form-control {{$errors->has('start_year')?"border border-danger":""}}">
                                        <span id="noticeSYear"></span>
                                        @if ($errors->has('start_year'))
                                            <span class="text-danger">{{$errors->first('start_year')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="{{$errors->has('classroom_id')?"text-danger":""}}" id="labelClassroom">Kelas {{$errors->has('classroom_id')?"*":""}}</label>
                                        <select class="form-control m-b" name="classroom_id" id="classroom_id">
                                            <option selected value="">-- Pilih Kelas --</option>
                                            @foreach ($classes as $class)
                                                <option value="{{$class->id}}">{{$class->name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="noticeClassroom"></span>
                                        @if ($errors->has('classroom_id'))
                                            <span class="text-danger">{{$errors->first('classroom_id')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label id="labelYear" class="{{$errors->has('school_year_id')?"text-danger":""}}">Tahun Ajaran {{$errors->has('school_year_id')?"*":""}}</label>
                                        <select id="year" class="form-control m-b" name="school_year_id">
                                            <option selected value="">-- Pilih Tahun Ajaran --</option>
                                            @foreach ($years as $year)
                                                <option value="{{$year->id}}">{{$year->start_year}}/{{$year->end_year}}</option>
                                            @endforeach
                                        </select>
                                        <span id="noticeSYear"></span>
                                        @if ($errors->has('school_year_id'))
                                            <span class="text-danger">{{$errors->first('school_year_id')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="{{$errors->has('gender')?"text-danger":""}}">Jenis Kelamin {{$errors->has('gender')?"*":""}}</label>
                                        <div class="i-checks col-sm-6">
                                            <label class="{{$errors->has('gender')?"text-danger":""}}">
                                                <input type="radio" value="L" name="gender">
                                                <i></i>
                                                Laki-Laki
                                            </label>
                                        </div>
                                        <div class="i-checks col-sm-6">
                                            <label class="{{$errors->has('gender')?"text-danger":""}}">
                                                <input type="radio" value="P" name="gender">
                                                <i></i>
                                                Perempuan
                                            </label>
                                        </div>
                                        @if ($errors->has('gender'))
                                            <span class="text-danger">{{$errors->first('gender')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h3>Set Up Akun</h3>
                                    <div class="form-group">
                                        <label class="{{$errors->has('email')?"text-danger":""}}" for="email" id="labelEmail">Email {{$errors->has('email')?"*":""}}</label>
                                        <input id="email" name="email" value="{{old('email')}}" type="text" class="form-control {{$errors->has('email')?"border border-danger":""}}">
                                        <span id="noticeEmail"></span>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{$errors->first('email')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="{{$errors->has('password')?"text-danger":""}}" for="password" id="labelPassword">Password {{$errors->has('password')?"*":""}}</label>
                                        <input id="password" type="password" class="form-control {{$errors->has('password')?"border border-danger":""}}">
                                        <i class="text-muted">Password minimal 8 karakter</i>
                                        <span id="noticePassword"></span>
                                        @if ($errors->has('password'))
                                            <br>
                                            <span class="text-danger">{{$errors->first('password')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="{{$errors->has('confirmation_password')?"text-danger":""}}" id="labelCPassword" for="confirmation_password">Konfirmasi Password {{$errors->has('confirmation_password')?"*":""}}</label>
                                        <input id="confirmation_password" name="confirmation_password" type="password" class="form-control {{$errors->has('confirmation_password')?"border border-danger":""}}">
                                        <i class="text-muted">Password minimal 8 karakter</i>
                                        <span id="noticeCPassword"></span>
                                        @if ($errors->has('confirmation_password'))
                                            <br>
                                            <span class="text-danger">{{$errors->first('confirmation_password')}}</span>
                                        @endif
                                    </div>
                                    <label for="">Avatar</label>

                                    <div class="custom-file">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                          <div class="fileinput-new img-thumbnail" style="height: 160px;">
                                            <img src="{{asset('img/150.png')}}">
                                          </div>
                                          <div class="fileinput-preview fileinput-exists img-thumbnail" style="max-width: 200px;"></div>
                                          <div>
                                            <span class="btn btn-outline-secondary btn-file">
                                                <span class="fileinput-new">Pilih Gambar</span>
                                                <span class="fileinput-exists">Ubah</span>
                                                <input type="file" name="avatar">
                                            </span>
                                            <a href="#" class="btn btn-outline-secondary fileinput-exists" data-dismiss="fileinput">Hapus</a>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-success mt-4 pull-right" id="tombol" disabled type="submit"><i class="fa fa-save"></i> Simpan</button>
                                    <a class="btn btn-default mt-4" href="{{route('student.index')}}"><i class="fa fa-arrow-left"></i> Kembali</a>
                                    <button class="btn btn-danger mt-4" type="reset"><i class="fa fa-trash"></i> Buang</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Jasny -->
    <script src="{{asset('inspinia/js/plugins/jasny/jasny-bootstrap.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('inspinia/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                radioClass: 'iradio_square-green',
            });
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
            $('#nisn').bind('keypress', function(e){
                var keyCode = e.which ? e.which : e.keyCode;
                if (!(keyCode >= 48 && keyCode <= 57)) {
                    return false;
                }else {
                    return true;
                }
            });
            $('#start_year').bind('keypress',function(e){
                var keyCode = e.which ? e.which : e.keyCode;
                if (!(keyCode >= 48 && keyCode <= 57)) {
                    return false;
                }else {
                    return true;
                }
            });
            $('#nisn').blur(function(){
                var nisn     = $('#nisn').val();
                if (nisn == "") {
                    $('#labelNisn').addClass('text-danger').text('NISN *');
                    $('#nisn').addClass('border border-danger');
                    $('#noticeNisn').addClass('text-danger').text('NISN tidak boleh kosong');
                    document.getElementById('tombol').disabled = true;
                }else {
                    $('#labelNisn').removeClass('text-danger').text('NISN');
                    $('#nisn').removeClass('border border-danger');
                    $('#noticeNisn').removeClass('text-danger').text('');
                    document.getElementById('tombol').disabled = false;
                }
            });
            $('#name').blur(function(){
                var name     = $('#name').val();
                if (name == "") {
                    $('#labelName').addClass('text-danger').text('Nama *');
                    $('#name').addClass('border border-danger');
                    $('#noticeName').addClass('text-danger').text('Nama tidak boleh kosong');
                    document.getElementById('tombol').disabled = true;
                }else if (name.length < 4 || name.length > 100) {
                    $('#labelName').addClass('text-danger').text('Nama *');
                    $('#name').addClass('border border-danger');
                    $('#noticeName').addClass('text-danger').text('Nama minimal 4 dan maksimal 100 karakter');
                    document.getElementById('tombol').disabled = true;
                }else {
                    $('#labelName').removeClass('text-danger').text('Nama');
                    $('#name').removeClass('border border-danger');
                    $('#noticeName').removeClass('text-danger').text('');
                    document.getElementById('tombol').disabled = false;
                }
            })
            $("#start_year").blur(function(){
                var start_year = $("#start_year").val();
                if (start_year == "") {
                    $("#labelSYear").addClass('text-danger').text('Tahun Masuk *');
                    $('#start_year').addClass('border border-danger');
                    $('#noticeSYear').addClass('text-danger').text('Tahun Masuk tidak boleh kosong');
                    document.getElementById('tombol').disabled = true;
                }else{
                    $("#labelSYear").removeClass('text-danger').text('Tahun Masuk');
                    $('#start_year').removeClass('border border-danger');
                    $('#noticeSYear').removeClass('text-danger').text('');
                    document.getElementById('tombol').disabled = false;
                }
            })
            $("#email").blur(function(){
              var email   = $("#email").val();
              if (email == "") {
                  var pesan   = "Email tidak boleh kosong";
                  $("#labelEmail").addClass('text-danger').text('Email');
                  $("#email").addClass('border border-danger');
                  $("#noticeEmail").addClass('text-danger').text(pesan);
                  document.getElementById("tombol").disabled = true;
              }
              else if (email.search('@')>=0) {
                var pesan2   = "Email Terverifikasi";
                $("#labelEmail").remmoveClass('text-danger').text('Email');
                $("#email").remmoveClass('border border-danger');
                $("#noticeEmail").text(pesan2);
                document.getElementById("tombol").disabled = true;
              }else {
                var pesan3   = "Email harus sesuai standar";
                $("#labelEmail").addClass('text-danger').text('Email *');
                $("#email").addClass('border border-danger');
                $("#noticeEmail").text(pesan3);
                document.getElementById("tombol").disabled = false;
              }
            })
            $("#password").blur(function(){
              var passNew   = $("#password").val();
              var noticeCPassword  = $("#confirmation_password").val();
              if (passNew == "" && noticeCPassword == "") {
                  $(".text-muted").remove();
                  $("#noticePassword").addClass('text-danger').text("Password tidak boleh kosong");
                  $("#labelPassword").addClass('text-danger').text('Password *');
                  $("#password").addClass('border border-danger');
                  document.getElementById("tombol").disabled = true;
              }else if (passNew.length < 8) {
                $(".text-muted").remove();
                $("#labelPassword").addClass('text-danger').text('Password *');
                $("#password").addClass('border border-danger');
                $("#noticePassword").addClass('text-danger').text("Password kurang 8 Karakter");
                document.getElementById("tombol").disabled = true;
              }else {
                $(".text-muted").remove();
                $("#labelPassword").removeClass('text-danger').text('Password');
                $("#password").removeClass('border border-danger');
                $("#noticePassword").removeClass('text-danger').text("");
                document.getElementById("tombol").disabled = false;
              }
            })
            $("#confirmation_password").blur(function(){
              var passNew     = $("#password").val();
              var noticeCPassword  = $("#confirmation_password").val();
              if (passNew == "" && noticeCPassword == "") {
                  $("#noticeCPassword").addClass('text-danger').text('Password tidak boleh kosong');
                  $("#labelCPassword").addClass('text-danger').text('Konfirmasi Password *');
                  $("#confirmation_password").addClass('border border-danger');
                  $(".text-muted").text("Password minimal 8 karakter");
                  document.getElementById("tombol").disabled = true;
              }else if (noticeCPassword !== passNew) {
                $(".text-muted").remove();
                $("#password").addClass('border border-danger');
                $("#labelPassword").addClass('text-danger').text('Password *');
                $("#labelCPassword").addClass('text-danger').text('Konfirmasi Password *');
                $("#confirmation_password").addClass('border border-danger');
                $("#noticeCPassword").addClass('text-danger').text("Password tidak sama");
                document.getElementById("tombol").disabled = true;
              }else if (noticeCPassword.length < 8) {
                $(".text-muted").remove();
                $("#labelCPassword").addClass('text-danger').text('Konfirmasi Password *');
                $("#confirmation_password").addClass('border border-danger');
                $("#noticeCPassword").addClass('text-danger').text("Lengkapi Password anda");
                document.getElementById("tombol").disabled = true;
              }else {
                $(".text-muted").remove();
                $("#labelCPassword").removeClass('text-danger').text('Konfirmasi Password');
                $("#confirmation_password").removeClass('border border-danger');
                $("#noticeCPassword").removeClass('text-danger').text("Password benar");
                document.getElementById("tombol").disabled = false;
              }
            })
            $("#classroom_id").blur(function(){
                var selek = $("#classroom_id option:selected").val();
                if (selek == "") {
                    $("#labelClassroom").addClass('text-danger').text('Kelas *');
                    $("#classroom_id").addClass('border border-danger');
                    document.getElementById("tombol").disabled = true;
                }else {
                    $("#labelClassroom").removeClass('text-danger').text('Kelas');
                    $("#classroom_id").removeClass('border border-danger');
                    document.getElementById("tombol").disabled = false;
                }
            })
            $("#year").blur(function(){
                var selek = $("#year option:selected").val();
                if (selek == "") {
                    $("#labelYear").addClass('text-danger').text('Tahun Ajaran *');
                    $("#year").addClass('border border-danger');
                    document.getElementById("tombol").disabled = true;
                }else {
                    $("#labelYear").removeClass('text-danger').text('Tahun Ajaran');
                    $("#year").removeClass('border border-danger');
                    document.getElementById("tombol").disabled = false;
                }
            })
       });
    </script>
@endsection
