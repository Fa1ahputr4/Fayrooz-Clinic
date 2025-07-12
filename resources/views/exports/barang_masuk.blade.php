<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: center; font-size: 16px; font-weight: bold;">
                LAPORAN BARANG MASUK
            </th>
        </tr>
       
        <tr>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">No</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Kode Masuk</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Nama Barang</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">No Batch</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Jumlah</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Satuan</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Tanggal Masuk</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Tanggal Expired</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $index => $item)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->kode_masuk ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->no_batch ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ number_format($item->jumlah) ?? '0' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->barang->satuan ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ date('d/m/Y', strtotime($item->tanggal_masuk)) ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->exp_date ? date('d/m/Y', strtotime($item->exp_date)) : '-' }}</td>
                <td style="border: 1px solid #000;">{{ $item->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>