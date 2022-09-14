@push('modal')
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rincian Order <span></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead class="text-center">
                            <th>No.</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Jml. Order</th>
                            <th>Biaya Tambahan</th>
                            <th>Total (Rp)</th>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <th colspan="6">Total Bayar</th>
                            <th id="total_bayar"></th>
                        </tfoot>
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
            var nama_produk = $(data).data('nama_produk').split(';');
            var satuan = $(data).data('satuan').split(';');
            var harga = $(data).data('harga').toString().split(';');
            var jml_order = $(data).data('jml_order').toString().split(';');
            var biaya_tambahan = $(data).data('biaya_tambahan').toString().split(';');

            var row = '';
            var tot_bayar = 0;
            for (let i = 0; i < nama_produk.length; i++) {
                let tot = (parseInt(jml_order[i]) * parseInt(harga[i])) + parseInt(biaya_tambahan[i]);
                row += '<tr>' +
                    '<td align="center">' + (i + 1) + '</td>' +
                    '<td>' + nama_produk[i] + '</td>' +
                    '<td align="center">' + satuan[i] + '</td>' +
                    '<td align="right">' + formatRupiah(harga[i]) + '</td>' +
                    '<td align="right">' + formatRupiah(jml_order[i]) + '</td>' +
                    '<td align="right">' + formatRupiah(biaya_tambahan[i]) + '</td>' +
                    '<td align="right">' + formatRupiah(tot.toString()) + '</td>' +
                    '</tr>';
                tot_bayar += parseInt(tot);
            }

            $('#detailModal .modal-title span').html('(' + no_order + ')');
            $('#detailModal table tbody').html(row);
            $('#detailModal #total_bayar').html(formatRupiah(tot_bayar.toString(), 'Rp. '));
            $('#detailModal #total_bayar').addClass('text-end');

            $('#detailModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#detailModal').modal('show');
        }
    </script>
@endpush
