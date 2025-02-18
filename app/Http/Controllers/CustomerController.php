<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
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

    public function importCustomer()
    {
        $response = Http::get("{$this->url}/wp-json/wc/v3/customers", [
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret,
        ]);

        $stores = $response->json();
        foreach ($stores as $store) {
            Store::updateOrCreate(
                ['id' => $store['id']],
                [   
                    'email' => $store['email'],
                    'name' => $store['first_name']. ' '.$store['last_name'],
                    'billing' => [
                        'first_name' => $store['billing']['first_name'],
                        'last_name' => $store['billing']['last_name'],
                        'company' => $store['billing']['company'],
                        'address_1' => $store['billing']['address_1'],
                        'address_2' => $store['billing']['address_2'],
                        'city' => $store['billing']['city'],
                        'province' => $store['billing']['state'],
                        'cp' => $store['billing']['postcode'],
                        'country' => $store['billing']['country'],
                        'email' => $store['billing']['email'],
                        'phone' => $store['billing']['phone'],
                    ],
                ]
            );
        
        }

        return response()->json(['message' => 'Órdenes importadas correctamente']);
    }
}