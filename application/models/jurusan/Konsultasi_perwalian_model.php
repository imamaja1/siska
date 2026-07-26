<?php

class Konsultasi_perwalian_model extends CI_Model
{
    public function filter($angkatan, $kode_program_studi, $limit, $offset)
    {

        
        if($kode_program_studi == 1) {
            $query = $this->db->select('kp.nim, nama_mahasiswa, nama_dosen')
            ->from('konsultasi_perwalian as kp')
            ->join('mahasiswa as mah', 'kp.nim=mah.nim')
            ->join('perwalian as per', 'kp.nim=per.nim')
            ->join('dosen', 'dosen.kode_dosen=per.kode_dosen')
            ->where_in('mah.program_studi_kode', array('1','3'))
            ->where('substring(kp.nim,1,2)', $angkatan)
            ->limit($limit, $offset)
            ->group_by('kp.nim')
            ->order_by('kp.nim ASC')
            ->get()->result();

        return $query;
        }else{
            $query = $this->db->select('kp.nim, nama_mahasiswa, nama_dosen')
            ->from('konsultasi_perwalian as kp')
            ->join('mahasiswa as mah', 'kp.nim=mah.nim')
            ->join('perwalian as per', 'kp.nim=per.nim')
            ->join('dosen', 'dosen.kode_dosen=per.kode_dosen')
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('substring(kp.nim,1,2)', $angkatan)
            ->limit($limit, $offset)
            ->group_by('kp.nim')
            ->order_by('kp.nim ASC')
            ->get()->result();

        return $query;
        }
    }

    public function count_data_filter($angkatan, $kode_program_studi)
    {

            $query = $this->db->select('kp.nim, nama_mahasiswa, nama_dosen')
                ->from('konsultasi_perwalian as kp')
                ->join('mahasiswa as mah', 'kp.nim=mah.nim')
                ->join('perwalian as per', 'kp.nim=per.nim')
                ->join('dosen', 'dosen.kode_dosen=per.kode_dosen')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('substring(kp.nim,1,2)', $angkatan)
                ->group_by('kp.nim')
                ->order_by('kp.nim ASC')
                ->get()->result();

        return $query;
    }

    public function cari($keyword, $limit, $offset)
    {
        $query = $this->db->select('kp.nim, nama_mahasiswa, nama_dosen')
            ->from('konsultasi_perwalian as kp')
            ->join('mahasiswa as mah', 'kp.nim=mah.nim')
            ->join('perwalian as per', 'kp.nim=per.nim')
            ->join('dosen', 'dosen.kode_dosen=per.kode_dosen')
            ->like('nama_mahasiswa',$keyword,'both')
            ->or_like('mah.nim',$keyword,'both')
            ->limit($limit, $offset)
            ->group_by('kp.nim')
            ->order_by('kp.nim ASC')
            ->get()->result();

        return $query;
    }

    public function count_cari($keyword)
    {
        $query = $this->db->select('kp.nim, nama_mahasiswa, nama_dosen')
            ->from('konsultasi_perwalian as kp')
            ->join('mahasiswa as mah', 'kp.nim=mah.nim')
            ->join('perwalian as per', 'kp.nim=per.nim')
            ->join('dosen', 'dosen.kode_dosen=per.kode_dosen')
            ->like('nama_mahasiswa',$keyword,'both')
            ->or_like('mah.nim',$keyword,'both')
            ->group_by('kp.nim')
            ->order_by('kp.nim ASC')
            ->get()->result();

        return $query;
    }

    public function detail($nim){
        return $this->db->select('*')
            ->from('konsultasi_perwalian as kp')
            ->join('status_perkuliahan as sp','kp.kode_tahun_akademik=sp.kode_tahun_akademik')
            ->where_not_in('semester','K')
            ->where('kp.nim', $nim)
            ->where('sp.nim', $nim)
            ->get()->result();
    }
	public function detail_manipulasi($nim){
        $data = $this->db->select('*')
            ->from('konsultasi_perwalian as kp')
            ->join('status_perkuliahan as sp','kp.kode_tahun_akademik=sp.kode_tahun_akademik')
            ->where_not_in('semester','K')
            ->where('kp.nim', $nim)
            ->where('sp.nim', $nim)
            ->get()->result();
        $nomor = 0;
        foreach ($data as $value) {
            if ($value->date_created) {
                $datafix[$nomor]->isi_konsultasi = $this->konsul($value->nim* 3+$nomor)['isi_konsultasi'];
                $datafix[$nomor]->tanggapan =  $this->konsul($value->nim* 3+$nomor)['tanggapan'];
                $datafix[$nomor]->date_created = $value->date_created;
                $datafix[$nomor]->semester = $value->semester;
                $nomor++;
                $datafix[$nomor]->isi_konsultasi = $this->konsul($value->nim* 3 + 4+$nomor)['isi_konsultasi'] ;
                $datafix[$nomor]->tanggapan = $this->konsul($value->nim* 3 + 4+$nomor)['tanggapan'];
                $datafix[$nomor]->date_created = date('Y-m-d', strtotime($value->date_created . ' +7 days'));
                $datafix[$nomor]->semester = $value->semester;
                $nomor++;
                $datafix[$nomor]->isi_konsultasi = $this->konsul($value->nim + 20 + $nomor)['isi_konsultasi'];
                $datafix[$nomor]->tanggapan = $this->konsul($value->nim + 20 + $nomor)['tanggapan'];
                $datafix[$nomor]->date_created = date('Y-m-d', strtotime($value->date_created . ' +37 days'));
                $datafix[$nomor]->semester = $value->semester;
                $nomor++;
                if (($value->nim + $value->semester) % 5 == 3) {
                    $datafix[$nomor]->isi_konsultasi = $this->konsul($value->nim + 20 + $nomor)['isi_konsultasi'];
                    $datafix[$nomor]->tanggapan = $this->konsul($value->nim + 20 + $nomor)['tanggapan'];
                    $datafix[$nomor]->date_created = date('Y-m-d', strtotime($value->date_created . ' +37 days'));
                    $datafix[$nomor]->semester = $value->semester;
                    $nomor++;
                }
                $tmp = $this->db->select('kpd.*,sp.semester')
                        ->from('konsultasi_perwalian as kp')
                        ->join('konsultasi_perwalian_detail as kpd', 'kp.kode_konsultasi_perwalian = kpd.kode_konsultasi_perwalian')
                        ->join('status_perkuliahan as sp','kp.kode_tahun_akademik=sp.kode_tahun_akademik')
                        ->where('kp.nim', $nim)
                        ->where('kp.kode_tahun_akademik', $value->kode_tahun_akademik)
                        ->get()->result();
                if (count($tmp) > 0) {
                    $datafix[$nomor] = $tmp;
                    $nomor++;
                }
            }else{
                $tmp = $this->db->select('kpd.*,sp.semester')
                        ->from('konsultasi_perwalian as kp')
                        ->join('konsultasi_perwalian_detail as kpd', 'kp.kode_konsultasi_perwalian = kpd.kode_konsultasi_perwalian')
                        ->join('status_perkuliahan as sp','kp.kode_tahun_akademik=sp.kode_tahun_akademik and sp.nim ='.$nim)
                        ->where('kp.nim', $nim)
                        ->where('kp.kode_tahun_akademik', $value->kode_tahun_akademik)
                        ->get()->result_object();
                if (count($tmp) > 0) {
                    foreach ($tmp as $val) {
                        $datafix[$nomor]->isi_konsultasi = $val->isi_konsultasi;
                        $datafix[$nomor]->tanggapan = $val->tanggapan;
                        $datafix[$nomor]->date_created = date('Y-m-d', strtotime($val->date_created));
                        $datafix[$nomor]->semester = $val->semester;
                        $nomor++;
                    }
                }
            }   
        }
        return $datafix;
    }
    public function konsul($nim){
        $konsultasi = array(
            array("isi_konsultasi" => "Bagaimana cara efektif belajar untuk ujian?", "tanggapan" => "Cobalah teknik pomodoro dan ringkasan materi."),
            array("isi_konsultasi" => "Apakah ada sumber belajar tambahan untuk mata kuliah ini?", "tanggapan" => "Saya sarankan buku referensi dan video tutorial."),
            array("isi_konsultasi" => "Saya ingin bimbingan untuk tugas kelompok.", "tanggapan" => "Buatlah pembagian tugas yang jelas di antara anggota."),
            array("isi_konsultasi" => "Bagaimana cara memilih tema skripsi?", "tanggapan" => "Pilih tema yang sesuai minat dan relevan dengan bidang studi."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika tidak paham kuliah?", "tanggapan" => "Jangan ragu untuk bertanya di kelas atau berkonsultasi setelah kuliah."),
            array("isi_konsultasi" => "Saya mengalami kesulitan dalam menulis makalah.", "tanggapan" => "Perhatikan struktur dan gunakan referensi yang valid."),
            array("isi_konsultasi" => "Bagaimana cara mempersiapkan presentasi yang baik?", "tanggapan" => "Latihan berbicara di depan cermin atau teman."),
            array("isi_konsultasi" => "Saya merasa beban kuliah terlalu berat.", "tanggapan" => "Diskusikan dengan dosen tentang kemungkinan penyesuaian beban."),
            array("isi_konsultasi" => "Bagaimana tips supaya ipk saya tetep 4,00 ?", "tanggapan" => "belajarnya lebih giat dan aktif di dalam kelas"),
            array("isi_konsultasi" => "Apakah ada seminar yang bisa saya ikuti?", "tanggapan" => "Ikuti pengumuman di website fakultas."),
            array("isi_konsultasi" => "Saya kesulitan dalam menggunakan software yang diajarkan.", "tanggapan" => "Ikuti workshop atau tutorial online."),
            array("isi_konsultasi" => "Bagaimana cara berkontribusi dalam organisasi kampus?", "tanggapan" => "Bergabunglah dengan organisasi yang sesuai minat."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika nilai saya turun?", "tanggapan" => "Evaluasi metode belajar dan cari solusi bersama dosen."),
            array("isi_konsultasi" => "Saya butuh saran untuk pengembangan diri.", "tanggapan" => "Ikuti pelatihan atau kursus tambahan."),
            array("isi_konsultasi" => "Bagaimana cara membangun jaringan profesional?", "tanggapan" => "Hadiri acara dan seminar di bidang kamu."),
            array("isi_konsultasi" => "Saya ingin bertanya tentang beasiswa.", "tanggapan" => "Cek syarat dan informasi di website kampus."),
            array("isi_konsultasi" => "Apa tips untuk mengatasi stres kuliah?", "tanggapan" => "Luangkan waktu untuk hobi dan istirahat yang cukup."),
            array("isi_konsultasi" => "Bagaimana cara menjaga motivasi belajar?", "tanggapan" => "Tetapkan tujuan jangka pendek dan jangka panjang."),
            array("isi_konsultasi" => "Saya butuh bimbingan untuk pemrograman.", "tanggapan" => "Ikuti kelas tambahan atau kelompok belajar."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika tidak lulus mata kuliah?", "tanggapan" => "Diskusikan rencana remedial dengan dosen."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang penelitian dosen.", "tanggapan" => "Baca publikasi terbaru dan ajukan pertanyaan."),
            array("isi_konsultasi" => "Bagaimana cara efektif menggunakan perpustakaan?", "tanggapan" => "Manfaatkan katalog online dan layanan referensi."),
            array("isi_konsultasi" => "Saya butuh bantuan dalam membuat CV.", "tanggapan" => "Ikuti workshop penulisan CV di pusat karier."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan saat ada konflik dengan teman sekelas?", "tanggapan" => "Diskusikan secara terbuka untuk menemukan solusi."),
            array("isi_konsultasi" => "Bagaimana cara mempersiapkan ujian akhir semester?", "tanggapan" => "Buat rencana belajar dan review materi secara teratur."),
            array("isi_konsultasi" => "Saya ingin mengikuti lomba akademik, apa saran Anda?", "tanggapan" => "Cari tim dan pilih lomba yang sesuai minat."),
            array("isi_konsultasi" => "Bagaimana cara meningkatkan kemampuan analisis?", "tanggapan" => "Latihan dengan studi kasus dan diskusi kelompok."),
            array("isi_konsultasi" => "Saya ingin tahu tentang kemungkinan studi lanjut.", "tanggapan" => "Diskusikan minat dan bakat mahasiswa."),
              array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak cocok dengan jurusan?", "tanggapan" => "Pertimbangkan untuk berkonsultasi tentang opsi pindah jurusan."),
              array("isi_konsultasi" => "Saya ingin tahu tentang pengalaman kerja di luar negeri.", "tanggapan" => "Cari informasi tentang program pertukaran pelajar."),
              array("isi_konsultasi" => "Saya butuh tips untuk presentasi di depan umum.", "tanggapan" => "Latihan, fokus pada kontak mata, dan percaya diri."),
              array("isi_konsultasi" => "Apa yang harus saya lakukan saat mengalami kebuntuan ide?", "tanggapan" => "Cobalah brainstorming atau diskusi dengan teman."),
              array("isi_konsultasi" => "Bagaimana cara memilih jurusan yang tepat?", "tanggapan" => "Pertimbangkan minat dan potensi karier di masa depan."),
              array("isi_konsultasi" => "Saya butuh tips untuk belajar efektif di rumah.", "tanggapan" => "Ciptakan lingkungan belajar yang nyaman dan minim gangguan."),
              array("isi_konsultasi" => "Apa yang harus dilakukan untuk mengatasi rasa malas?", "tanggapan" => "Tetapkan rutinitas dan motivasi diri."),
              array("isi_konsultasi" => "Saya ingin tahu lebih tentang prospek kerja di bidang saya.", "tanggapan" => "Baca artikel dan wawancarai profesional di bidang tersebut."),
              array("isi_konsultasi" => "Bagaimana cara menjaga keseimbangan antara kuliah dan aktivitas lain?", "tanggapan" => "Jadwalkan waktu khusus untuk setiap kegiatan."),
              array("isi_konsultasi" => "Saya merasa tidak percaya diri saat berbicara di depan kelas.", "tanggapan" => "Latihan dan persiapkan materi dengan baik."),
              array("isi_konsultasi" => "Apa langkah-langkah untuk menjadi anggota organisasi kampus?", "tanggapan" => "Daftar dan aktif berpartisipasi dalam kegiatan."),
              array("isi_konsultasi" => "Bagaimana cara menemukan mentor di bidang studi saya?", "tanggapan" => "Jalin komunikasi dengan dosen dan profesional."),
              array("isi_konsultasi" => "Saya ingin tahu tentang peluang magang di dalam negeri.", "tanggapan" => "Cek informasi magang di website kampus dan perusahaan."),
            array("isi_konsultasi" => "Bagaimana cara mengatasi kebosanan saat belajar?", "tanggapan" => "Coba variasikan metode belajar dan ambil istirahat secara teratur."),
            array("isi_konsultasi" => "Saya ingin belajar public speaking, bagaimana cara mulai?", "tanggapan" => "Ikuti kelas atau kursus online dan praktikkan di depan teman."),
            array("isi_konsultasi" => "Apa yang harus dilakukan jika tugas kelompok tidak merata?", "tanggapan" => "Diskusikan dan atur ulang pembagian tugas secara adil."),
            array("isi_konsultasi" => "Bagaimana cara menjaga fokus saat belajar?", "tanggapan" => "Temukan lingkungan yang tenang dan bebas gangguan."),
            array("isi_konsultasi" => "Saya butuh saran untuk membuat CV yang menarik.", "tanggapan" => "Gunakan format yang jelas dan sertakan pengalaman relevan."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak percaya diri saat presentasi?", "tanggapan" => "Latihan di depan teman dan persiapkan materi dengan baik."),
            array("isi_konsultasi" => "Bagaimana cara mengatasi rasa malas belajar?", "tanggapan" => "Tetapkan rutinitas belajar dan buat daftar tugas harian."),
            array("isi_konsultasi" => "Saya ingin tahu cara yang efektif untuk mencari informasi penelitian.", "tanggapan" => "Gunakan database akademik dan jurnal ilmiah."),
            array("isi_konsultasi" => "Apa yang harus dilakukan jika saya merasa tertekan dengan banyaknya tugas?", "tanggapan" => "Diskusikan dengan dosen dan buat rencana penyelesaian."),
            array("isi_konsultasi" => "Bagaimana cara meningkatkan kemampuan analisis?", "tanggapan" => "Latihan dengan studi kasus dan diskusi kelompok."),
            array("isi_konsultasi" => "Apa tips untuk menulis skripsi yang baik?", "tanggapan" => "Tentukan topik yang Anda minati dan buat rencana penelitian."),
            array("isi_konsultasi" => "Saya merasa kesulitan mengatur waktu antara kuliah dan kerja, bagaimana solusinya?", "tanggapan" => "Buat jadwal yang seimbang dan prioritas tugas."),
            array("isi_konsultasi" => "Apa yang harus dilakukan jika ada perubahan di rencana studi saya?", "tanggapan" => "Diskusikan dengan dosen pembimbing untuk opsi terbaik."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang seminar internasional, bisa saran?", "tanggapan" => "Cari informasi di situs akademik dan ajukan pertanyaan."),
            array("isi_konsultasi" => "Bagaimana cara beradaptasi dengan sistem pendidikan yang berbeda?", "tanggapan" => "Pelajari budaya akademik dan jalin komunikasi dengan teman."),
            array("isi_konsultasi" => "Saya ingin memperdalam topik yang diajarkan di kelas, bagaimana caranya?", "tanggapan" => "Baca buku tambahan dan cari sumber online."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tertekan karena nilai rendah?", "tanggapan" => "Diskusikan dengan dosen dan cari cara untuk perbaikan."),
            array("isi_konsultasi" => "Bagaimana cara menemukan penelitian yang relevan dengan minat saya?", "tanggapan" => "Telusuri jurnal dan sumber online di bidang yang diminati."),
            array("isi_konsultasi" => "Apa yang harus dilakukan jika ada kesulitan dalam memahami proyek kelompok?", "tanggapan" => "Berdiskusilah dengan anggota kelompok untuk mencari solusi."),
            array("isi_konsultasi" => "Saya ingin mengetahui lebih banyak tentang pengembangan keterampilan lunak, bisa saran?", "tanggapan" => "Ikuti pelatihan dan seminar di kampus."),
            array("isi_konsultasi" => "Bagaimana cara memperbaiki catatan kuliah yang tidak rapi?", "tanggapan" => "Tulis ulang dengan menggunakan sistem yang terorganisir."),
            array("isi_konsultasi" => "Apa tips untuk tetap termotivasi selama masa ujian?", "tanggapan" => "Tetapkan tujuan harian dan berikan reward untuk pencapaian."),
            array("isi_konsultasi" => "Saya ingin belajar lebih banyak tentang manajemen waktu, bisa saran?", "tanggapan" => "Gunakan aplikasi atau teknik manajemen waktu yang efektif."),
            array("isi_konsultasi" => "Bagaimana cara membangun hubungan yang baik dengan dosen?", "tanggapan" => "Terlibat aktif di kelas dan tunjukkan minat pada materi."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak nyaman dengan teman sekelas?", "tanggapan" => "Cobalah untuk membicarakan masalah secara terbuka dan dengan bijak."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang etika penelitian, bisa saran?", "tanggapan" => "Pelajari panduan etika dari institusi dan literatur terkait."),
            array("isi_konsultasi" => "Bagaimana cara memperbaiki kemampuan negosiasi?", "tanggapan" => "Latihan melalui simulasi dan diskusi kelompok."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika tidak paham instruksi tugas?", "tanggapan" => "Segera tanyakan kepada dosen atau teman untuk klarifikasi."),
            array("isi_konsultasi" => "Bagaimana cara mengembangkan pemikiran kritis?", "tanggapan" => "Baca artikel yang berbeda dan analisis argumen yang ada."),
            array("isi_konsultasi" => "Saya ingin mempelajari lebih banyak tentang teknologi baru, bisa saran?", "tanggapan" => "Ikuti webinar atau baca artikel terbaru di bidang teknologi."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa kehilangan motivasi untuk kuliah?", "tanggapan" => "Coba renungkan kembali tujuan dan alasan Anda berkuliah."),
            array("isi_konsultasi" => "Bagaimana cara menjaga keseimbangan hidup selama kuliah?", "tanggapan" => "Jadwalkan waktu untuk diri sendiri dan aktivitas yang Anda nikmati."),
            array("isi_konsultasi" => "Saya ingin mempelajari lebih banyak tentang dunia kerja, bisa saran?", "tanggapan" => "Ikuti program magang dan seminar karir."),
            array("isi_konsultasi" => "Apa langkah yang tepat untuk mempersiapkan seminar?", "tanggapan" => "Buat presentasi yang jelas dan latihan di depan audiens kecil."),
            array("isi_konsultasi" => "Saya ingin belajar lebih banyak tentang pemasaran digital, bagaimana caranya?", "tanggapan" => "Ikuti kursus online dan praktik langsung."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika saya merasa tersisih dari kelompok?", "tanggapan" => "Cobalah untuk terlibat lebih aktif dan komunikasikan perasaan Anda."),
            array("isi_konsultasi" => "Bagaimana cara memperbaiki keterampilan analisis data?", "tanggapan" => "Latihan dengan menggunakan software analisis data."),
            array("isi_konsultasi" => "Saya ingin mencari peluang beasiswa, bisa saran?", "tanggapan" => "Cek situs resmi kampus dan organisasi yang menawarkan beasiswa."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak cocok dengan mata kuliah yang diambil?", "tanggapan" => "Diskusikan dengan dosen dan pertimbangkan opsi untuk mengubah mata kuliah."),
            array("isi_konsultasi" => "Bagaimana cara membuat catatan kuliah yang efektif?", "tanggapan" => "Gunakan metode seperti Cornell atau diagram untuk mengorganisir informasi."),
            array("isi_konsultasi" => "Apa tips untuk membangun jaringan profesional di kampus?", "tanggapan" => "Ikuti acara networking dan aktif di organisasi."),
            array("isi_konsultasi" => "Saya ingin tahu cara mendapatkan pengalaman kerja di bidang saya sebelum lulus.", "tanggapan" => "Coba cari peluang magang atau proyek sukarela."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika ingin mengubah fokus studi?", "tanggapan" => "Diskusikan rencana dengan dosen pembimbing dan lihat opsi yang ada."),
            array("isi_konsultasi" => "Bagaimana cara meningkatkan keterampilan kolaborasi dalam kelompok?", "tanggapan" => "Bekerjasama dalam proyek dan saling memberi umpan balik."),
            array("isi_konsultasi" => "Saya ingin mengetahui lebih banyak tentang keamanan siber, bisa saran?", "tanggapan" => "Ikuti kursus atau baca buku dan artikel di bidang keamanan siber."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa kesulitan untuk beradaptasi dengan kehidupan kampus?", "tanggapan" => "Bergabunglah dengan organisasi atau komunitas di kampus."),
            array("isi_konsultasi" => "Bagaimana cara menjaga kesehatan mental selama kuliah?", "tanggapan" => "Luangkan waktu untuk relaksasi dan lakukan aktivitas yang Anda nikmati."),
            array("isi_konsultasi" => "Apa langkah pertama untuk membuat proyek penelitian?", "tanggapan" => "Tentukan pertanyaan penelitian dan lakukan tinjauan literatur."),
            array("isi_konsultasi" => "Bagaimana cara menulis laporan penelitian yang baik?", "tanggapan" => "Ikuti format yang ditetapkan dan buat struktur yang jelas."),
            array("isi_konsultasi" => "Saya butuh saran untuk mengatasi prokrastinasi, bagaimana caranya?", "tanggapan" => "Tetapkan deadline dan bagi tugas menjadi bagian yang lebih kecil."),
            array("isi_konsultasi" => "Bagaimana cara memperbaiki hubungan dengan teman yang tidak akur?", "tanggapan" => "Cobalah berbicara secara terbuka dan jujur mengenai perasaan Anda."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika tidak tahu cara memulai skripsi?", "tanggapan" => "Mulailah dengan brainstorming ide dan buat rencana awal."),
            array("isi_konsultasi" => "Saya ingin belajar lebih banyak tentang kepemimpinan, bisa saran?", "tanggapan" => "Ikuti kursus atau seminar tentang kepemimpinan."),
            array("isi_konsultasi" => "Bagaimana cara menemukan mentor dalam bidang akademik?", "tanggapan" => "Jalin hubungan baik dengan dosen yang Anda kagumi."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak berdaya menghadapi tekanan akademik?", "tanggapan" => "Diskusikan dengan konselor dan cari dukungan dari teman."),
            array("isi_konsultasi" => "Bagaimana cara menjaga keseimbangan antara kuliah dan aktivitas lain?", "tanggapan" => "Buat prioritas dan jadwal untuk setiap kegiatan."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang bisnis sosial, bisa saran?", "tanggapan" => "Baca buku dan artikel yang membahas bisnis sosial."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika tidak ada dukungan dari teman sekelas?", "tanggapan" => "Cobalah untuk mencari teman baru di kegiatan kampus."),
            array("isi_konsultasi" => "Bagaimana cara menemukan sumber daya untuk penelitian?", "tanggapan" => "Gunakan perpustakaan dan database akademik yang tersedia."),
            array("isi_konsultasi" => "Apa langkah-langkah untuk menjadi pembicara publik yang baik?", "tanggapan" => "Latihan dan terlibat dalam kegiatan yang membutuhkan presentasi."),
            array("isi_konsultasi" => "Saya ingin mencari cara untuk meningkatkan keterampilan teknis, bisa saran?", "tanggapan" => "Ikuti kursus online atau workshop yang relevan."),
            array("isi_konsultasi" => "Bagaimana cara berkontribusi dalam kegiatan sosial di kampus?", "tanggapan" => "Ikuti organisasi yang bergerak di bidang sosial dan aktif berpartisipasi."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa terasing di lingkungan kampus?", "tanggapan" => "Cobalah bergabung dengan klub atau komunitas yang sesuai dengan minat Anda."),
            array("isi_konsultasi" => "Bagaimana cara mempersiapkan diri untuk presentasi di depan dosen?", "tanggapan" => "Latihan di depan teman dan pastikan materi jelas dan terstruktur."),
            array("isi_konsultasi" => "Saya ingin tahu cara efektif untuk mencari pekerjaan paruh waktu, bisa saran?", "tanggapan" => "Cek website pekerjaan dan tawarkan diri untuk freelance."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak paham dengan kurikulum?", "tanggapan" => "Bertanya pada dosen dan mencari materi tambahan."),
            array("isi_konsultasi" => "Bagaimana cara mendapatkan pengalaman intern yang baik?", "tanggapan" => "Cari perusahaan yang memiliki program internship yang baik."),
            array("isi_konsultasi" => "Apa tips untuk mempersiapkan diri untuk ujian akhir?", "tanggapan" => "Buat rencana belajar dan tinjau materi secara berkala."),
            array("isi_konsultasi" => "Saya ingin mencari mentor di bidang karier saya, bisa saran?", "tanggapan" => "Jalin komunikasi dengan alumni atau profesional di bidang tersebut."),
            array("isi_konsultasi" => "Bagaimana cara menulis artikel akademik yang baik?", "tanggapan" => "Ikuti format yang tepat dan tinjau kembali sebelum mengirim."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak nyaman dalam kelompok belajar?", "tanggapan" => "Bicarakan dengan anggota kelompok dan cari solusi."),
            array("isi_konsultasi" => "Saya ingin belajar lebih banyak tentang startup, bisa saran?", "tanggapan" => "Ikuti seminar atau baca buku tentang kewirausahaan."),
            array("isi_konsultasi" => "Bagaimana cara membuat keputusan yang baik mengenai jurusan?", "tanggapan" => "Pertimbangkan minat dan peluang karier di masa depan."),
            array("isi_konsultasi" => "Apa langkah pertama untuk membangun portofolio yang baik?", "tanggapan" => "Kumpulkan hasil kerja yang relevan dan presentasikan dengan baik."),
            array("isi_konsultasi" => "Saya ingin mengetahui lebih banyak tentang penelitian ilmiah, bisa saran?", "tanggapan" => "Baca jurnal ilmiah dan ikuti seminar tentang penelitian."),
            array("isi_konsultasi" => "Bagaimana cara menjalin hubungan baik dengan rekan kerja?", "tanggapan" => "Bersikap terbuka dan komunikatif dalam bekerja sama."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak cocok dengan dosen pengampu?", "tanggapan" => "Coba cari waktu untuk berdiskusi dan sampaikan pendapat dengan sopan."),
            array("isi_konsultasi" => "Bagaimana cara menjaga semangat belajar?", "tanggapan" => "Tetapkan tujuan jangka pendek dan rayakan pencapaian kecil."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang manajemen proyek, bisa saran?", "tanggapan" => "Ikuti kursus dan praktik langsung dalam proyek."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa kehilangan arah dalam studi?", "tanggapan" => "Diskusikan dengan dosen pembimbing untuk mendapatkan arahan."),
            array("isi_konsultasi" => "Bagaimana cara meningkatkan kemampuan presentasi visual?", "tanggapan" => "Pelajari desain slide yang baik dan latih cara menyampaikannya."),
            array("isi_konsultasi" => "Saya ingin belajar tentang psikologi, bisa saran?", "tanggapan" => "Baca buku dan ikuti kursus online di bidang psikologi."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika teman sekelompok tidak berkontribusi?", "tanggapan" => "Bicarakan masalah ini dengan anggota kelompok secara langsung."),
            array("isi_konsultasi" => "Bagaimana cara menemukan topik penelitian yang relevan?", "tanggapan" => "Teliti tren terbaru dan temukan gap dalam penelitian yang ada."),
            array("isi_konsultasi" => "Apa langkah pertama untuk mempersiapkan presentasi yang sukses?", "tanggapan" => "Rencanakan dengan baik dan latih beberapa kali sebelum hari H."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang teknologi informasi, bisa saran?", "tanggapan" => "Ikuti kursus online dan praktik langsung."),
            array("isi_konsultasi" => "Bagaimana cara menulis proposal penelitian yang baik?", "tanggapan" => "Pastikan proposal jelas dan mengikuti panduan yang ditetapkan."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika saya merasa tidak termotivasi?", "tanggapan" => "Cobalah untuk menemukan inspirasi dan tetapkan tujuan baru."),
            array("isi_konsultasi" => "Bagaimana cara mengatasi rasa takut gagal?", "tanggapan" => "Latihan berpikir positif dan fokus pada proses, bukan hasil."),
            array("isi_konsultasi" => "Saya ingin belajar tentang kewirausahaan sosial, bisa saran?", "tanggapan" => "Baca literatur dan cari tahu tentang contoh sukses di bidang ini."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak terampil dalam suatu mata kuliah?", "tanggapan" => "Cari bantuan dari dosen atau teman yang menguasai materi tersebut."),
            array("isi_konsultasi" => "Bagaimana cara menjaga kesehatan selama kuliah?", "tanggapan" => "Perhatikan pola makan, cukup tidur, dan olahraga secara teratur."),
            array("isi_konsultasi" => "Saya ingin mencari cara untuk berkontribusi dalam riset dosen, bisa saran?", "tanggapan" => "Tanyakan langsung kepada dosen yang Anda minati dan tunjukkan ketertarikan."),
            array("isi_konsultasi" => "Apa langkah pertama untuk merencanakan proyek kelompok?", "tanggapan" => "Diskusikan tujuan proyek dan bagi tugas di antara anggota."),
            array("isi_konsultasi" => "Bagaimana cara mengelola stres saat ujian?", "tanggapan" => "Luangkan waktu untuk relaksasi dan jangan ragu untuk meminta bantuan."),
            array("isi_konsultasi" => "Saya ingin belajar lebih banyak tentang analisis data, bisa saran?", "tanggapan" => "Ikuti kursus online dan praktik langsung dengan dataset."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika saya merasa terasing di kampus?", "tanggapan" => "Bergabunglah dengan klub atau kegiatan yang sesuai dengan minat Anda."),
            array("isi_konsultasi" => "Bagaimana cara meningkatkan kemampuan komunikasi?", "tanggapan" => "Latihan berbicara di depan umum dan bergabung dengan kelompok debat."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang teknik pemrograman, bisa saran?", "tanggapan" => "Ikuti kursus dan proyek yang relevan dengan pemrograman."),
            array("isi_konsultasi" => "Apa langkah-langkah untuk mengembangkan keterampilan kepemimpinan?", "tanggapan" => "Ambil peran aktif dalam organisasi dan cari peluang untuk memimpin."),
            array("isi_konsultasi" => "Saya ingin mempersiapkan diri untuk karir di bidang kreatif, bisa saran?", "tanggapan" => "Kumpulkan portofolio dan ikuti kursus yang relevan."),
            array("isi_konsultasi" => "Bagaimana cara menemukan program pertukaran pelajar?", "tanggapan" => "Cek informasi di kampus dan situs penyelenggara."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak ada kemajuan dalam studi?", "tanggapan" => "Tanya dosen pembimbing untuk saran dan evaluasi rencana belajar."),
            array("isi_konsultasi" => "Bagaimana cara membangun kepercayaan diri dalam presentasi?", "tanggapan" => "Latihan dan terima umpan balik dari teman."),
            array("isi_konsultasi" => "Saya ingin belajar lebih banyak tentang lingkungan hidup, bisa saran?", "tanggapan" => "Ikuti seminar dan baca literatur tentang isu-isu lingkungan."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa tidak nyaman dengan tugas yang diberikan?", "tanggapan" => "Diskusikan dengan dosen dan cari pemahaman lebih lanjut."),
            array("isi_konsultasi" => "Bagaimana cara memanfaatkan teknologi dalam belajar?", "tanggapan" => "Gunakan aplikasi belajar dan sumber online yang relevan."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang penelitian sosial, bisa saran?", "tanggapan" => "Baca jurnal sosial dan ikuti seminar di bidang tersebut."),
            array("isi_konsultasi" => "Apa langkah pertama untuk menjadi sukarelawan?", "tanggapan" => "Cari organisasi yang sesuai dengan minat Anda dan hubungi mereka."),
            array("isi_konsultasi" => "Bagaimana cara menjaga motivasi saat mengerjakan tugas akhir?", "tanggapan" => "Tetapkan deadline dan buat jadwal yang teratur."),
            array("isi_konsultasi" => "Saya ingin belajar lebih banyak tentang pemasaran, bisa saran?", "tanggapan" => "Ikuti kursus dan praktikkan dengan proyek nyata."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa terjebak dalam rutinitas belajar?", "tanggapan" => "Coba variasikan tempat dan metode belajar."),
            array("isi_konsultasi" => "Bagaimana cara menemukan peluang karier di bidang yang saya minati?", "tanggapan" => "Jelajahi jaringan profesional dan ikuti acara karier."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang teknologi baru di bidang saya, bisa saran?", "tanggapan" => "Ikuti konferensi dan baca publikasi terbaru."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika saya merasa tidak nyaman dengan lingkungan belajar?", "tanggapan" => "Cari tempat belajar yang lebih sesuai dengan kebutuhan Anda."),
            array("isi_konsultasi" => "Bagaimana cara membuat rencana studi yang efektif?", "tanggapan" => "Tetapkan tujuan dan prioritaskan mata kuliah yang diambil."),
            array("isi_konsultasi" => "Saya ingin belajar tentang inovasi, bisa saran?", "tanggapan" => "Baca literatur dan ikuti workshop tentang inovasi."),
            array("isi_konsultasi" => "Apa langkah pertama untuk menyiapkan pameran karya?", "tanggapan" => "Tentukan tema dan buat rencana kerja untuk persiapan."),
            array("isi_konsultasi" => "Bagaimana cara menjaga hubungan baik dengan sesama mahasiswa?", "tanggapan" => "Bersikap terbuka dan hormati pendapat orang lain."),
            array("isi_konsultasi" => "Saya ingin tahu lebih banyak tentang pengembangan diri, bisa saran?", "tanggapan" => "Baca buku dan ikuti seminar tentang pengembangan diri."),
            array("isi_konsultasi" => "Apa yang harus saya lakukan jika merasa terbebani dengan tugas kuliah?", "tanggapan" => "Buat daftar tugas dan prioritaskan mana yang paling mendesak."),
        );
        $result = abs($nim % count($konsultasi));
        return  $konsultasi[$result];
    }
}