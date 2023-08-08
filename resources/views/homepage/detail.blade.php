@extends('homepage.index')
@section('header')
    <title> {{ $products->name }}</title>
@endsection
@section('slide')

@endsection
@section('contents')
<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">{{ $products->name }}</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Detail</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
 <div id="content">
        <div class="container">
          <div class="row bar">
            <div class="col-md-9">
              <div id="productMain" class="row">
                <div class="col-sm-6">
                  <div data-slider-id="1" class="owl-carousel shop-detail-carousel">
                    <div> <img src=" {{ url($products->photo) }}" alt="" class="img-fluid"></div>
                   {{--  <div> <img src="img/detailbig2.jpg" alt="" class="img-fluid"></div>
                    <div> <img src="img/detailbig3.jpg" alt="" class="img-fluid"></div> --}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="box">
                    <form action="{{ url('cart') }}" method="POST">
                      {{ @csrf_field() }}
                    <p class="price" style="margin:0px 0px;">Rp. {{ number_format($harga, 0, ',', '.')}}</p>
                    <br>
                    <div class="row">
                        <div class="col-md-5 text-md-right">
                            <label class="col-form-label">Stock</label>
                        </div>
                        <div class="col-md-3">
                            <div class="sizes">
                            @if($products->stock <1 )
                            <label class="col-form-label">: Habis</label>
                            @else
                                <select onchange="hitungSubtotal(this.value)" name="qty" id="qty" class="form-control">
                                <?php
                                for($i=1;$i <= $products->stock ; $i++){
                                  echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                                  ?>
                                </select>
                                @endif
                        </div>
                    </div>
                        <br>
                        <br>
                      </div>
                      @if(Auth::user())
                      @if($products->stock > 0 )
                        <div class="row">
                            <div class="col-md-5 text-md-right">
                                <label class="col-form-label text-right">Lama Pinjam</label>
                            </div>
                            <div class="col-md-7">
                                <select onchange="getHarga(this.value)" id="lama_pinjam_id" name="lama_pinjam" class="form-control" required>
                                    @foreach ($lama_pinjam as $row)
                                    <option value="{{$row->id}}">{{$row->lama_pinjam}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col text-center">
                                <label class="col-form-label text-secondary font-weight-bold">SUBTOTAL</label>
                                <p id="subtotal" class="price" style="margin:0px 0px;">Rp. {{ number_format($harga, 0, ',', '.')}}</p>
                            </div>
                        </div>
                        @endif
                    <input type="hidden" id="harga" name="harga" value="{{$harga}}">
                    <input type="hidden" id="subtotal2" name="subtotal2" value="{{$harga}}">
                      <input type="hidden" id="product_id" name="id" value="<?php echo $products->id;?>">
                      <br><br>
                      <p class="text-center">
                          @if($products->stock < 1)

                          @else
                            <button type="submit" class="btn btn-template-outlined"><i class="fa fa-shopping-cart"></i> Add to cart</button>
                          @endif
                        @else
                        <br>
                        <p class="text-center">Login dahulu untuk melakukan transaksi</p>
                        @endif
                      </p>
                    </form>
                  </div>
                  <div data-slider-id="1" class="owl-thumbs">
                    <button class="owl-thumb-item"><img src="img/detailsquare.jpg" alt="" class="img-fluid"></button>
                    <button class="owl-thumb-item"><img src="img/detailsquare2.jpg" alt="" class="img-fluid"></button>
                    <button class="owl-thumb-item"><img src="img/detailsquare3.jpg" alt="" class="img-fluid"></button>
                  </div>
                </div>
              </div>
              <div id="details" class="box mb-4 mt-4">
                 <h4>Penjual</h4>
                {{ $products->user->name }}
                <br><br>
                <h4>Weight</h4>
                {{ $products->weight }} KG
                <br><br>
                <h4>Product details</h4>
                {!! $products->description !!}
              </div>
            <div class="row"> 
          
             
              </div>
           </div> 
            <div class="col-md-3">
              <!-- MENUS AND FILTERS-->
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Categories</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm category-menu">
                      @foreach($category as $cat)
                        <li class="nav-item"><a href="{{ url('/category/'.$cat->slug) }}" class="nav-link active d-flex align-items-center justify-content-between"><span> {{ $cat->name }}  </span><span class="badge badge-light">{{ count($cat->children)}}</span></a>
                          <ul class="nav nav-pills flex-column">
                               @foreach($cat ->children as $sub)
                                <li class="nav-item"><a href="{{ url('/category/'.$sub->slug) }}" class="nav-link"> {{ $sub->name }}</a></li>
                                @endforeach

                          </ul>
                        </li>
                       @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

@endsection

@section('footer')
<script>
    function getHarga(id) {
        var product_id = $("#product_id").val();
        var qty = $("#qty").val();
        $.getJSON('/harga/'+product_id+'/'+id, null,
            function(data){
                $("#harga").val(data.harga);
                $("#subtotal2").val(data.harga);
                $("#subtotal").text(convertToRupiah(data.harga));
                hitungSubtotal(qty);
        });
    }
    function convertToRupiah(angka)
    {
        var rupiah = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
        return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
    }
    function hitungSubtotal(qty){
        var harga = $("#harga").val();
        var subtotal = qty * harga;
        $("#subtotal2").val(subtotal);
        $("#subtotal").text(convertToRupiah(subtotal));
    }
</script>
@endsection

@show