<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    // =============================================
    // DASHBOARD ADMIN
    // =============================================
    public function dashboard()
    {
        $totalOrders   = Pesanan::count();
        $totalRevenue  = Pesanan::where('status', 'completed')->sum('total_keseluruhan');
        $totalMenus    = Menu::count();
        $todayOrders   = Pesanan::whereDate('created_at', today())->count();
        $todayRevenue  = Pesanan::whereDate('created_at', today())
                            ->where('status', 'completed')
                            ->sum('total_keseluruhan');

        return view('pos.admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalMenus', 'todayOrders', 'todayRevenue'
        ));
    }

    // =============================================
    // CRUD MENU
    // =============================================
    public function menuIndex()
    {
        $menus = Menu::with('kategori')->latest()->paginate(15);
        return view('pos.admin.menu.index', compact('menus'));
    }

    public function menuCreate()
    {
        $categories = Kategori::all();
        return view('pos.admin.menu.create', compact('categories'));
    }

    public function menuStore(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'id_kategori' => 'required|exists:kategori,id',
            'gambar'       => 'nullable|url',
            'tersedia'=> 'boolean',
        ]);

        $validated['tersedia'] = $request->boolean('tersedia', true);
        $validated['slug'] = Str::slug($validated['nama']) . '-' . rand(1000, 9999);

        Menu::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu "' . $validated['nama'] . '" berhasil ditambahkan!');
    }

    public function menuEdit(Menu $menu)
    {
        $categories = Kategori::all();
        return view('pos.admin.menu.edit', compact('menu', 'categories'));
    }

    public function menuUpdate(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'id_kategori' => 'required|exists:kategori,id',
            'gambar'       => 'nullable|url',
            'tersedia'=> 'boolean',
        ]);

        $validated['tersedia'] = $request->boolean('tersedia', true);
        if ($menu->nama !== $validated['nama']) {
            $validated['slug'] = Str::slug($validated['nama']) . '-' . rand(1000, 9999);
        }

        $menu->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu "' . $menu->nama . '" berhasil diperbarui!');
    }

    public function menuDestroy(Menu $menu)
    {
        $name = $menu->nama;
        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu "' . $name . '" berhasil dihapus.');
    }

    
    public function categoryIndex()
    {
        $categories = Kategori::withCount('menu')->get();
        return view('pos.admin.category.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100|unique:kategori,nama']);
        Kategori::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama)
        ]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function categoryUpdate(Request $request, Kategori $category)
    {
        $request->validate(['nama' => 'required|string|max:100|unique:kategori,nama,' . $category->id]);
        $category->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama)
        ]);
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function categoryDestroy(Kategori $category)
    {
        if ($category->menu()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori masih memiliki menu! Hapus atau pindahkan menu terlebih dahulu.');
        }
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

   
    public function kasirIndex()
    {
        $users = \App\Models\User::where('role', 'kasir')->latest()->get();
        return view('pos.admin.kasir.index', compact('users'));
    }

    public function kasirStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        \App\Models\User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role'      => 'kasir',
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun Kasir berhasil ditambahkan!');
    }

    public function kasirUpdate(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Akun Kasir berhasil diperbarui!');
    }

    public function kasirDestroy(\App\Models\User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'Akun Kasir berhasil dihapus.');
    }


    public function report(Request $request)
    {
        $period = $request->get('period', 'today');
        $startDate = match($period) {
            'week'  => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year'  => Carbon::now()->startOfYear(),
            default => Carbon::today(),
        };
        $endDate = Carbon::now();

        // Total ringkasan
        $summary = Pesanan::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('COUNT(*) as total_orders, SUM(total_keseluruhan) as total_revenue, SUM(pajak) as total_tax, SUM(total_harga) as total_subtotal')
            ->first();

        // Produk terlaris
        $topProducts = DetailPesanan::join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
            ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id')
            ->where('pesanan.status', 'completed')
            ->whereBetween('pesanan.created_at', [$startDate, $endDate])
            ->selectRaw('menu.nama, menu.gambar, SUM(detail_pesanan.jumlah) as total_qty, SUM(detail_pesanan.subtotal) as total_revenue')
            ->groupBy('menu.id', 'menu.nama', 'menu.gambar')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Metode pembayaran terpopuler
        $paymentMethods = Pesanan::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('metode_pembayaran, COUNT(*) as count, SUM(total_keseluruhan) as total')
            ->groupBy('metode_pembayaran')
            ->orderByDesc('count')
            ->get();

        // Grafik harian (7 hari terakhir untuk tampil chart sederhana)
        $dailySales = Pesanan::where('status', 'completed')
            ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_keseluruhan) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Pesanan terbaru
        $recentOrders = Pesanan::with(['detail.menu', 'kasir'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        return view('pos.admin.report', compact(
            'summary', 'topProducts', 'paymentMethods', 'dailySales', 'recentOrders', 'period', 'startDate', 'endDate'
        ));
    }
}
