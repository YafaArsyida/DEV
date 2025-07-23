<table>
    <thead>
        <tr>
            <th>ms_pegawai_id</th>
            <th>nama</th>
            <th>unit</th>
            <th>educard</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pegawais as $index => $item)        
            <tr>
                <td>{{ $item['ms_pegawai_id'] }}</td>
                <td>{{ $item['nama_pegawai'] }}</td>
                <td>{{ $item['ms_jenjang']['nama_jenjang'] }}</td>
                <td>{{ $item['ms_educard']['kode_kartu'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
