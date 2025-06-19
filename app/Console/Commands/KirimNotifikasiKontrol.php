<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\RekmedUmum;

class KirimNotifikasiKontrol extends Command
{
    protected $signature = 'notifikasi:kontrol';
    protected $description = 'Kirim notifikasi WhatsApp H-1 untuk jadwal kontrol ulang';

    public function handle()
    {
        $besok = Carbon::tomorrow()->format('Y-m-d');

        $rekmeds = RekmedUmum::with('pendaftaran.pasien')
            ->where('kontrol_ulang', true)
            ->whereDate('jadwal_kontrol', $besok)
            ->where('notifikasi_terkirim', false)
            ->get();

        foreach ($rekmeds as $rekmed) {
            $pasien = $rekmed->pendaftaran->pasien;
            $noWa = 6289666711399;

            if ($noWa) {
                $pesan = "Halo *{$pasien->nama}*,\n\nIni pengingat kontrol ulang Anda di klinik kami besok (*" .
                    Carbon::parse($rekmed->jadwal_kontrol)->translatedFormat('l, d F Y') . "*).\n\n📌 Catatan: {$rekmed->catatan_jadwal}\n\nMohon hadir tepat waktu, terima kasih 🙏.";

                try {
                    Http::withHeaders([
                        'Authorization' => 'sx7aapgSLvDzGoWeBtrT',
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $noWa,
                        'message' => $pesan,
                        'countryCode' => '62',
                    ]);

                    $rekmed->update(['notifikasi_terkirim' => true]);
                    $this->info("Notifikasi dikirim ke: {$noWa}");
                } catch (\Exception $e) {
                    \Log::error("Gagal kirim WA Fonnte ke {$noWa}: " . $e->getMessage());
                    $this->error("Gagal kirim ke: {$noWa}");
                }
            }
        }
    }
}
