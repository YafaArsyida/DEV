<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Total Tagihan</th>
            <th>Rincian Tagihan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporans as $index => $laporan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $laporan['nama_siswa'] }}</td>
                <td>{{ $laporan['nama_kelas'] }}</td>
                <td>{{ $laporan['total_tagihan'] }}</td>
                <td>
                    @foreach($laporan['rincian_tagihan'] as $rincian)
                        {{ $rincian['nama_jenis_tagihan'] }} {{ (int)$rincian['jumlah_tagihan'] }}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"><strong>TOTAL</strong></td>
            <td><strong>{{ (int)$totals['totalTagihan'] }}</strong></td>
        </tr>
    </tfoot>
</table>
