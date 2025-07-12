<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: center; font-size: 16px; font-weight: bold;">
                LAPORAN BARANG KELUAR
            </th>
        </tr>

        <tr>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">No</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Nama Barang</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Jumlah</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Satuan</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Tanggal Keluar</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Status Keluar</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $index => $item)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ number_format($item->jumlah) ?? '0' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->barang->satuan ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">
                    {{ date('d/m/Y', strtotime($item->tgl_keluar)) ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->status_keluar ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $item->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
