<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>Tagihan</th>
            <th>Kategori</th>
            <th>Tagihan</th>
            <th>Dibayarkan</th>
            <th>Kekurangan</th>
            <th>Presentase</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}.</td>
                <td>{{ $item['nama_jenis_tagihan'] ?? '-' }}</td>
                <td>{{ $item['nama_kategori_tagihan'] ?? '-' }}</td>
                <td>{{ $item['estimasi'] }}</td>
                <td>{{ $item['dibayarkan'] }}</td>
                <td>{{ $item['kekurangan'] }}</td>
                <td>{{ $item['presentase'] }}%</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th colspan="2"><strong>TOTAL</strong></th>
            <th class="text-right">
                <strong>{{ $totals['totalEstimasi'] ?? 0 }}</strong>
            </th>
            <th class="text-right">
                <strong>{{ $totals['totalDibayarkan'] ?? 0 }}</strong>
            </th>
            <th class="text-right">
                <strong>{{ $totals['totalKekurangan'] ?? 0 }}</strong>
            </th>
            <th class="text-right">
                <strong>{{ $totals['totalPresentase'] ?? 0 }}%</strong>
            </th>
        </tr>
    </tfoot>
</table>
