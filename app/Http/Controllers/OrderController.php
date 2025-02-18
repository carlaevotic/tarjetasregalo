<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    protected $url;
    protected $consumerKey;
    protected $consumerSecret;

    public function __construct()
    {
        $this->url = config('app.woocommerce.url');
        $this->consumerKey = config('app.woocommerce.consumer_key');
        $this->consumerSecret = config('app.woocommerce.consumer_secret');
    }

    public function importOrders()
    {
        $response = Http::get("{$this->url}/wp-json/wc/v3/orders", [
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret,
        ]);

        $orders = $response->json();
        foreach ($orders as $order) {
            $orderModel = Order::updateOrCreate(
                ['id' => $order['id']],
                [   
                    // 'store_id' =>$order['customer_id'],
                    'store_id' => 2,  // PASARLE LOS CLIENTES TBD !!!!
                    'name' => $order['billing']['first_name'] . ' ' . $order['billing']['last_name'],
                    // 'company' => $order['billing']['company'],
                    'amount_order' => $order['total'],
                    'status' => $order['status'],
                    'created_at' => $order['date_created'],
                ]
            );
        foreach($order['line_items'] as $item){
            OrderLine::create([
                'order_id' =>  $orderModel->id,
                // 'product_id' => $item['product_id'],
                'concept' => $item['name'],
                'qty' => $item['quantity'],
                'unit_price' => $item['price'], 
                'total' => $item['total'],
            ]);
        }
        }

        return response()->json(['message' => 'Órdenes importadas correctamente']);
    }
}
