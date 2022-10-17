@extends('template.direct_print')

@section('print-content')
    <div id="container" style="width: 58mm; margin-left: auto; margin-right: auto;">
        <table style="margin-top: -10px; margin-bottom: 0px; width: 100%">
            <tr>
                <td>
                    <img style="margin-top: 5px;" src="{{ show_image('logo.png') }}" width="35" height="35">
                </td>

                <td style="width: 100%; text-align: center; color: black">
                    {{-- <div style="font-family: arial; font-size: 5pt;">PERUSAHAAN DAERAH ANEKA USAHA</div> --}}
                    <h4 style="font-family: arial; color: black; font-size: 6pt; font-weight: bold; margin: 0px">
                        PERUSAHAAN DAERAH ANEKA USAHA <br>
                        KABUPATEN MAGELANG
                    </h4>

                    <div style="font-size: 5pt; font-family: arial; line-height: 7px">
                        Jl. Veteran No. 5 Magelang 56117 <br> Telp. (0293) 362310 <br>
                        Email: pdaumagelangkab@gmail.com
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="background-color: black; height: 1px"> </td>
            </tr>

            {{-- <tr>
                <td colspan="2" align="center"
                    style="padding-left: 10px; padding-right: 10px; font-family: aral; font-weight: bold; font-size: 6pt">
                    TANDA BUKTI PEMBAYARAN TERA/TERA ULANG UTTP
                </td>
            </tr> --}}

            {{-- <tr style="font-size: 5pt; font-family: arial">
                <td valign="top">DASAR : </td>
                <td>
                    PERDA KABUPATEN MAGELANG NO 1 TAHUN 2018 TENTANG RETRIBUSI TERA/TERA ULANG
                </td>
            </tr> --}}

            <tr>
                <table style="font-family: arial; margin: 0px; margin-bottom: 5px; width: 100%">
                    <tr style="font-weight: bold; font-size: 7pt">
                        <td width="40">No.</td>
                        <td>:</td>
                        <td>{{ $order->no_order }}</td>
                    </tr>

                    <tr style="font-size: 7pt">
                        <td width="40">Nama</td>
                        <td>:</td>
                        <td>{{ ucwords($order->nama_klien) }}</td>
                    </tr>

                    <tr style="font-size: 7pt">
                        <td>Waktu</td>
                        <td>:</td>
                        <td>{{ date('d/m/Y H:i:s', strtotime($order->created_at)) }}</td>
                    </tr>

                    {{-- <tr style="font-size: 7pt">
                        <td>Tempat</td>
                        <td>:</td>
                        <td>{{ $nama_pasar }}</td>
                    </tr> --}}
                </table>
            </tr>

            <tr>
                <table id="tbl_bayar" style="font-size: 7pt; font-family: arial; width: 100%; margin: 0px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th align="left">Produk</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $no = 1;
                            $tot_bayar = 0;
                        @endphp

                        @foreach ($order->rincian_order as $item)
                            @php
                                $total = $item->tarif->harga * $item->jml_order;
                                $tot_bayar += $total;
                            @endphp

                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->tarif->produk->nama_produk . ' @' . nominal($item->tarif->harga) }}
                                </td>
                                <td align="right" valign="bottom">{{ $item->jml_order }}</td>
                                <td align="right" valign="bottom">{{ nominal($total) }}</td>
                            </tr>
                        @endforeach

                    </tbody>

                    <tfoot>
                        <tr style="font-weight: bold;">
                            <td colspan="3">Total Bayar (Rp)</td>
                            <td id="total_bayar" align="right">{{ nominal($tot_bayar) }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" style="background-color: black; height: 0.5px; padding: 0px;"></td>
                        </tr>
                    </tfoot>

                </table>
            </tr>

            <tr>
                <table style="font-family: arial; width: 100%; margin-bottom: 5px;">
                    <tr style="font-weight: bold; font-size: 7pt">
                        <td width="50">Kasir</td>
                        <td>:</td>
                        <td>{{ $order->user->nama_user }}</td>
                    </tr>
                </table>
            </tr>

            <tr>
                <table style="font-size: 8pt; font-style: italic; width: 100%; margin-bottom: 10px;">
                    <tr>
                        <td align="center">
                            --- Terima Kasih ---
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            Kepuasan Anda Harapan Kami
                        </td>
                    </tr>
                </table>
            </tr>

            <tr>
                <table style="margin: auto">
                    <tr>
                        <td>
                            <span>
                                ------------------------------------
                            </span>
                        </td>
                    </tr>
                </table>
            </tr>
        </table>

        <div id="aside"></div>
    </div>
@endsection

@push('css_style')
    <style type="text/css">
        body {
            page-break-after: always;
        }
    </style>
@endpush
