<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Pendaftaran</th>
            <th>Nama Pasien</th>
            <th>No RM</th>
            <th>Layanan</th>
            <th>Detail</th>
            <th>Tanggal Kunjungan</th>
            <th>Status</th>
            <th>Catatan Tambahan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pendaftarans as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->kode_pendaftaran }}</td>
                <td>{{ $p->pasien->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->pasien->nomor_rm ?? '-' }}</td>
                <td>{{ $p->layanan->nama ?? '-' }}</td>
                <td>{{ $p->layananDetail->nama_layanan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->catatan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
