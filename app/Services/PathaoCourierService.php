<?php
namespace App\Services;

use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Http;

class PathaoCourierService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $userName;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = get_setting('pathao_base_url');
        $this->clientId = get_setting('pathao_client_id');
        $this->clientSecret = get_setting('pathao_client_secret');
        $this->userName = get_setting('pathao_username');
        $this->password = get_setting('pathao_password');
        $this->token = $this->authenticate();
    }

    private function authenticate()
    {
        try {
           $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/aladdin/api/v1/issue-token", [
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret,
            "grant_type" => "password",
            "username" => $this->userName,
            "password" => $this->password,
        ]);
        return $response['access_token'];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error authenticating to Pathao: ' . $e->getMessage(),
            ];
        }
        
    }
    public function storeId (){
       return Http::withToken($this->token)
            ->get("{$this->baseUrl}/aladdin/api/v1/stores")
            ->json()['data'];
    }

    public function getCities()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/aladdin/api/v1/city-list")
            ->json()['data'];
    }

    public function getZones($city_id)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/aladdin/api/v1/cities/{$city_id}/zone-list")
            ->json()['data'];
    }

    public function getAreas($zone_id)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/aladdin/api/v1/areas?zone_id={$zone_id}")
            ->json()['data'];
    }
    public function sendOrder($order)
    {
       try{
         $shipping = json_decode($order->shipping_address, true);
        $name = $shipping['name'];
        $email = $shipping['email'];
        $state = State::where('name', $shipping['state'])->first();
        $city = City::where('name', $shipping['city'])->first();
        $address = $shipping['address'];
        $phone = $shipping['phone'];

        //dd($order->getTotalProductWeight());
        $orderData = [
            "store_id" => get_setting('pathao_store_id'),
            "merchant_order_id" => $order['code'] ?? null,
            "recipient_name" => $name,
            "recipient_phone" => $phone,
            "recipient_address" => $address,
            "recipient_city" =>  (int)$state->id,
            "recipient_zone" => (int)$city->id,
            "delivery_type" => 48,// 48 for Normal Delivery, 12 for On Demand Delivery
            "item_type" => 2, // 	1 for Document, 2 for Parcel
            "special_instruction" => $order['additional_info'] ?? null,
            "item_quantity" => count($order->orderDetails),
            "item_weight" => $order->getTotalProductWeight(),
            "amount_to_collect" => $order['grand_total'],
        ];
       $response = Http::withToken($this->token)
            ->post("{$this->baseUrl}/aladdin/api/v1/orders", $orderData);
        
            return $response->json();
       }
         catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error sending order to Pathao: ' . $e->getMessage(),
            ];
        }
    }
}
