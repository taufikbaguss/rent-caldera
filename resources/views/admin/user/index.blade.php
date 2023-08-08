@extends('admin.layout.master')
	@section('header')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('static/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <style type="text/css">
    .table_list {
      list-style: none;
      padding: 4px;
      margin-left: -30px;
    }
  </style>
  <title>User - Kaldera Rent</title>
	@endsection
	@section('body')
  <div class="row">
    <div class="col-md-12">
       <div class="box">
    <div class="box-header">
      <h3 class="box-title">User</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th width="30px">No</th>
          <th>Photo</th>
          <th>Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Address</th>
          <th>Gender</th>
          <th>Status</th>
          <th>Role</th>
          <th>Deposit</th>
          <th width="100">Menu</th>
        </tr>
        </thead>
        <tbody>
        @php
          $no = 1;
        @endphp
        @foreach($user as $users)
        <tr>
          <td>{{$no++ }}</td>
          <td><img src="{{ url($users->photo) }}" width="50px"></td>
          <td>{{ $users->name }}</td>
          <td>{{ $users->username }}</td>
          <td>{{ $users->email }}</td>
           <td>{{ $users->address }}</td>
          <td> {{ $users->gender }}</td>
          <td>
            @if($users->status == 0)
             <a href="{{ url('admin/user/status/'.$users->id) }}" class="text-danger"> Non Aktif </a>
            @else
             <a href="{{ url('admin/user/status/'.$users->id) }}" class="text-primary">  Aktif </a>
            @endif
          </td>
          <td>{{ $users->role }}</td>
          <td>{{ number_format($users->deposit, 0, ",", ".") }}</td>
          <td>
            <a href="{{ url('admin/user/edit/'.$users->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <a href="{{ url('admin/user/delete/'.$users->id) }}" class="btn btn-sm btn-danger">Delete</a>
          </td>
        </tr>

        @endforeach
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    </div>
    </div>
  </div>

          <!-- /.box -->
	@endsection
	@section('footer')
		  <!-- DataTables -->
  <script src="{{ asset('static/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('static/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
  <script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
	@endsection
@show