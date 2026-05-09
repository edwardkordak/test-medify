<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    var start_date = '';
    var end_date = '';
    var data_per_fetch = 500;
    var data_fetched = 0;

    $(document).ready(function() {

        $('#table').DataTable({
            searching: false,
            order: [
                [0, 'desc']
            ],
        });

        getData();
    });

    $('.btn-get-data').click(function() {
        getData();
    });

    function rupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function getData() {

        $('#loading-filter').show();

        var dataTableObj = $('#table').DataTable();

        var filter_kode = $('#filter-kode').val();
        var filter_nama = $('#filter-nama').val();
        var filter_harga_min = $('#filter-harga-min').val();
        var filter_harga_max = $('#filter-harga-max').val();

        dataTableObj.clear();

        $.ajax({
            url: '{{ url("master-items/search") }}',
            type: 'GET',
            dataType: 'json',

            tryCount: 0,
            retryLimit: 3,

            data: {
                kode: filter_kode,
                nama: filter_nama,
                hargamin: filter_harga_min,
                hargamax: filter_harga_max
            },

            success: function(results) {

                var data = results.data;

                $.each(data, function(index, item) {

                    var array_temp = [];

                    var harga_beli = parseFloat(item.harga_beli) || 0;
                    var laba = parseFloat(item.laba) || 0;

                    var harga_jual =
                        harga_beli +
                        (harga_beli * laba / 100);

                    harga_jual = Math.round(harga_jual);

                    var kode = item.kode;

                    var html = `
                        <a href="{{ url('master-items/view/') }}/${kode}"
                           class="btn btn-primary btn-sm">
                           View
                        </a>
                    `;

                    array_temp.push(item.kode);

                    var foto = '-';

                    if (item.foto != null && item.foto != '') {

                        foto = `
                            <img src="/storage/${item.foto}"
                                 width="60"
                                 height="60"
                                 style="object-fit:cover;border-radius:6px">
                        `;
                    }

                    array_temp.push(foto);

                    array_temp.push(item.nama ?? '-');
                    array_temp.push(item.jenis ?? '-');

                    array_temp.push(rupiah(harga_beli));

                    array_temp.push(rupiah(harga_jual));

                    array_temp.push(item.supplier ?? '-');

                    array_temp.push(html);

                    dataTableObj.row.add(array_temp);
                });

                dataTableObj.draw();

                $('#loading-filter').hide();
            },

            error: function(xhr, textStatus, errorThrown) {

                this.tryCount++;

                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }

                alert('Terjadi kesalahan server, tidak dapat mengambil data');

                $('#loading-filter').hide();
            }
        });
    }
</script>