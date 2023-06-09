<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\DataTables\OrderDataTable;
use App\DataTables\UsersDataTable;

use Auth;
use DB;


class UserController extends Controller
{
    public function getSignup(){
        return view('user.signup');
    }

    public function postSignup(Request $request){
        $this->validate($request, [
            'email' => 'email|required|unique:users',
            'password' => 'required|min:4'
        ]);
        $user = new User([
         	'name' => $request->fname.' '.$request->lname,
            'email' => $request->email,
            'password' => bcrypt($request->input('password'))
        ]);
         $user->save();
         $customer = new Customer;
         $customer->user_id = $user->id;
         $customer->fname = $request->fname;
         $customer->lname = $request->lname;
         $customer->addressline = $request->addressline;
         $customer->phone = $request->phone;
         $customer->zipcode = $request->zipcode;
         $customer->save();
         Auth::login($user);
        //  return redirect()->route('user.profile');
        return redirect()->route('getItems');
    }

    public function getProfile(OrderDataTable $dataTable){
        // public function getProfile(){
        // $customer = Customer::where('user_id',Auth::id())->first();
        // $orders = DB::table('customer as c')->join('orderinfo as o','o.customer_id', '=', 'c.customer_id')
        //             ->join('orderline as ol','o.orderinfo_id', '=', 'ol.orderinfo_id')
        //             ->join('item as i','ol.item_id', '=', 'i.item_id')
        //             ->where('c.user_id', Auth::id())
        //             ->select('o.orderinfo_id', 'o.date_placed', DB::raw("SUM(ol.quantity * i.sell_price) as total"))
        //              ->groupBy('o.orderinfo_id', 'o.date_placed')->get();
                    // ->get()->toJson();
                    // foreach ($orders as $order) {
                    //     dump ($order->sell_price);
                    // }
        // return view('user.profile',compact('orders'));
        // dd($orders);
        return $dataTable->render('user.profile');
        
        // return view('user.profile');
    }

    public function getUsers(UsersDataTable $dataTable) {
        return $dataTable->render('admin.users');
    }
}
