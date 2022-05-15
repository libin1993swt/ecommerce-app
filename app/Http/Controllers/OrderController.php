<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('customer')->get();
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('order.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:255',
            'phone' => 'required|digits:10',
            'product.*' => 'required|numeric',
            'quantity.*' => 'required|numeric|max:10',
        ]);

        $customerId = $this->saveCustomer($request);
        $orderId = $this->saveOrder($request,$customerId);
        $this->saveOrderDetails($request,$orderId);
        return redirect('/orders')->with('completed', 'Order has been saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Save customer details
     */
    public function saveCustomer($request)
    {
        $customer = new Customer();
        $customer->name = $request->customer_name;
        $customer->phone = $request->phone;
        $customer->save();
        return $customer->id;
    }

    /**
     * Save order
     */
    public function saveOrder($request,$customerId)
    {
        $order = new Order();
        $order->customer_id = $customerId;
        $order->date = date('Y-m-d h:i:s');
        $order->save();
        return $order->id;
    }

    /**
     * Save order details
     */
    public function saveOrderDetails($request,$orderId) 
    {
        $quantities = $request->quantity;
        $productsId = $request->product_id;
        foreach($quantities as $key => $quantity) {
            $orderDetails = new OrderDetail();
            $orderDetails->order_id = $orderId;
            $orderDetails->product_id = $productsId[$key];
            $orderDetails->quantity = $quantity;
            $orderDetails->save();
        }
        
    }

    /**
     * Generate invoice for order.
     */
    public function generateInvoice($id)
    {
        $order = Order::findOrFail($id);
        $orderDetails = OrderDetail::with('product')->where('order_id',$id)->get();

        $details = DB::table('orders')
        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        ->join('products', 'products.id', '=', 'order_details.product_id')
        ->select('orders.*', 'order_details.quantity', 'products.name', 'products.price',DB::raw('(products.price*order_details.quantity) AS net_total'))
        ->where('orders.id',$id)
        ->get();
        $netTotal = $details->sum('net_total');

        return view('order.invoice', compact('order','orderDetails','netTotal'));
    }
}
