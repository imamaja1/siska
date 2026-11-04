# Aturan Kerja Agen (Workflow & Execution Protocol)

File ini mengatur alur kerja Antigravity / Gemini dalam merespons setiap prompt atau permintaan instruksi dari pengguna.

---

## 🎯 Alur Kerja Dua Fase (Two-Phase Workflow)

Secara default, agen **TIDAK BOLEH** langsung menulis file, mengedit file, atau menjalankan perintah yang memodifikasi sistem sebelum rencana disetujui. Setiap tugas harus melalui dua fase:

```
[Prompt Pengguna] 
       │
       ▼
[FASE 1: Analisis, Evaluasi & Rencana] ──► (Hanya Tool Read-Only)
       │
       ▼
[GERBANG VALIDASI] ──► Menunggu konfirmasi ("kerjakan" / "eksekusi")
       │
       ▼
[FASE 2: Eksekusi & Verifikasi] ──► (Terapkan perubahan kode)
```

---

## 📋 Fase 1: Analisis, Evaluasi, dan Rencana

Ketika menerima instruksi/prompt baru yang membutuhkan modifikasi kode atau sistem:

1. **Gunakan Tool Read-Only**:
   - Periksa dan baca file yang relevan (`read_file`, pencarian teks, dsb.) untuk memahami konteks dan struktur kode sebenarnya.
   - **DILARANG KERAS** menggunakan `write_file`, `edit_file`, atau menjalankan command yang mengubah kode/state pada fase ini.

2. **Sajikan Format Standar**:
   Respon agen harus memuat struktur berikut secara jelas dan teratur:

   ### 1. 🔍 Analisis Kebutuhan
   - Ringkasan pemahaman mengenai masalah, fitur, atau perubahan yang diminta.
   - Identifikasi komponen, file, model, controller, view, library, atau tabel database yang terdampak.

   ### 2. ⚖️ Evaluasi Teknis & Risiko
   - Dampak terhadap fungsionalitas yang sudah ada (potensi *breaking changes* atau *side effects*).
   - Aspek keamanan (misal: sanitasi input, validasi data, autentikasi/otorisasi).
   - Kompatibilitas dengan arsitektur proyek (CodeIgniter / PHP).
   - Alternatif pendekatan jika ada pertimbangan teknis lain.

   ### 3. 📝 Rencana Kerja (Action Plan)
   - Daftar langkah kerja spesifik dan bertahap:
     - **Langkah 1**: File target & perubahan yang akan dilakukan.
     - **Langkah 2**: File target berikutnya atau penambahan fungsi.
     - **Langkah n**: Pengujian / verifikasi sintaks & fungsionalitas.

   ### 4. 🛑 Gerbang Validasi (Wajib Ditampilkan di Akhir Respon)
   Sertakan blok penutup persis atau setara dengan berikut:
   > ✋ **Validasi Diperlukan**:  
   > Rencana di atas belum dieksekusi. Ketik **"kerjakan"** atau **"eksekusi"** untuk mulai menerapkan perubahan, atau berikan arahan revisi jika ada yang perlu disesuaikan.

---

## 🚦 Fase Validasi (Validation Gate)

- **Menunggu Persetujuan**: Agen berhenti dan menunggu balasan pengguna.
- **Kondisi Lanjut**:
  - Jika pengguna membalas dengan kata kunci: **"kerjakan"**, **"eksekusi"**, **"lanjut"**, **"gas"**, **"proceed"**, atau kalimat persetujuan eksplisit, agen **langsung masuk ke Fase 2 (Eksekusi)**.
- **Kondisi Revisi / Klarifikasi**:
  - Jika pengguna memberikan kritik, pertanyaan, atau penyesuaian rencana, agen memperbarui analisis dan menyajikan rencana baru yang disesuaikan, lalu kembali meminta validasi.
- **Pengecualian (Tanya Jawab Bebas)**:
  - Jika prompt pengguna murni berupa pertanyaan konseptual, penjelasan kode, atau diskusi tanpa permintaan perubahan file, agen dapat menjawab langsung tanpa memerlukan gerbang validasi.

---

## ⚡ Fase 2: Eksekusi & Verifikasi

Setelah pengguna memvalidasi dengan kata kunci persetujuan:

1. **Implementasi Bertahap**:
   - Lakukan perubahan sesuai poin-poin rencana yang telah disepakati.
   - Gunakan `edit_file` atau `write_file` dengan cermat.
   - Pertahankan integritas kode, standar penulisan (PSR / pola CodeIgniter), serta komentar dan docstring yang sudah ada.

2. **Verifikasi & Validasi Hasil**:
   - Periksa error/sintaks (misalnya linter atau pengecekan sintaks PHP jika diperlukan).
   - Pastikan tidak ada dependensi yang rusak.

3. **Laporan Akhir (Ringkasan Perubahan)**:
   - Sebutkan file-file yang telah diubah beserta tautan file-nya.
   - Ringkas apa saja yang telah diselesaikan.
   - Berikan panduan verifikasi manual untuk pengguna (cara menguji fitur/perbaikan).
