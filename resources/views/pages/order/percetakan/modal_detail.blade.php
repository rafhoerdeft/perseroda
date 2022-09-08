@push('modal')
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rincian Order <span></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="tbl_detail"
                        class="table table-hover table-bordered table-striped table-responsive d-lg-table">
                        <thead> </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js_script')
    <script>
        function showDetail(data) {
            var no_order = $(data).data('no_order');
            var dasar_jenis = $(data).data('dasar_jenis');
            var dasar_tgl = $(data).data('dasar_tgl');
            var dasar_nomor = $(data).data('dasar_nomor');
            var dasar_oleh = $(data).data('dasar_oleh');
            var tgl_selesai = $(data).data('tgl_selesai');
            var lampiran_konsep = $(data).data('lampiran_konsep');
            var koordinator_konsep_tgl = $(data).data('koordinator_konsep_tgl');
            var koordinator_konsep_nama = $(data).data('koordinator_konsep_nama');
            var lain_lain = $(data).data('lain_lain');
            var jenis_pesanan = $(data).data('jenis_pesanan');
            var jml_pesanan = $(data).data('jml_pesanan');
            var jenis_bahan = $(data).data('jenis_bahan');
            var ukuran_isi = $(data).data('ukuran_isi');
            var warna_tinta = $(data).data('warna_tinta');
            var gramatur = $(data).data('gramatur');
            var muka_halaman = $(data).data('muka_halaman');
            var pakai_nomor = $(data).data('pakai_nomor');
            var mulai_nomor = $(data).data('mulai_nomor');
            var finishing_lepas = $(data).data('finishing_lepas');
            var finishing_lem = $(data).data('finishing_lem');
            var finishing_jilid = $(data).data('finishing_jilid');
            var finishing_paku = $(data).data('finishing_paku');
            var finishing_perforasi = $(data).data('finishing_perforasi');
            var ket_cetakan = $(data).data('ket_cetakan');

            var list = "<tr class='bg-secondary text-white'> <th colspan='2'> Detail </th></tr>";
            list += "<tr> <th> Dasar Order </th> <td>" + dasar_jenis + "</td> </tr>";
            if (dasar_jenis == 'Surat') {
                list += "<tr> <th> Tanggal Surat </th> <td>" + dasar_tgl + "</td> </tr>";
                list += "<tr> <th> Nomor Surat </th> <td>" + dasar_nomor + "</td> </tr>";
            } else {
                list += "<tr> <th> Tanggal </th> <td>" + dasar_tgl + "</td> </tr>";
                list += "<tr> <th> Oleh </th> <td>" + dasar_oleh + "</td> </tr>";
            }
            list += "<tr> <th> Tanggal Selesai </th> <td>" + tgl_selesai + "</td> </tr>";
            list += "<tr> <th> Lamp. Contoh/Konsep </th> <td>" + (lampiran_konsep ? 'Ada' : 'Tidak Ada') + "</td> </tr>";
            if (lampiran_konsep) {
                list += "<tr> <th> Koordinator Konsep </th> <td>" + koordinator_konsep_nama + "</td> </tr>";
                list += "<tr> <th> Tanggal Konsep </th> <td>" + koordinator_konsep_tgl + "</td> </tr>";
            }
            list += "<tr> <th> Lain-Lain </th> <td>" + lain_lain + "</td> </tr>";
            list += "<tr class='bg-secondary text-white'> <th colspan='2'> Pesanan </th></tr>";
            list += "<tr> <th> Jenis Pesanan </th> <td>" + jenis_pesanan + "</td> </tr>";
            list += "<tr> <th> Jumlah </th> <td>" + jml_pesanan + "</td> </tr>";
            list += "<tr class='bg-secondary text-white'> <th colspan='2'> Bahan Digunakan </th></tr>";
            list += "<tr> <th> Jenis Bahan </th> <td>" + jenis_bahan + "</td> </tr>";
            list += "<tr> <th> Ukuran/Isi </th> <td>" + ukuran_isi + "</td> </tr>";
            list += "<tr> <th> Warna </th> <td>" + warna_tinta + "</td> </tr>";
            list += "<tr> <th> Gramatur </th> <td>" + gramatur + "</td> </tr>";
            list += "<tr> <th> Muka Halaman </th> <td>" + muka_halaman + "</td> </tr>";
            list += "<tr> <th> Pakai Nomor </th> <td>" + (pakai_nomor ? 'Ya' : 'Tidak') + "</td> </tr>";
            if (pakai_nomor) {
                list += "<tr> <th> Mulai Nomor </th> <td>" + mulai_nomor + "</td> </tr>";
            }
            list += "<tr class='bg-secondary text-white'> <th colspan='2'> Finishing </th></tr>";
            list += "<tr> <th> Lepas </th> <td>" + (finishing_lepas ? '✔' : '-') + "</td> </tr>";
            list += "<tr> <th> Lem </th> <td>" + (finishing_lem ? '✔' : '-') + "</td> </tr>";
            list += "<tr> <th> Jilid </th> <td>" + (finishing_jilid ? '✔' : '-') + "</td> </tr>";
            list += "<tr> <th> Paku </th> <td>" + (finishing_paku ? '✔' : '-') + "</td> </tr>";
            list += "<tr> <th> Perforasi </th> <td>" + (finishing_perforasi ? '✔' : '-') + "</td> </tr>";
            list += "<tr class='bg-secondary text-white'> <th colspan='2'> Catatan </th></tr>";
            list += "<tr> <th> Keterangan </th> <td>" + ket_cetakan + "</td> </tr>";

            $('#detailModal .modal-title span').html('(' + no_order + ')');
            $('#detailModal table thead').html(list);

            $('#detailModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#detailModal').modal('show');
        }
    </script>
@endpush
