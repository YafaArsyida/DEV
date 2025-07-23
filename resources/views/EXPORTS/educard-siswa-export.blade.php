<table>
    <thead>
        <tr>
            <th>ms_siswa_id</th>
            <th>nama</th>
            <th>educard</th>
            <th>kelas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswas as $index => $item)        
            <tr>
                <td>{{ $item['ms_siswa']['ms_siswa_id'] }}</td>
                <td>{{ $item['ms_siswa']['nama_siswa'] }}</td>
                <td>{{ $item['ms_siswa']['ms_educard']['kode_kartu'] ?? '' }}</td>
                <td>{{ $item['ms_kelas']['nama_kelas'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
