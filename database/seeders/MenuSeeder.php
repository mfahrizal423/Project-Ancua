<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'SIGNATURE',
            'ESPRESSO',
            'MILK BASED',
            'PASTRY'
        ];

        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat] = Kategori::create([
                'nama' => $cat,
                'slug' => Str::slug($cat)
            ]);
        }

        $menus = [
            [
                'id_kategori' => $catMap['SIGNATURE']->id,
                'nama' => 'KOPI SUSU GULA AREN',
                'slug' => Str::slug('KOPI SUSU GULA AREN'),
                'deskripsi' => 'Signature espresso mixed with fresh milk and authentic palm sugar. A perfect balance of creamy and sweet.',
                'harga' => 22000,
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCgGNmhqDRjBHU-djp6qa6FEMkSafZteawNiO-rjZyFjQ7FI829tRIB-QaAalE39gS1eGgGkr2ZUp9rgW5f-TXHdczLGA_WAjovLqV3zVUZE_9Xsd5zGUPZjFX8HH1rlOQr0ujyexadGI9aE94BPazVKaLr4xry97fQchHESw2lr2MHl60znIuQcumO2SANEPzn9JcycaLWSpUw_n0ygGXeqmROR8pwbYGXJ7EJjKY1Witb11Y8uimT'
            ],
            [
                'id_kategori' => $catMap['ESPRESSO']->id,
                'nama' => 'AMERICANO ICE',
                'slug' => Str::slug('AMERICANO ICE'),
                'deskripsi' => 'A classic iced black coffee.',
                'harga' => 18000,
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCcgec73SMLLT6OzsiRPRlh1fF4MGUKLKVZsYKVEuZ8ZT_u3SgE0hgXMONOiFMYal398AgAyWcsTepXPgUDU1n3m82i_8yqFp6y5ZHPa_5hrksMSDzfxwXH7I-3pPrSYEmtSf8py3frjTTE_uwu8o5Cgx6t84jF3oDhqurmlM4tFckxX4wchkBcHmxXY0js7EPkcifgwSoWbmLaXSM9yL_ou6VUo1vt3xblM13JZpXlqw9kFdwoT4hj'
            ],
            [
                'id_kategori' => $catMap['PASTRY']->id,
                'nama' => 'CROISSANT BUTTER',
                'slug' => Str::slug('CROISSANT BUTTER'),
                'deskripsi' => 'A flaky, golden-brown artisanal croissant.',
                'harga' => 25000,
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBgpOj5J3r-CLRBwyuClUS89IyvQvB6WFyjTeS0do7nmVOKHG6JnEFyFfZa4Jvy-c2ciIZrN7LgexbPgKNLFmj4y6IxOkNUfa7Zw1MhrYrPJ7XadM9IhbOjNysThxYnUCr9gzSYVbNzHl9MGDUWG_jMKzwcbbHTfpd5GZ5XCS9-RGw01AW0O2Wr0h28dDDUYjdsoNGt0KDqXcpqXJXqgK-BcVOQLiqDJNppHcPFjXZQULLnJ4wn50cF'
            ],
            [
                'id_kategori' => $catMap['MILK BASED']->id,
                'nama' => 'LATTE COLD',
                'slug' => Str::slug('LATTE COLD'),
                'deskripsi' => 'A smooth cold caffe latte.',
                'harga' => 20000,
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBfZ1EBjDbGhqPSRthqjfj_rpqbdh39bXOFrHZe23dS94YZRFHrXgy-71xrwLld7xHZLv9eVPXVx5Ndoa6smvcdqUZMyBAtYINJGoysQK9NYhx1w1_t6MdjcSk99j3a2znrErf_JKs8L43gA8cPtCFXkFslv1--vTgMESmS4A_ZAmbnow7eLAk-IlFbLMnAOEi2gaexT6whdOnWV_Oz7hmPvh2sDwjh3IwQblS3zCcLBOpTdSwtLYhZ'
            ],
            [
                'id_kategori' => $catMap['ESPRESSO']->id,
                'nama' => 'ESPRESSO SINGLE SHOT',
                'slug' => Str::slug('ESPRESSO SINGLE SHOT'),
                'deskripsi' => 'A rich, dark espresso.',
                'harga' => 35000,
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIJdjS8F2k8qXUdZO8vXGyJ9e1kSNRCxUeRcUce8hBpmSUi0d5Wa3-nME1dcJrtyBtwTCzflsT6XbQEfQcsHPTfIYEKdRZpQO2Yucn1gpy_0-CD2PdxXQvvFzkLfGoc56u2-BPrZ7IVLxL1TVdqC9NWtpCaMxI75dlvbPUbAs409qmTDCOyPa2T7Uf3U8jnCHvY-YbwADASuOzvqk-VA6RcujFIhuOsFsgm-wHgjD3hvYe1gkK1C8D'
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
