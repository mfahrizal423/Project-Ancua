<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    public function catalog(Request $request)
    {
        $categories = \App\Models\Kategori::all();
        $query = \App\Models\Menu::query();

        if ($request->has('category')) {
            $query->where('id_kategori', $request->category);
        }

        $menus = $query->get();

        return view('pos.catalog', compact('categories', 'menus'));
    }

    public function detail($id = null)
    {
        if (!$id) {
            return redirect()->route('home');
        }
        
        $menu = \App\Models\Menu::findOrFail($id);
        
        return view('pos.detail', compact('menu'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['jumlah'];
        }
        
        $tax = $subtotal * 0.10;
        $grandTotal = $subtotal + $tax;

        return view('pos.cart', compact('cart', 'subtotal', 'tax', 'grandTotal'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id_menu' => 'required|exists:menu,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $menu = \App\Models\Menu::findOrFail($request->id_menu);
        
        $cart = session()->get('cart', []);
        
        // Use a unique key based on item and customizations
        $sugar = $request->tingkat_gula ?? 'Normal';
        $ice = $request->tingkat_es ?? 'Normal';
        
        $cartKey = $menu->id . '-' . \Illuminate\Support\Str::slug($sugar) . '-' . \Illuminate\Support\Str::slug($ice);

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['jumlah'] += $request->jumlah;
        } else {
            $cart[$cartKey] = [
                'id_menu' => $menu->id,
                'nama' => $menu->nama,
                'gambar' => $menu->gambar,
                'nama_kategori' => $menu->kategori->nama ?? 'Menu',
                'harga' => $menu->harga,
                'jumlah' => $request->jumlah,
                'tingkat_gula' => $sugar,
                'tingkat_es' => $ice
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function updateCart(Request $request)
    {
        if($request->cart_key && $request->jumlah){
            $cart = session()->get('cart');
            $cart[$request->cart_key]['jumlah'] = $request->jumlah;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function removeFromCart(Request $request)
    {
        if($request->cart_key) {
            $cart = session()->get('cart');
            if(isset($cart[$request->cart_key])) {
                unset($cart[$request->cart_key]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Item removed');
        }
        return redirect()->back();
    }

    public function payment()
    {
        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty!');
        }

        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['jumlah'];
        }
        
        $tax = $subtotal * 0.10;
        $grandTotal = $subtotal + $tax;
        
        $totalItems = array_sum(array_column($cart, 'jumlah'));

        return view('pos.payment', compact('cart', 'subtotal', 'tax', 'grandTotal', 'totalItems'));
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty!');
        }

        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['jumlah'];
        }
        
        $tax = $subtotal * 0.10;
        $grandTotal = $subtotal + $tax;

        $order = \Illuminate\Support\Facades\DB::transaction(function () use ($cart, $subtotal, $tax, $grandTotal, $request) {
            // Create Order
            $order = \App\Models\Pesanan::create([
                'id_kasir' => auth()->id(),
                'nomor_pesanan' => 'ORD-' . strtoupper(uniqid()),
                'nama_pelanggan' => $request->nama_pelanggan,
                'total_harga' => $subtotal,
                'pajak' => $tax,
                'total_keseluruhan' => $grandTotal,
                'status' => 'unpaid', // Diubah menjadi unpaid sampai dibayar
                'metode_pembayaran' => 'duitku'
            ]);

            // Create Order Items
            foreach($cart as $item) {
                \App\Models\DetailPesanan::create([
                    'id_pesanan' => $order->id,
                    'id_menu' => $item['id_menu'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['jumlah'],
                    'tingkat_gula' => $item['tingkat_gula'],
                    'tingkat_es' => $item['tingkat_es']
                ]);
            }
            
            return $order;
        });

        // DUITKU INTEGRATION
        $merchantCode = env('DUITKU_MERCHANT_CODE');
        $apiKey = env('DUITKU_API_KEY');
        $duitkuEnv = env('DUITKU_ENV', 'sandbox');
        
        $paymentAmount = (int) $grandTotal;
        $paymentMethod = 'VA'; // VA = Virtual Account (semua bank, user pilih sendiri)
        $merchantOrderId = $order->nomor_pesanan; 
        $productDetails = 'Pesanan ' . $order->nomor_pesanan;
        $email = auth()->user()->email ?? 'customer@example.com';
        $phoneNumber = '08123456789'; 
        $customerVaName = $request->nama_pelanggan ?? auth()->user()->name ?? 'Customer';
        $callbackUrl = url('/api/duitku/callback');
        $returnUrl = url('/payment/return/' . $order->id);
        
        // Signature menggunakan HMAC SHA256 sesuai dokumentasi resmi Duitku
        $stringToSign = $merchantCode . $merchantOrderId . $paymentAmount;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);

        $params = array(
            'merchantCode' => $merchantCode,
            'paymentAmount' => $paymentAmount,
            'paymentMethod' => $paymentMethod,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => '',
            'merchantUserInfo' => '',
            'customerVaName' => $customerVaName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'itemDetails' => array(
                array(
                    'name' => $productDetails,
                    'price' => $paymentAmount,
                    'quantity' => 1
                )
            ),
            'customerDetail' => array(
                'firstName' => $customerVaName,
                'lastName' => '',
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'billingAddress' => array(
                    'firstName' => $customerVaName,
                    'lastName' => '',
                    'address' => 'Indonesia',
                    'city' => 'Jakarta',
                    'postalCode' => '10000',
                    'phone' => $phoneNumber,
                    'countryCode' => 'ID'
                ),
                'shippingAddress' => array(
                    'firstName' => $customerVaName,
                    'lastName' => '',
                    'address' => 'Indonesia',
                    'city' => 'Jakarta',
                    'postalCode' => '10000',
                    'phone' => $phoneNumber,
                    'countryCode' => 'ID'
                )
            ),
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'signature' => $signature,
            'expiryPeriod' => 60
        );
        
        $params_string = json_encode($params);
        
        // URL resmi sesuai dokumentasi Duitku
        $url = $duitkuEnv === 'sandbox' 
            ? 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry' 
            : 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry';
            
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($params_string)
        ])->post($url, $params);
        
        $result = $response->json();
        
        if ($response->successful() && isset($result['statusCode']) && $result['statusCode'] == '00') {
            $order->update([
                'payment_url' => $result['paymentUrl'],
                'payment_reference' => $result['reference']
            ]);
            
            session()->forget('cart');
            return redirect($result['paymentUrl']);
        }

        // If error, delete order to allow retry
        $order->delete();
        $errorMsg = $result['Message'] ?? $result['statusMessage'] ?? $result['message'] ?? 'Unknown error';
        return redirect()->route('pos.cart')->with('error', 'Gagal memproses pembayaran Duitku: ' . $errorMsg);
    }
    
    public function success($orderId)
    {
        $order = \App\Models\Pesanan::with('detail.menu')->findOrFail($orderId);
        return view('pos.success', compact('order'));
    }
    
    public function orderStatus($orderId)
    {
        $order = \App\Models\Pesanan::with('detail.menu')->findOrFail($orderId);
        return view('pos.order-status', compact('order'));
    }
    
    public function receipt($orderId)
    {
        $order = \App\Models\Pesanan::with('detail.menu')->findOrFail($orderId);
        return view('pos.receipt', compact('order'));
    }
    
    public function notifications()
    {
        // Get recent orders for notifications
        $orders = \App\Models\Pesanan::where('id_kasir', auth()->id())
            ->latest()
            ->take(10)
            ->get();
        return view('pos.notifications', compact('orders'));
    }
    
    public function orders()
    {
        $orders = \App\Models\Pesanan::with('detail.menu')
            ->where('id_kasir', auth()->id())
            ->latest()
            ->paginate(10);
        return view('pos.orders', compact('orders'));
    }
    
    /**
     * API endpoint untuk polling status order dari JS pelanggan
     */
    public function checkOrderStatus(\App\Models\Pesanan $order)
    {
        // Hanya pemilik order yang bisa cek
        if ($order->id_kasir !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return response()->json([
            'id'           => $order->id,
            'order_number' => $order->nomor_pesanan,
            'status'       => $order->status,
            'updated_at'   => $order->updated_at->toISOString(),
        ]);
    }
    
    /**
     * Admin: update status pesanan (misal: pending → preparing → ready → completed)
     */
    public function updateOrderStatus(Request $request, \App\Models\Pesanan $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,completed,cancelled'
        ]);
        
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);
        
        return response()->json([
            'success'    => true,
            'message'    => 'Status updated to ' . $request->status,
            'order'      => [
                'id'           => $order->id,
                'order_number' => $order->nomor_pesanan,
                'status'       => $order->status,
            ]
        ]);
    }
    
    /**
     * Halaman admin: daftar semua pesanan (untuk barista/kasir)
     */
    public function adminOrders()
    {
        $orders = \App\Models\Pesanan::with(['detail.menu', 'kasir'])
            ->latest()
            ->paginate(20);
        return view('pos.admin.orders', compact('orders'));
    }
}
