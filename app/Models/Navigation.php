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
                "title"     => "Litbang",
                "url"       => 'javascript:void(0)',
                "index"     => 4,
                "icon"      => 'bx bx-home',
                "child"     => array(
                    array(
                        "title"     => "Hasil Penelitian",
                        "url"       => url('user/hasilpenelitian'),
                        "index"     => 4.1,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Hasil Inovasi",
                        "url"       => url('user/hasilinovasi'),
                        "index"     => 4.2,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                ),
            ),
            array(
                "role"      => ['bendahara'],
                "title"     => "Forum Kelitbangan",
                "url"       => 'javascript:void(0)',
                "index"     => 5,
                "icon"      => 'bx bx-home',
                "child"     => array(
                    array(
                        "title"     => "Usulan Penelitian",
                        "url"       => url('user/usulanpenelitian'),
                        "index"     => 5.1,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Usulan Inovasi Daerah",
                        "url"       => url('user/usulaninovasi'),
                        "index"     => 5.2,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Hasil Kelitbangan Perangkat Daerah",
                        "url"       => url('user/hasilkpd'),
                        "index"     => 5.3,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Hasil Kelitbangan Stakeholder",
                        "url"       => url('user/hasilksh'),
                        "index"     => 5.4,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Kerjasama Penelitian",
                        "url"       => url('user/kerjasama'),
                        "index"     => 5.5,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                ),
            ),
            array(
                "role"      => ['bendahara'],
                "title"     => "Layanan",
                "url"       => 'javascript:void(0)',
                "index"     => 6,
                "icon"      => 'bx bx-home',
                "child"     => array(
                    array(
                        "title"     => "Izin Penelitian",
                        "url"       => url('user/izinpenelitian'),
                        "index"     => 6.1,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Izin Pengabdian Masyarakat",
                        "url"       => url('user/izinpengabdian'),
                        "index"     => 6.2,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Izin Magang/Praktik Kerja Lapangan",
                        "url"       => url('user/izinmagang'),
                        "index"     => 6.3,
                        "icon"      => 'bx bx-home',
                        "child"     => null,
                    ),
                    array(
                        "title"     => "Klinik Penelitian",
                        "url"       => url('user/klinik'),
                        "index"     => 6.4,
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
