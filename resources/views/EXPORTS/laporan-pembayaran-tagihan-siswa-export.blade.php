<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Transaksi</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jenis Tagihan</th>
            <th>Kategori Tagihan</th>
            <th>Jumlah Bayar</th>
            <th>Nama Pengguna</th>
            <th>Metode Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporans as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item['tanggal_transaksi'], 'd F Y H:i') }}</td>
            <td>{{ $item['nama_siswa'] }}</td>
            <td>{{ $item['kelas'] }}</td>
            <td>{{ $item['jenis_tagihan'] }}</td>
            <td>{{ $item['kategori_tagihan'] }}</td>
            <td class="text-center">
                {{ intval($item['jumlah_bayar']) }}
            </td>
            <td>{{ $item['petugas'] }}</td>
            <td class="text-end">{{ $item['metode_pembayaran'] }}</td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" style="text-align: right;"><strong>TOTAL</strong></td>
                <td>
                    <strong>{{ $totals['totalPembayaran'] ?? 0 }}</strong>
                </td>
            </tr>
        </tfoot>
   </table>
