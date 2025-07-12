@props([
    'startDate' => null,
    'endDate' => null,
    'wireStart' => 'startDate',
    'wireEnd' => 'endDate',
    'id' => 'dateRangePicker', // agar bisa digunakan lebih dari 1 kali
])

<div x-data="{
    init() {
        $('#{{ $id }}').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Batal',
                applyLabel: 'Terapkan',
                daysOfWeek: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                firstDay: 1
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $wire.set('{{ $wireStart }}', picker.startDate.format('YYYY-MM-DD'));
            $wire.set('{{ $wireEnd }}', picker.endDate.format('YYYY-MM-DD'));
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        }).on('cancel.daterangepicker', function(ev, picker) {
            $wire.set('{{ $wireStart }}', null);
            $wire.set('{{ $wireEnd }}', null);
            $(this).val('');
        });
    }
}" x-init="init()"
    x-effect="
    if ($wire.{{ $wireStart }} === null && $wire.{{ $wireEnd }} === null) {
        $('#{{ $id }}').val('');
    }
">
    <input id="{{ $id }}" type="text"
        class=" w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500"
        placeholder="Pilih rentang tanggal" readonly>
</div>
