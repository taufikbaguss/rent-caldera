@extends('homepage.index')
@section('header')
    <title>Kaldera Rent - Menyewakan Peralatan Camping</title>

@endsection
@section('slide')

@endsection
@section('contents')
<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">My Orders</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">My Orders</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row bar mb-0">
            <div id="customer-orders" class="col-md-9">
              <p class="text-muted lead">Apabila ada pertanyaan silahkan hubungi adamin.</p>
              <a href="{{ url('addproduct') }}" class="btn btn-sm btn-primary">Add Product</a><br><br>
              <div class="box mt-0 mb-lg-0">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th width="30px">No</th>
                        <th>Photo</th>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>User</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php
                    $no = 1;
                    @endphp
                    @foreach($product as $item)
                    <tr>
                        <td>{{$no ++ }}</td>
                        <td><img src="{{ url($item->photo) }}" width="50px"></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->stock }}</td>
                        <td>{{$item->user->name }}</td>
                        <td>
                            <a href="{{ url('editproduct/'.$item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <a href="{{ url('deleteproduct/'.$item->id) }}" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-3 mt-4 mt-md-0">
              <!-- CUSTOMER MENU -->
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Customer section</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
                    <li class="nav-item"><a href="{{ url('cart/myorder') }}" class="nav-link "><i class="fa fa-list"></i> My orders</a></li>
                    <li class="nav-item"><a href="{{ url('myproduct') }}" class="nav-link active"><i class="fa fa-heart"></i> My Product</a></li>
                    <li class="nav-item"><a href="{{ url('myprofil') }}" class="nav-link"><i class="fa fa-user"></i> My account</a></li>
                    <li class="nav-item"><a href="/logout" class="nav-link"><i class="fa fa-sign-out"></i> Logout</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('footer')

@endsection
@show