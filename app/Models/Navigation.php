<?php

namespace App\Models;

class Navigation
{
    static function getMenu()
    {
        $data = [
            array(
                "role"      => ['kasir', 'akuntansi', 'bendahara'],
                "title"     => "Beranda",
                "url"       => url('dashboard'),
                "index"     => 1,
                "icon"      => "bx bx-home",
                "child"     => null,
            ),
            // array(
            //     "role"      => ['bendahara', 'kasir'],
            //     'header'    => 'Titit',
            // ),
            array(
                "role"      => ['kasir'],
                "title"     => "Perdagangan",
                "url"       => 'javascript:void(0)',
                "index"     => 2,
                "icon"      => 'bx bx-cart-alt',
                "child"     => array(
                    array(
                        "title"     => "List Order",
                        "url"       => url('order/perdagangan'),
                        "index"     => 2.1,
                        "icon"      => null,
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Input Order",
                        "url"       => url('order/perdagangan/add'),
                        "index"     => 2.2,
                        "icon"      => null,
                        "child"     => null,
                    ),
                ),
            ),
            array(
                "role"      => ['kasir'],
                "title"     => "Percetakan",
                "url"       => 'javascript:void(0)',
                "index"     => 3,
                "icon"      => 'bx bx-printer',
                "child"     => array(
                    array(
                        "title"     => "List Order",
                        "url"       => url('order/percetakan'),
                        "index"     => 3.1,
                        "icon"      => null,
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Input Order",
                        "url"       => url('order/percetakan/add'),
                        "index"     => 3.2,
                        "icon"      => null,
                        "child"     => null,
                    ),
                ),
            ),
            array(
                "role"      => ['kasir'],
                "title"     => "Jasa & Lainnya",
                "url"       => 'javascript:void(0)',
                "index"     => 4,
                "icon"      => 'lni lni-hand',
                "child"     => array(
                    array(
                        "title"     => "List Order",
                        "url"       => url('order/jasa'),
                        "index"     => 4.1,
                        "icon"      => null,
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Input Order",
                        "url"       => url('order/jasa/add'),
                        "index"     => 4.2,
                        "icon"      => null,
                        "child"     => null,
                    ),
                ),
            ),
            array(
                "role"      => ['kasir'],
                "title"     => "Stok Produk",
                "url"       => 'javascript:void(0)',
                "index"     => 5,
                "icon"      => "bx bx-box",
                "child"     => [
                    array(
                        "title"     => "List Data",
                        "url"       => route('produk.list'),
                        "index"     => 5.1,
                        "icon"      => null,
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Tambah Data",
                        "url"       => route('produk.add'),
                        "index"     => 5.2,
                        "icon"      => null,
                        "child"     => null,
                    ),
                ],
            ),
            array(
                "role"      => ['bendahara'],
                "title"     => "Transaksi Masuk",
                "url"       => 'javascript:void(0)',
                "index"     => 6,
                "icon"      => 'bx bx-home',
                "child"     => array(
                    array(
                        "title"     => "Perdagangan",
                        "url"       => url('order/perdagangan'),
                        "index"     => 6.1,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Percetakan",
                        "url"       => url('order/percetakan'),
                        "index"     => 6.2,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Jasa & Lainnya",
                        "url"       => url('order/jasa'),
                        "index"     => 6.3,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                ),
            ),
            array(
                "role"      => ['bendahara'],
                "title"     => "Transaksi Keluar",
                "url"       => 'javascript:void(0)',
                "index"     => 7,
                "icon"      => 'bx bx-home',
                "child"     => array(
                    array(
                        "title"     => "Belanja Produk",
                        "url"       => url('transaksi/keluar/produk'),
                        "index"     => 7.1,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Belanja Lainnya",
                        "url"       => url('transaksi/keluar/lain'),
                        "index"     => 7.2,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                ),
            ),
            // array(
            //     "role"       => ['kasir'],
            //     "title"     => "FAQ",
            //     "url"       => url('user/faq'),
            //     "index"     => 7,
            //     "icon"      => 'bx bx-home',
            //     "child"     => null,
            // ),
        ];

        return $data;
    }
}
