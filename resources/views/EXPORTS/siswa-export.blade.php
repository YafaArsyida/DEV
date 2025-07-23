<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Siswa</th>
            <th>Telepon</th>
            <th>Kelas</th>
            <th>Tabungan</th>
            <th>EduPay</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswas as $index => $item)        
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['ms_siswa']['nama_siswa'] }}</td>
                <td>{{ $item['ms_siswa']['telepon'] }}</td>
                <td>{{ $item['ms_kelas']['nama_kelas'] }}</td>
                <td>{{ $item['saldo_tabungan'] }}</td>
                <td>{{ $item['saldo_edupay'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
