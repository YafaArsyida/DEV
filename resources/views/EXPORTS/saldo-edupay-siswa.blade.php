<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Siswa</th>
            <th>Kelas</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswas as $index => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['ms_siswa']['nama_siswa'] }}</td>
                <td>{{ $item['ms_kelas']['nama_kelas'] }}</td>
                <td>{{ $item['saldo_edupay'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>
            <td><strong>{{ $totalSaldo }}</strong></td>
        </tr>
    </tfoot>
</table>
