@extends('admin.layouts2.app')
@section('title', 'Daftar Pengajar')
@section('style')
    <link href="{{asset('qlab/plugins/tables/css/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="content-body">
        <div class="row page-titles mx-0">
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Beranda</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('teacher.index')}}">Daftar Pengajar</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Daftar Pengajar</h4>
                            <a href="{{route('teacher.create')}}" class="btn mb-4 btn-rounded btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</a>
                            <a href="#" class="btn mb-4 btn-rounded btn-success btn-sm text-white"><i class="fas fa-download"></i> Ekspor</a>
                            <a href="#" class="btn mb-4 btn-rounded btn-info btn-sm pull-right"><i class="fa fa-print"></i> Cetak</a>
                            @if (session('notif'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                                    {{session('notif')}}
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped table-hover dataTables-example">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Avatar</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Gender</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{asset('qlab/plugins/tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('qlab/plugins/tables/js/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('qlab/plugins/tables/js/datatable-init/datatable-basic.min.js')}}"></script>
<script type="text/javascript">
    var table;
    $(function() {
        table = $('.dataTables-example').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{$ajax}}',
            order: [[0,'desc']],
            columns: [
                { data: 'id', searchable: true, orderable: true},
                { data: 'avatar', searchable: false, orderable: false},
                { data: 'nip', searchable: true, orderable: true},
                { data: 'name', searchable: true, orderable: true},
                { data: 'gender', searchable: true, orderable: false},
                { data: 'action', searchable: false, orderable: false}
            ],
            columnDefs: [{
              "targets": 0,
              "searchable": false,
              "orderable": false,
              "data": null,
              "title": 'No',
              "render": function (data, type, full, meta) {
                  return meta.settings._iDisplayStart + meta.row + 1;
              }
            }],
        });
    });
</script>
@endsection
