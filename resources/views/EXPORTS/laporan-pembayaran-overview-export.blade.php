<!-- Bulan -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Periode</th>
            <th>Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($months as $index => $month)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($month['bulan'], 'F Y') }}</td>
                <td>{{ $month['total'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold;">
            <td></td>
            <td><strong>TOTAL</strong></td>
            <td><strong>{{ $totalClasses }}</strong></td>
        </tr>
    </tfoot>
</table>

<!-- Metode Pembayaran -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Metode</th>
            <th>Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($methods as $index => $item)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td>{{ $item['metode'] }}</td>
                <td>{{ $item['total'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold;">
            <td></td>
            <td><strong>TOTAL</strong></td>
            <td><strong>{{ $totalClasses }}</strong></td>
        </tr>
    </tfoot>
</table>

<!-- Kelas -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kelas</th>
            <th>Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($classes as $index => $class)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td>{{ $class['nama_kelas'] }}</td>
                <td>{{ $class['total'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold;">
            <td></td>
            <td><strong>TOTAL</strong></td>
            <td><strong>{{ $totalClasses }}</strong></td>
        </tr>
    </tfoot>
</table>