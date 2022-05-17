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
        foreach($orders as $key => $order) {
            $order->net_total = $this->invoiceNetTotal($order->id); 
        }
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
            'quantity.*' => 'required|numeric|max:10|gt:0',
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
        $order = Order::with('customer','order_details')->findOrFail($id);
        $products = Product::orderBy('name')->get();
        return view('order.create', compact('products','order'));
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
        $request->validate([
            'customer_name' => 'required|max:255',
            'phone' => 'required|digits:10',
            'product.*' => 'required|numeric',
            'quantity.*' => 'required|numeric|max:10|gt:0',
        ]);
        
        if($request->has('customer_id') && $request->customer_id != null) {
            $customerId = $this->saveCustomer($request,$request->customer_id);
        }

        return redirect('/orders')->with('completed', 'Order has been deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect('/orders')->with('completed', 'Order has been deleted');
    }

    /**
     * Save customer details
     */
    public function saveCustomer($request,$customerId = null)
    {
        if($customerId != null) {
            $customer = Customer::findOrFail($customerId);
        } else {
            $customer = new Customer();
        }
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

        $netTotal = $this->invoiceNetTotal($id);

        return view('order.invoice', compact('order','orderDetails','netTotal'));
    }

    /**
     * Get invoice net total
     */
    public function invoiceNetTotal($id) {
        $details = DB::table('orders')
        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        ->join('products', 'products.id', '=', 'order_details.product_id')
        ->select('orders.*', 'order_details.quantity', 'products.name', 'products.price',DB::raw('(products.price*order_details.quantity) AS net_total'))
        ->where('orders.id',$id)
        ->get();
        $netTotal = $details->sum('net_total');
        return $netTotal;
    }
}
