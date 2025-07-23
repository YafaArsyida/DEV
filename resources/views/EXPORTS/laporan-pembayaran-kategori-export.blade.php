<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>Kategori Tagihan</th>
            <th>Estimasi</th>
            <th>Dibayarkan</th>
            <th>Kekurangan</th>
            <th>Presentase</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporans as $index => $laporan)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $laporan['nama_kategori_tagihan'] }}</td>
            <td>{{ $laporan['estimasi'] }}</td>
            <td>{{ $laporan['dibayarkan'] }}</td>
            <td>{{ $laporan['kekurangan'] }}</td>
            <td>{{ $laporan['presentase'] }}%</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th><strong>TOTAL</strong></th>
            <th class="text-right">
                <strong>Rp{{ $totals['totalEstimasi'] ?? 0 }}</strong>
            </th>
            <th class="text-right">
                <strong>Rp{{ $totals['totalDibayarkan'] ?? 0 }}</strong>
            </th>
            <th class="text-right">
                <strong>Rp{{ $totals['totalKekurangan'] ?? 0 }}</strong>
            </th>
            <th class="text-right">
                <strong>{{ $totals['totalPresentase'] ?? 0 }}%</strong>
            </th>
        </tr>
    </tfoot>
</table>
