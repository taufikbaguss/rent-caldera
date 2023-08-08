<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Product;
use Auth;
use App\Category;
use App\User;
use App\Transaction;
use Alert;
use App\Lama_pinjam;

class CartController extends Controller
{
    protected $category;
    public function __construct()
    {
        $this->category = Category::where('parent_id', null)->get();
    }
    public function index(Request $req)
    {
        // Cart::destroy();
        $product = Product::find($req->id);
        $lama_pinjam = Lama_pinjam::find($req->lama_pinjam);
        Cart::add(['id' => $product->id, 'name' => $product->name, 'qty' => $req->qty, 'price' => $req->harga, 'options' => ['lama_pinjam_id' => $req->lama_pinjam, 'lama_pinjam' => $lama_pinjam->lama_pinjam, 'deposit' => $product->deposit_harga]]);
        return redirect('keranjang');
    }
    public function keranjang()
    {
        $category = $this->category;
        return view('homepage.keranjang', compact('category'));
    }
    public function update(Request $req)
    {
        Cart::update($req->rowid, $req->qty);
        $category = $this->category;
        return redirect('keranjang');
    }
    public function delete($rowid)
    {
        Cart::remove($rowid);
        $category = $this->category;
        return redirect('keranjang');
    }
    public function formulir()
    {
        $category = $this->category;
        return view('homepage.formulir', compact('category'));
    }
    public function transaction(Request $req)
    {
        $file = $req->file('file');
        $filename = $file->getClientOriginalName();
        $req->file('file')->move('static/dist/img/', $filename);
        foreach (Cart::content() as $row) {
            $product = Product::find($row->id);

            $city = json_decode(City(), true);
            $weight = $product->weight * $row->qty;
            $product->stock = $product->stock - $row->qty;
            $product->save();
            $lama_pinjam_id = $row->options->lama_pinjam_id;
            foreach ($city['rajaongkir']['results'] as $key) {
                if ($product->user->address == $key['city_name']) {
                    $cost = Cost($key['city_id'], $req->city, $weight, $req->eks);
                    $data = json_decode($cost, true);
                    // Cart::update($row->rowId, ['options' => [
                    //     'code'  => $data['rajaongkir']['results'][0]['code'],
                    //     'name'  => $data['rajaongkir']['results'][0]['name'],
                    //     'value' => $data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'],
                    //     'etd'   => $data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['etd']
                    // ]]);
                    $eks = [
                        'code' => $data['rajaongkir']['results'][0]['code'],
                        'name' => $data['rajaongkir']['results'][0]['name'],
                        'value' => $data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'],
                        'etd' => $data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['etd']
                    ];
                    // $item = Cart::get($row->rowId);
                    // Cart::remove($item->rowId);
                }
            }
            $subtotal_deposit = ($row->price + $row->options->deposit) * $row->qty;
            $transaction = new Transaction;
            $transaction->code = date('ymdhi') . Auth::user()->id;
            $transaction->user_id = Auth::user()->id;
            $transaction->qty = $row->qty;
            $transaction->harga = $row->price;
            $transaction->deposit = $row->options->deposit;
            $transaction->subtotal = $subtotal_deposit;
            // $transaction->subtotal = $row->subtotal;
            $transaction->lama_pinjam_id = $lama_pinjam_id;
            $transaction->name = $req->name;
            $transaction->file = 'static/dist/img/'.$filename;
            $transaction->address = $req->city;
            $transaction->portal_code = $req->portal_code;
            $transaction->ekspedisi = $eks;
            $transaction->product_id = $product->id;
            $transaction->save();
            // if (Cart::count() == 0) {
            //         return redirect('cart/myorder');
            // }
        }
        Cart::destroy();
        return redirect('cart/myorder');
    }

    public function myorder()
    {
        $category = $this->category;
        $transaction = Transaction::groupBy('code')->orderBy('id', 'DESC')->where('user_id', Auth::user()->id)->get();
        return view('homepage.myorder', compact('category', 'transaction'));
    }

    public function detail($code)
    {
        $transaction = Transaction::groupBy('code')->orderBy('id', 'DESC')->where('code', $code)->first();
        $transactiondetail = Transaction::orderBy('id', 'DESC')->where('code', $code)->get();
        $category = $this->category;
        return view('homepage.detailtransaksi', compact('category', 'transaction', 'transactiondetail'));
    }
    public function product()
    {
        $category = $this->category;
        $product = Product::where('user_id', Auth::user()->id)->get();
        return view('homepage.myproduct', compact('product', 'category'));
    }
    public function addproduct()
    {
        $category = $this->category;
        // $mycategorys = Category::where('parent_id',null)->get();
        return view('homepage.addproduct', compact('category'));
    }
    public function saveproduct(Request $request)
    {

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $request->file('file')->move('static/dist/img/', $filename);
        $product = new Product;
        $product->slug = $request->slug;
        $product->photo = 'static/dist/img/' . $filename;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->weight = $request->weight;
        $product->user_id = Auth::user()->id;
        $product->save();
        Alert::success('', 'Product Berhasil di Tambahkan');
        return redirect('myproduct');
    }
    public function editproduct($id)
    {
        $category = $this->category;
        $product = Product::find($id);
        // $categorys = Category::where('parent_id',null)->get();
        return view('homepage.editproduct', compact('product', 'category'));
    }
    public function updateproduct(Request $request)
    {
        $id = $request->id;
        if ($file = $request->file('file')) {
            $filename = $file->getClientOriginalName();
            $request->file('file')->move('static/dist/img/', $filename);
            $img = 'static/dist/img/' . $filename;
        } else {
            $img = $request->tmp_image;
        }
        $product = Product::find($id);
        $product->slug = $request->slug;
        $product->photo = $img;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->user_id = Auth::user()->id;
        $product->weight = $request->weight;
        $product->save();
        Alert::success('', 'Product Berhasil di Update');
        return redirect('myproduct');
    }
    public function deleteproduct($id)
    {
        $product = Product::find($id);
        $product->delete();
        Alert::success('', 'Product Berhasil di delete');
        return redirect('myproduct');
    }
}
