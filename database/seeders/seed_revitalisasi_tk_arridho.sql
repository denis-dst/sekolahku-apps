-- =====================================================================
-- SCRIPT SQL: KOSONGKAN & INSERT TRANSAKSI PEMBUKUAN REVITALISASI
-- SATUAN PENDIDIKAN : TK AR-RIDHO MANDAH
-- PERIODE           : S.D. 26 OKTOBER 2026 (MAKSIMAL 150 JUTAAN)
-- TOTAL PENGELUARAN : Rp 150.599.457,31
-- DATABASE          : sekolahku_apps
-- =====================================================================

-- ---------------------------------------------------------------------
-- 1. KOSONGKAN TABEL TRANSAKSI TERKAIT (RESET AUTO_INCREMENT KE 1)
-- ---------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE revitalisasi_transaksi_items;
TRUNCATE TABLE revitalisasi_transaksis;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- 2. UPDATE INFORMASI PROYEK REVITALISASI ID 1 (TK AR-RIDHO)
-- ---------------------------------------------------------------------
UPDATE revitalisasi_proyeks SET
    nama_kegiatan = 'Bantuan Revitalisasi Sarana dan Prasarana PAUD TK AR-RIDHO 2026',
    tahun_anggaran = 2026,
    pagu_anggaran = 343136609.26,
    nama_bank = 'Bank Mandiri',
    nomor_rekening = '123-00-987654-1',
    atas_nama_rekening = 'Panitia P2SP TK AR RIDHO MANDAH',
    lokasi_surat = 'Mandah',
    tanggal_tutup_buku = '2026-10-26',
    penanggung_jawab_nama = 'DIAH ANIKA FAHRANI',
    penanggung_jawab_nip = '-',
    ketua_p2sp_nama = 'Ketua Panitia P2SP',
    ketua_p2sp_nip = '-',
    bendahara_nama = 'Bendahara Panitia P2SP',
    bendahara_nip = '-',
    pengawas_nama = 'Pengawas Lapangan P2SP',
    pengawas_nip = '-',
    status = 'Aktif',
    updated_at = NOW()
WHERE id = 1;

-- ---------------------------------------------------------------------
-- 3. INSERT 32 TRANSAKSI PEMBUKUAN REVITALISASI & 69 SUB-ITEM BELANJA
-- ---------------------------------------------------------------------
-- Transaksi #01 (BKM-01) - 2026-10-01 - terima_dana
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (1, 1, 1, 1, 'BKM-01', '2026-10-01', 'terima_dana', 'Terima transfer dana bantuan Revitalisasi Tahap I (70%)', NULL, 'Direktorat PAUD Kemendikbudristek', 343136609.26, 'Penerimaan Bantuan Pemerintah Revitalisasi Sarpras PAUD Tahap I (70%)', NULL, 1, NOW(), NOW());

-- Transaksi #02 (BKK-01) - 2026-10-02 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (2, 1, 1, 1, 'BKK-01', '2026-10-02', 'belanja_tunai', 'Pembersihan lapangan & persiapan lahan kerja', 'Upah', 'Pekerja / Tukang HOK', 1800000.00, 'Belanja upah swakelola kegiatan revitalisasi', NULL, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (1, 2, 'Pembersihan lapangan & persiapan lahan kerja', 'Upah', 1.00, 'Minggu', 1800000.00, 1800000.00, 1, NOW(), NOW());

-- Transaksi #03 (BKK-02) - 2026-10-02 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (3, 1, 1, 1, 'BKK-02', '2026-10-02', 'belanja_tunai', 'Pekerjaan persiapan bedeng & patok bowplank', 'Upah', 'Pekerja / Tukang HOK', 1800000.00, 'Belanja upah swakelola kegiatan revitalisasi', NULL, 3, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (2, 3, 'Pekerjaan persiapan bedeng & patok bowplank', 'Upah', 1.00, 'Minggu', 1800000.00, 1800000.00, 1, NOW(), NOW());

-- Transaksi #04 (BKM-02) - 2026-10-03 - tarik_bank
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (4, 1, 1, 1, 'BKM-02', '2026-10-03', 'tarik_bank', 'Penarikan tunai operasional kas dari rekening bank', NULL, 'Bendahara Panitia P2SP', 150599457.31, 'Penarikan Uang Persediaan (UP) dari Rekening Bank Mandiri ke Kas Tunai Swakelola', NULL, 4, NOW(), NOW());

-- Transaksi #05 (BKK-03) - 2026-10-03 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (5, 1, 1, 1, 'BKK-03', '2026-10-03', 'belanja_tunai', 'Oprasional Pengambilan Dana ke Bank', 'Manajemen', 'Tim Pengelola Swakelola P2SP', 100000.00, 'Belanja manajemen swakelola kegiatan revitalisasi', NULL, 5, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (3, 5, 'Oprasional Pengambilan Dana ke Bank', 'Manajemen', 1.00, 'Trip/pp', 100000.00, 100000.00, 1, NOW(), NOW());

-- Transaksi #06 (BKK-04) - 2026-10-03 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (6, 1, 1, 1, 'BKK-04', '2026-10-03', 'belanja_tunai', 'Konsumsi Rapat Panitia P2SP', 'Manajemen', 'Katering Konsumsi Rapat P2SP', 150000.00, 'Belanja manajemen swakelola kegiatan revitalisasi', NULL, 6, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (4, 6, 'Konsumsi Rapat Panitia P2SP', 'Manajemen', 15.00, 'ktk', 10000.00, 150000.00, 1, NOW(), NOW());

-- Transaksi #07 (BKK-05) - 2026-10-03 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (7, 1, 1, 1, 'BKK-05', '2026-10-03', 'belanja_tunai', 'Survey Harga Material & Bahan ke Toko Rekanan', 'Manajemen', 'Tim Pengelola Swakelola P2SP', 300000.00, 'Belanja manajemen swakelola kegiatan revitalisasi', NULL, 7, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (5, 7, 'Survey Harga Material & Bahan ke Toko Rekanan', 'Manajemen', 1.00, 'trip (PP)', 300000.00, 300000.00, 1, NOW(), NOW());

-- Transaksi #08 (BKK-06) - 2026-10-03 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (8, 1, 1, 1, 'BKK-06', '2026-10-03', 'belanja_tunai', 'Pengadaan ATK & Dokumen Administrasi Proyek (Kertas, Tinta, Odner, dll)', 'Manajemen', 'Toko ATK & Percetakan Digital', 648000.00, 'Belanja manajemen swakelola kegiatan revitalisasi', NULL, 8, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (6, 8, 'Kertas HVS A4 70gr', 'Manajemen', 5.00, 'rim', 60000.00, 300000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (7, 8, 'Tinta Printer Epson Black / Color', 'Manajemen', 2.00, 'bh', 54000.00, 108000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (8, 8, 'Odner Bantex Arsip Dokumen', 'Manajemen', 1.00, 'bh', 50000.00, 50000.00, 3, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (9, 8, 'Streples kecil HD-10', 'Manajemen', 2.00, 'bh', 35000.00, 70000.00, 4, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (10, 8, 'Isi streples kecil No. 10', 'Manajemen', 2.00, 'kotak', 6000.00, 12000.00, 5, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (11, 8, 'Pena / Pulpen Gel Joyko', 'Manajemen', 4.00, 'bh', 6000.00, 24000.00, 6, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (12, 8, 'Pinsil 2B Faber Castell', 'Manajemen', 1.00, 'bh', 4000.00, 4000.00, 7, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (13, 8, 'Penggaris besi 30 cm', 'Manajemen', 1.00, 'bh', 20000.00, 20000.00, 8, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (14, 8, 'Map snail folio plastik', 'Manajemen', 10.00, 'bh', 6000.00, 60000.00, 9, NOW(), NOW());

-- Transaksi #09 (BKK-07) - 2026-10-04 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (9, 1, 1, 1, 'BKK-07', '2026-10-04', 'belanja_tunai', 'Papan Nama Kegiatan & Rambu K3 Konstruksi (Banner, Rambu Bahaya, Bendera K3)', 'Manajemen', 'Percetakan Digital & Reklame K3', 295000.00, 'Belanja manajemen swakelola kegiatan revitalisasi', NULL, 9, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (15, 9, 'Papan nama kegiatan (banner + rangka)', 'Manajemen', 1.00, 'Bh', 50000.00, 50000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (16, 9, 'Papan informasi K3 keselamatan kerja', 'Manajemen', 1.00, 'Bh', 100000.00, 100000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (17, 9, 'Rambu larangan masuk area berbahaya', 'Manajemen', 1.00, 'Bh', 50000.00, 50000.00, 3, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (18, 9, 'Rambu Peringatan bahaya proyek', 'Manajemen', 1.00, 'Bh', 50000.00, 50000.00, 4, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (19, 9, 'Bendera K3 (Keselamatan Kerja)', 'Manajemen', 1.00, 'Bh', 45000.00, 45000.00, 5, NOW(), NOW());

-- Transaksi #10 (BKK-08) - 2026-10-04 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (10, 1, 1, 1, 'BKK-08', '2026-10-04', 'belanja_tunai', 'Pengadaan Alat Pelindung Diri K3 (Helm SNI, Sarung Tangan, Sepatu Boots, Rompi)', 'Alat', 'Toko Perlengkapan APD K3', 1860000.00, 'Belanja alat swakelola kegiatan revitalisasi', NULL, 10, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (20, 10, 'Helm pelindung proyek SNI', 'Alat', 10.00, 'Bh', 50000.00, 500000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (21, 10, 'Sarung Tangan kerja rajut katun', 'Alat', 24.00, 'Bh', 15000.00, 360000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (22, 10, 'Sepatu Karet / Boots proyek', 'Alat', 10.00, 'Bh', 65000.00, 650000.00, 3, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (23, 10, 'Rompi kerja reflektif K3', 'Alat', 10.00, 'Bh', 35000.00, 350000.00, 4, NOW(), NOW());

-- Transaksi #11 (BKK-09) - 2026-10-04 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (11, 1, 1, 1, 'BKK-09', '2026-10-04', 'belanja_tunai', 'Peralatan & kotak obat P3K darurat', 'Manajemen', 'Apotek / Toko Obat P3K', 150000.00, 'Belanja manajemen swakelola kegiatan revitalisasi', NULL, 11, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (24, 11, 'Peralatan & kotak obat P3K darurat', 'Manajemen', 1.00, 'paket', 150000.00, 150000.00, 1, NOW(), NOW());

-- Transaksi #12 (BKK-10) - 2026-10-04 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (12, 1, 1, 1, 'BKK-10', '2026-10-04', 'belanja_tunai', 'Biaya Perencanaan Teknis (Termin I - 70%)', 'Manajemen', 'Konsultan Perencana Teknik', 7845558.94, 'Belanja manajemen swakelola kegiatan revitalisasi', NULL, 12, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (25, 12, 'Biaya Perencanaan Teknis (Termin I - 70%)', 'Manajemen', 0.70, 'Ls', 11207941.34, 7845558.94, 1, NOW(), NOW());

-- Transaksi #13 (BKK-11) - 2026-10-05 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (13, 1, 1, 1, 'BKK-11', '2026-10-05', 'belanja_tunai', 'Pengadaan Alat Kerja Pertukangan (Ember, Bak, Palu, Meteran, Slang, dll)', 'Alat', 'Toko Bangunan (TB Rekanan)', 1074000.00, 'Belanja alat swakelola kegiatan revitalisasi', NULL, 13, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (26, 13, 'Ember cor kecil adukan', 'Alat', 15.00, 'Bh', 20000.00, 300000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (27, 13, 'Bak adukan semen plastik', 'Alat', 4.00, 'Bh', 40000.00, 160000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (28, 13, 'Benang nilon tukang', 'Alat', 10.00, 'Bh', 7000.00, 70000.00, 3, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (29, 13, 'Palu besi martil', 'Alat', 1.00, 'Bh', 60000.00, 60000.00, 4, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (30, 13, 'Meteran gulung baja 5m', 'Alat', 3.00, 'Bh', 80000.00, 240000.00, 5, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (31, 13, 'Slang watervas kedataran 30m', 'Alat', 30.00, 'm', 4000.00, 120000.00, 6, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (32, 13, 'Mata gerinda potong keramik/besi', 'Alat', 4.00, 'Bh', 6000.00, 24000.00, 7, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (33, 13, 'Gegep kakatua catut pembesian', 'Alat', 5.00, 'Bh', 20000.00, 100000.00, 8, NOW(), NOW());

-- Transaksi #14 (BKK-12) - 2026-10-05 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (14, 1, 1, 1, 'BKK-12', '2026-10-05', 'belanja_tunai', 'Pengadaan air kerja dan listrik proyek', 'Bahan', 'Penyedia Air Kerja & Listrik Proyek', 350000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 14, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (34, 14, 'Pengadaan air kerja dan listrik proyek', 'Bahan', 1.00, 'set', 350000.00, 350000.00, 1, NOW(), NOW());

-- Transaksi #15 (BKK-13) - 2026-10-05 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (15, 1, 1, 1, 'BKK-13', '2026-10-05', 'belanja_tunai', 'Pengadaan Kayu Bowplank, Kaso & Paku Pengukuran Awal', 'Bahan', 'Toko Bangunan (TB Rekanan)', 3180000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 15, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (35, 15, 'Kayu kaso 5/7 patok bowplank', 'Bahan', 50.00, 'btg', 24000.00, 1200000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (36, 15, 'Papan kayu lokal 3/20 bowplank', 'Bahan', 60.00, 'lbr', 28000.00, 1680000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (37, 15, 'Paku campur bowplank', 'Bahan', 15.00, 'kg', 20000.00, 300000.00, 3, NOW(), NOW());

-- Transaksi #16 (BKK-14) - 2026-10-06 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (16, 1, 1, 1, 'BKK-14', '2026-10-06', 'belanja_tunai', 'Pengadaan Paku-Pakuan Konstruksi Kayu & Begisting', 'Bahan', 'Toko Bangunan (TB Rekanan)', 597000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 16, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (38, 16, 'Paku 1"', 'Bahan', 2.00, 'Kg', 21000.00, 42000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (39, 16, 'Paku 3,5"', 'Bahan', 8.00, 'Kg', 21000.00, 168000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (40, 16, 'Paku 3"', 'Bahan', 8.00, 'Kg', 21000.00, 168000.00, 3, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (41, 16, 'Paku 2,5"', 'Bahan', 6.00, 'Kg', 21000.00, 126000.00, 4, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (42, 16, 'Paku 2"', 'Bahan', 3.00, 'Kg', 21000.00, 63000.00, 5, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (43, 16, 'Paku Triplek / Asbes', 'Bahan', 1.00, 'Kg', 30000.00, 30000.00, 6, NOW(), NOW());

-- Transaksi #17 (BKK-15) - 2026-10-06 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (17, 1, 1, 1, 'BKK-15', '2026-10-06', 'belanja_tunai', 'Batu Belah hitam keras pondasi', 'Bahan', 'Toko Bangunan (TB Rekanan)', 5250000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 17, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (44, 17, 'Batu Belah hitam keras pondasi', 'Bahan', 3.00, 'Rit/5m3', 1750000.00, 5250000.00, 1, NOW(), NOW());

-- Transaksi #18 (BKK-16) - 2026-10-06 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (18, 1, 1, 1, 'BKK-16', '2026-10-06', 'belanja_tunai', 'Pengadaan Pasir Urug Bawah Pondasi, Pasir Pasang & Pasir Cor', 'Bahan', 'Toko Bangunan (TB Rekanan)', 16400000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 18, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (45, 18, 'Pasir urug padat bawah pondasi', 'Bahan', 2.00, 'Rit/5m3', 1200000.00, 2400000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (46, 18, 'Pasir Sedang kali pasang pondasi', 'Bahan', 4.00, 'Rit/5m3', 2000000.00, 8000000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (47, 18, 'Pasir Cor / Kasar beton struktur', 'Bahan', 3.00, 'Rit/5m3', 2000000.00, 6000000.00, 3, NOW(), NOW());

-- Transaksi #19 (BKK-17) - 2026-10-10 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (19, 1, 1, 1, 'BKK-17', '2026-10-10', 'belanja_tunai', 'Batu Belah hitam pondasi menerus (pengiriman tahap 2)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 9625000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 19, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (48, 19, 'Batu Belah hitam pondasi menerus (pengiriman tahap 2)', 'Bahan', 5.50, 'Rit/5m3', 1750000.00, 9625000.00, 1, NOW(), NOW());

-- Transaksi #20 (BKK-18) - 2026-10-10 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (20, 1, 1, 1, 'BKK-18', '2026-10-10', 'belanja_tunai', 'Tanah urug pilihan peninggian peil bangunan (13.5 Rit)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 2164000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 20, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (49, 20, 'Tanah urug pilihan peninggian peil bangunan (13.5 Rit)', 'Bahan', 54.10, 'm3', 40000.00, 2164000.00, 1, NOW(), NOW());

-- Transaksi #21 (BKK-19) - 2026-10-12 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (21, 1, 1, 1, 'BKK-19', '2026-10-12', 'belanja_tunai', 'Semen Portland PCC (50 kg) SNI (Semen Padang / Holcim)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 8160000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 21, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (50, 21, 'Semen Portland PCC (50 kg) SNI (Semen Padang / Holcim)', 'Bahan', 120.00, 'sak', 68000.00, 8160000.00, 1, NOW(), NOW());

-- Transaksi #22 (BKK-20) - 2026-10-14 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (22, 1, 1, 1, 'BKK-20', '2026-10-14', 'belanja_tunai', 'Pengadaan Besi Beton Ø10, Ø6 SNI & Kawat Bendrat Struktur Sloof/Kolom', 'Bahan', 'Toko Bangunan (TB Rekanan)', 21740000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 22, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (51, 22, 'Besi Beton Polos Ø10 mm SNI (12m)', 'Bahan', 190.00, 'btg', 82000.00, 15580000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (52, 22, 'Besi Beton Polos Ø6 mm SNI (12m)', 'Bahan', 160.00, 'btg', 31000.00, 4960000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (53, 22, 'Kawat Bendrat Hitam Pengikat Anyaman Besi', 'Bahan', 50.00, 'kg', 24000.00, 1200000.00, 3, NOW(), NOW());

-- Transaksi #23 (BKK-21) - 2026-10-16 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (23, 1, 1, 1, 'BKK-21', '2026-10-16', 'belanja_tunai', 'Pengadaan Papan Kayu Begisting 3/20, Kaso 5/7, Balok Dolken & Paku', 'Bahan', 'Toko Bangunan (TB Rekanan)', 4720000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 23, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (54, 23, 'Papan Kayu Begisting Lokal 3/20', 'Bahan', 60.00, 'lbr', 28000.00, 1680000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (55, 23, 'Kayu Kaso 5/7 Begisting & Pengaku', 'Bahan', 40.00, 'btg', 24000.00, 960000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (56, 23, 'Kayu Balok / Dolken Penyangga 4m', 'Bahan', 60.00, 'btg', 18000.00, 1080000.00, 3, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (57, 23, 'Paku Kayu Campur Begisting', 'Bahan', 50.00, 'kg', 20000.00, 1000000.00, 4, NOW(), NOW());

-- Transaksi #24 (BKK-22) - 2026-10-18 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (24, 1, 1, 1, 'BKK-22', '2026-10-18', 'belanja_tunai', 'Pengadaan Pasir Cor Beton Bersih & Kerikil / Batu Split 2/3 Mesin', 'Bahan', 'Toko Bangunan (TB Rekanan)', 4786000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 24, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (58, 24, 'Pasir Cor / Pasir Beton Kasar Bersih', 'Bahan', 7.20, 'm3', 260000.00, 1872000.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (59, 24, 'Batu Split / Kerikil Cor Beton 2/3 Mesin', 'Bahan', 9.40, 'm3', 310000.00, 2914000.00, 2, NOW(), NOW());

-- Transaksi #25 (BKK-23) - 2026-10-20 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (25, 1, 1, 1, 'BKK-23', '2026-10-20', 'belanja_tunai', 'Upah borongan tukang Termin I (Selesai pondasi & cor sloof 100%)', 'Upah', 'Mandor & Pekerja Borongan', 13892713.37, 'Belanja upah swakelola kegiatan revitalisasi', NULL, 25, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (60, 25, 'Upah borongan tukang Termin I (Selesai pondasi & cor sloof 100%)', 'Upah', 0.15, 'Porsi', 92618089.18, 13892713.37, 1, NOW(), NOW());

-- Transaksi #26 (BKK-24) - 2026-10-22 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (26, 1, 1, 1, 'BKK-24', '2026-10-22', 'belanja_tunai', 'Bata Merah Standar Oven / Bakar (Pengiriman 1)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 9000000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 26, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (61, 26, 'Bata Merah Standar Oven / Bakar (Pengiriman 1)', 'Bahan', 12000.00, 'buah', 750.00, 9000000.00, 1, NOW(), NOW());

-- Transaksi #27 (BKK-25) - 2026-10-25 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (27, 1, 1, 1, 'BKK-25', '2026-10-25', 'belanja_tunai', 'Semen Portland PCC (50 kg) SNI (Pengiriman 2)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 3400000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 27, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (62, 27, 'Semen Portland PCC (50 kg) SNI (Pengiriman 2)', 'Bahan', 50.00, 'sak', 68000.00, 3400000.00, 1, NOW(), NOW());

-- Transaksi #28 (BKK-26) - 2026-10-25 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (28, 1, 1, 1, 'BKK-26', '2026-10-25', 'belanja_tunai', 'Pasir Pasang Kali Bebas Lumpur (Pengiriman 2)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 6440000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 28, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (63, 28, 'Pasir Pasang Kali Bebas Lumpur (Pengiriman 2)', 'Bahan', 28.00, 'm3', 230000.00, 6440000.00, 1, NOW(), NOW());

-- Transaksi #29 (BKK-27) - 2026-10-26 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (29, 1, 1, 1, 'BKK-27', '2026-10-26', 'belanja_tunai', 'Bata Merah Standar Oven / Bakar (Pengiriman 2 - Pelunasan)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 6000000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 29, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (64, 29, 'Bata Merah Standar Oven / Bakar (Pengiriman 2 - Pelunasan)', 'Bahan', 8000.00, 'buah', 750.00, 6000000.00, 1, NOW(), NOW());

-- Transaksi #30 (BKK-28) - 2026-10-26 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (30, 1, 1, 1, 'BKK-28', '2026-10-26', 'belanja_tunai', 'Pengadaan Kusen Kayu Pintu, Jendela & Bovenlight Kelas II + Jalusi', 'Bahan', 'Toko Bangunan (TB Rekanan)', 9492185.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 30, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (65, 30, 'Kusen Kayu Kelas II + Jalusi Pintu P1', 'Bahan', 5.00, 'unit', 597861.00, 2989305.00, 1, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (66, 30, 'Kusen Kayu Kelas II + Jalusi Jendela (J2L/J3L)', 'Bahan', 6.00, 'unit', 940000.00, 5640000.00, 2, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (67, 30, 'Kusen Kayu Bovenlight BV3L Bilik Toilet', 'Bahan', 1.00, 'unit', 862880.00, 862880.00, 3, NOW(), NOW());

-- Transaksi #31 (BKK-29) - 2026-10-26 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (31, 1, 1, 1, 'BKK-29', '2026-10-26', 'belanja_tunai', 'Semen Portland PCC (50 kg) SNI (Pengiriman 3)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 3400000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 31, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (68, 31, 'Semen Portland PCC (50 kg) SNI (Pengiriman 3)', 'Bahan', 50.00, 'sak', 68000.00, 3400000.00, 1, NOW(), NOW());

-- Transaksi #32 (BKK-30) - 2026-10-26 - belanja_tunai
INSERT INTO revitalisasi_transaksis (id, school_id, revitalisasi_proyek_id, user_id, nomor_bukti, tanggal, jenis_transaksi, uraian, jenis_biaya, penerima_vendor, nominal, keterangan, file_bukti, urutan, created_at, updated_at) VALUES (32, 1, 1, 1, 'BKK-30', '2026-10-26', 'belanja_tunai', 'Pasir Pasang Kali Ayak Halus (Pelunasan sisa volume)', 'Bahan', 'Toko Bangunan (TB Rekanan)', 5980000.00, 'Belanja bahan swakelola kegiatan revitalisasi', NULL, 32, NOW(), NOW());
INSERT INTO revitalisasi_transaksi_items (id, revitalisasi_transaksi_id, uraian_item, jenis_biaya, volume, satuan, harga_satuan, subtotal, urutan, created_at, updated_at) VALUES (69, 32, 'Pasir Pasang Kali Ayak Halus (Pelunasan sisa volume)', 'Bahan', 26.00, 'm3', 230000.00, 5980000.00, 1, NOW(), NOW());
