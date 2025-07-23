<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Siswa</th>
            <th>Kelas</th>
            <th>Transaksi</th>
            <th>Petugas</th>
            <th>Setoran</th>
            <th>Penarikan</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($laporans as $laporan)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $laporan['tanggal'] }}</td>
                <td>{{ $laporan['nama_siswa'] }}</td>
                <td>{{ $laporan['ms_penempatan_siswa']['ms_kelas']['nama_kelas'] }}</td>
                <td>{{ $laporan['jenis_transaksi'] }}</td>
                <td>{{ $laporan['ms_pengguna']['nama'] ?? '-' }}</td>
                <td>{{ $laporan['jenis_transaksi'] === 'setoran' ? $laporan['nominal'] : '-' }}</td>
                <td>{{ $laporan['jenis_transaksi'] === 'penarikan' ? $laporan['nominal'] : '-' }}</td>
                <td>{{ $laporan['deskripsi'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong>Total Kredit</strong></td>
            <td colspan="1"><strong>{{ $totalKredit }}</strong></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total Debit</strong></td>
            <td colspan="1"><strong>{{ $totalDebit }}</strong></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total Saldo</strong></td>
            <td colspan="1"><strong>{{ $totalSaldo }}</strong></td>
        </tr>
    </tfoot>
</table>
