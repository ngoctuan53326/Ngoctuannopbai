<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Bill;
use App\Models\Slide;
use App\Models\Typeproduct;
use App\Models\BillDetail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    //
    public function index(){
        $banner=Slide::all();
        $new_products = Product::where('promotion_price', '!=', 0)->get();
        $products=Product::all();
        $recommended_products = Product::inRandomOrder()->limit(4)->get();
        $typeproduct = Typeproduct::all();
        Session::put('typeproduct', $typeproduct);       
        return view('index',compact('new_products','products','banner','recommended_products'));   
    }
    public function show($id)
    {
        //
        $products = DB::table('products') ->where('id',$id)->get();
       //dd($products);
        
        return view('product',array('products' => $products)); 
    }
    public function addToCart(Request $request,$id){
        $product=Product::find($id);
        $oldCart=Session('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->add($product,$id);
        $request->session()->put('cart',$cart);
        return redirect()->back();
    }

    //thêm 1 sản phẩm có số lượng >1 có id cụ thể vào model cart rồi lưu dữ liệu của model cart vào 1 session có tên cart (session được truy cập bằng thực thể Request)
    public function addManyToCart(Request $request,$id){
        $product=Product::find($id);
        $oldCart=Session('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->addMany($product,$id,$request->qty);
        $request->session()->put('cart',$cart);
       
        return redirect()->back();
    }


    public function delCartItem($id){
        $oldCart=Session::has('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->removeItem($id);
        if(count($cart->items)>0){
            Session::put('cart',$cart);
        }else Session::forget('cart');
        return redirect()->back();
    }
    public function getCheckout(){
        return view('checkout');
    }
    public function postCheckout(Request $request){
    
        $cart=Session::get('cart');
        $customer=new Customer();
        $customer->name=$request->input('name');
        $customer->gender=$request->input('gender');
        $customer->email=$request->input('email');
        $customer->address=$request->input('address');
        $customer->phone_number=$request->input('phone_number');
        $customer->note=$request->input('note');
        $customer->trangthai = "Chưa liên hệ"; // Cung cấp giá trị cho trường 'trangthai
        $customer->save();

        $bill=new Bill();
        $bill->id_customer=$customer->id;
        $bill->date_order=date('Y-m-d');
        $bill->total=$cart->totalPrice;
        $bill->payment=$request->input('payment_method');
        $bill->note=$request->input('note');
        $bill->status="Đang chuẩn bị hàng";
        $bill->save();

        foreach($cart->items as $key=>$value)
        {
            $bill_detail=new BillDetail();
            $bill_detail->id_bill=$bill->id;
            $bill_detail->id_product=$key;
            $bill_detail->quantity=$value['qty'];
            $bill_detail->unit_price=$value['price']/$value['qty'];
            $bill_detail->save();
        }
        Session::forget('cart');
        return redirect()->back()->with('success','Đặt hàng thành công');

    }
    
    public function getSignin(){
       
        return view('signup');
    }

    public function postSignin(Request $req){
        $this->validate($req,
        ['email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:20',
            'fullname'=>'required',
            'repassword'=>'required|same:password'
        ],
        ['email.required'=>'Vui lòng nhập email',
        'email.email'=>'Không đúng định dạng email',
        'email.unique'=>'Email đã có người sử  dụng',
        'password.required'=>'Vui lòng nhập mật khẩu',
        'repassword.same'=>'Mật khẩu không giống nhau',
        'password.min'=>'Mật khẩu ít nhất 6 ký tự'
        ]);

        $user=new User();
        $user->full_name=$req->fullname;
        $user->email=$req->email;
        $user->password=Hash::make($req->password);
        $user->phone=$req->phone;
        $user->address=$req->address;
        $user->level=3;  //level=1: admin; level=2:kỹ thuật; level=3: khách hàng
        $user->love="";
        $user->save();
        return redirect()->back()->with('success','Tạo tài khoản thành công');
    }

    public function producttype(string $id){   
        $products=Product::where('id_type',$id)->get();   
        return view('product_type',compact('products'));
    }
    public function getLike($id)
    {
        $new_product = Product::find($id);
        $tb = "";
        if (Auth::check()) {
            $user = Auth::user();
            $love = $user->love;
            $listlove = explode(",", $love);
            if (!in_array($id, $listlove)) {
                // Nếu không tồn tại, thêm phần tử vào mảng
                $listlove[] = $id;
                $tb = "Đã thêm thành công";
                $love = implode(",", $listlove);
                $user->love = $love;
                $user->save();
            } else {
                $tb = "Sản phẩm này đã có trong danh sách yêu thích của bạn";
            }
        } else {
            $tb = "Bạn cần đăng nhập để sử dụng chức năng này";
        }
        return redirect()->route('trangchu.trangchu')->with('success', $tb);
    }
   
}
