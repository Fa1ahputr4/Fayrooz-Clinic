// public/js/rekamMedis.js
function rekamMedis() {
    return {
        activeTab: 'riwayat',
        tabs: ['riwayat', 'anamnesis', 'pemeriksaan', 'diagnosa', 'tindakan & resep', 'selesai'],

        anamnesisData: {
            keluhanUtama: '',
            riwayatSekarang: '',
            riwayatDahulu: '',
            riwayatPengobatan: '',
            riwayatAlergi: '',
            riwayatKeluarga: '',
            riwayatSosial: ''
        },

        pemeriksaanData: {
            tekananDarah: '',
            suhuTubuh: '',
            denyutNadi: '',
            respirasi: '',
            tB: '',
            bB: '',
            hasilPemeriksaanFisik: ''
        },

        diagnosa: '',
        planning: '',

        generateSubjektif() {
            const d = this.anamnesisData;
            return [
                d.keluhanUtama && `- Keluhan utama: ${d.keluhanUtama}`,
                d.riwayatSekarang && `- Riwayat penyakit sekarang: ${d.riwayatSekarang}`,
                d.riwayatDahulu && `- Riwayat penyakit dahulu: ${d.riwayatDahulu}`,
                d.riwayatPengobatan && `- Riwayat pengobatan: ${d.riwayatPengobatan}`,
                d.riwayatAlergi && `- Alergi: ${d.riwayatAlergi}`,
                d.riwayatKeluarga && `- Riwayat keluarga: ${d.riwayatKeluarga}`,
                d.riwayatSosial && `- Riwayat sosial: ${d.riwayatSosial}`
            ].filter(Boolean).join('\n');
        },

        generateObjektif() {
            const d = this.pemeriksaanData;
            return [
                d.tekananDarah && `- Tekanan darah: ${d.tekananDarah}`,
                d.suhuTubuh && `- Suhu tubuh: ${d.suhuTubuh}`,
                d.denyutNadi && `- Denyut nadi: ${d.denyutNadi}`,
                d.respirasi && `- Respirasi: ${d.respirasi}`,
                                d.respirasi && `- Respirasi: ${d.respirasi}`,
                d.respirasi && `- Respirasi: ${d.respirasi}`,

                d.hasilPemeriksaanFisik && `- Pemeriksaan fisik: ${d.hasilPemeriksaanFisik}`
            ].filter(Boolean).join('\n');
        },

        generateAssessment() {
            return this.diagnosa || '';
        },

        generatePlan() {
            return this.planning || '';
        }
    };
}
