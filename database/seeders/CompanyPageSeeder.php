<?php

namespace Database\Seeders;

use App\Models\CompanyPage;
use Illuminate\Database\Seeder;

class CompanyPageSeeder extends Seeder
{
    public function run(): void
    {
        CompanyPage::firstOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang Kami — DnD Tech Solutions',
                'content' => "SekolahKu-Apps adalah platform SaaS manajemen sekolah all-in-one yang dikembangkan oleh DnD Tech Solutions. Misi kami adalah menghadirkan solusi teknologi digital yang terjangkau, efisien, dan modern untuk mendukung kemajuan pendidikan di seluruh Indonesia.\n\nDengan integrasi E-Rapor Kurikulum Merdeka, Presensi Digital, Pembayaran SPP QRIS, BendaharaKu LPJ BOSP, dan WhatsApp Gateway otomatis, SekolahKu-Apps membantu ratusan sekolah mengotomatiskan alur kerja administrasi harian secara praktis dan hemat waktu.",
                'meta_description' => 'Profil dan tentang SekolahKu-Apps oleh DnD Tech Solutions — Platform manajemen sekolah cerdas Indonesia.',
                'is_active' => true,
            ]
        );

        CompanyPage::firstOrCreate(
            ['slug' => 'hubungi-kami'],
            [
                'title' => 'Hubungi Kami',
                'content' => 'Punya pertanyaan tentang SekolahKu-Apps atau butuh bantuan konsultasi implementasi di sekolah Anda? Tim DnD Tech Solutions siap membantu Anda kapan saja.',
                'meta_description' => 'Kontak resmi SekolahKu-Apps dan DnD Tech Solutions. Layanan bantuan via WhatsApp, Email, dan Lokasi Kantor.',
                'contact_email' => 'support@dndtech.id',
                'contact_phone' => '6289669651907',
                'contact_address' => 'Jl. Pendidikan Digital No. 88, Surabaya, Jawa Timur, Indonesia',
                'contact_maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d112.6426425!3d-7.2754438!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf8381ac6b5%3A0x3027a76e352be40!2sSurabaya%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="350" style="border:0; border-radius:1rem;" allowfullscreen="" loading="lazy"></iframe>',
                'is_active' => true,
            ]
        );
    }
}
