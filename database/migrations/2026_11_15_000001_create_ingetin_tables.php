<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Settings Table
        if (!Schema::hasTable('ingetin_settings')) {
            Schema::create('ingetin_settings', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->text('value')->nullable();
            });

            // Seed default settings
            $defaultSettings = [
                ['key' => 'app_name', 'value' => 'Ingetin'],
                ['key' => 'app_version', 'value' => '1.0.0'],
                ['key' => 'qris_image_url', 'value' => 'uploads/qris_default.png'],
                ['key' => 'merchant_name', 'value' => 'Ingetin Sedekah Peduli'],
                ['key' => 'merchant_city', 'value' => 'Jakarta Selatan'],
                ['key' => 'donation_info', 'value' => 'Sedekah tidak akan mengurangi harta. Semoga menjadi amal jariyah.'],
                ['key' => 'dhuha_start_time', 'value' => '07:00'],
                ['key' => 'dhuha_end_time', 'value' => '11:00'],
                ['key' => 'admin_pin', 'value' => '123456'],
            ];
            DB::table('ingetin_settings')->insert($defaultSettings);
        }

        // 2. Donation Stats Table
        if (!Schema::hasTable('ingetin_donation_stats')) {
            Schema::create('ingetin_donation_stats', function (Blueprint $table) {
                $table->id();
                $table->integer('total_donors')->default(2847);
                $table->string('total_amount_text')->default('47.2jt');
                $table->integer('total_programs')->default(12);
                $table->text('note')->nullable();
                $table->timestamps();
            });

            DB::table('ingetin_donation_stats')->insert([
                'id' => 1,
                'total_donors' => 2847,
                'total_amount_text' => '47.2jt',
                'total_programs' => 12,
                'note' => 'Seluruh donasi disalurkan 100% tanpa potongan biaya administrasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Dzikir Table
        if (!Schema::hasTable('ingetin_dzikirs')) {
            Schema::create('ingetin_dzikirs', function (Blueprint $table) {
                $table->id();
                $table->string('category')->index(); // pagi, petang, tidur, lainnya
                $table->string('title');
                $table->text('arabic');
                $table->text('latin')->nullable();
                $table->text('translation')->nullable();
                $table->integer('target_count')->default(1);
                $table->text('benefit')->nullable();
                $table->string('source')->nullable();
                $table->integer('order_index')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            $dzikirs = [
                [
                    'category' => 'pagi',
                    'title' => 'Membaca Ayat Kursi',
                    'arabic' => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ ۚ لَا تَأْخُذُهُ سِنَةٌ وَلَا نَوْمٌ ۚ لَّهُ مَا فِي السَّمَاوَاتِ وَمَا فِي الْأَرْضِ ۗ مَن ذَا الَّذِي يَشْفَعُ عِندَهُ إِلَّا بِإِذْنِهِ ۚ يَعْلَمُ مَا بَيْنَ أَيْدِيهِمْ وَمَا خَلْفَهُمْ ۖ وَلَا يُحِيطُونَ بِشَيْءٍ مِّنْ عِلْمِهِ إِلَّا بِمَا شَاءَ ۚ وَسِعَ كُرْسِيُّهُ السَّمَاوَاتِ وَالْأَرْضَ ۖ وَلَا يَئُودُهُ حِفْظُهُمَا ۚ وَهُوَ الْعَلِيُّ الْعَظِيمُ',
                    'latin' => 'Allahu laa ilaaha illaa huwal hayyul qayyuum, laa ta\'khudzuhuu sinatuw wa laa nawm, lahuu maa fis samaawaati wa maa fil ardh, man dzalladzii yasyfa\'u \'indahuu illaa bi idznih, ya\'lamu maa bayna aydiihim wa maa khalfahum, wa laa yuhiithuuna bi syay-im min \'ilmihii illaa bi maa syaa\', wasi\'a kursiyyuhus samaawaati wal ardh, wa laa ya-uuduhuu hifzhuhumaa, wa huwal \'aliyyul \'azhiim.',
                    'translation' => 'Allah, tidak ada tuhan selain Dia. Yang Mahahidup, Yang terus-menerus mengurus makhluk-Nya, tidak mengantuk dan tidak tidur. Milik-Nya apa yang ada di langit dan apa yang ada di bumi. Tidak ada yang dapat memberi syafaat di sisi-Nya tanpa izin-Nya. Dia mengetahui apa yang di hadapan mereka dan apa yang di belakang mereka, dan mereka tidak mengetahui sesuatu apa pun tentang ilmu-Nya melainkan apa yang Dia kehendaki. Kursi-Nya meliputi langit dan bumi. Dan Dia tidak merasa berat memelihara keduanya, dan Dia Mahatinggi, Mahabesar.',
                    'target_count' => 1,
                    'benefit' => 'Barangsiapa membacanya di pagi hari, ia akan dilindungi dari gangguan jin hingga petang.',
                    'source' => 'HR. An-Nasa\'i & Al-Hakim',
                    'order_index' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'pagi',
                    'title' => 'Doa Berpagi Hari (Ashbahna)',
                    'arabic' => 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَهَ إِلَّا اللهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ',
                    'latin' => 'Ashbahnaa wa ashbahal mulku lillaahi, wal hamdu lillaah, laa ilaaha illallaahu wahdahu laa syariika lah, lahul mulku wa lahul hamdu wa huwa \'alaa kulli syay-in qadiir.',
                    'translation' => 'Kami berpagi hari dan seluruh kerajaan milik Allah, segala puji bagi Allah, tiada tuhan selain Allah Yang Maha Esa tiada sekutu bagi-Nya, milik-Nya kerajaan dan bagi-Nya segala puji dan Dia Mahakuasa atas segala sesuatu.',
                    'target_count' => 1,
                    'benefit' => 'Pengakuan tauhid dan perlindungan di awal hari.',
                    'source' => 'HR. Muslim no. 2723',
                    'order_index' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'pagi',
                    'title' => 'Sayyidul Istighfar',
                    'arabic' => 'اللَّهُمَّ أَنْتَ رَبِّي لَا إِلَهَ إِلَّا أَنْتَ، خَلَقْتَنِي وَأَنَا عَبْدُكَ، وَأَنَا عَلَى عَهْدِكَ وَوَعْدِكَ مَا اسْتَطَعْتُ، أَعُوذُ بِكَ مِنْ شَرِّ مَا صَنَعْتُ، أَبُوءُ لَكَ بِنِعْمَتِكَ عَلَيَّ، وَأَبُوءُ بِذَنْبِي فَاغْفِرْ لِي فَإِنَّهُ لَا يَغْفِرُ الذُّنُوبَ إِلَّا أَنْتَ',
                    'latin' => 'Allahumma anta rabbii laa ilaaha illaa anta, khalaqtanii wa ana \'abduka, wa ana \'alaa \'ahdika wa wa\'dika mastatha\'tu, a\'uudzu bika min syarri maa shana\'tu, abuu-u laka bini\'matika \'alayya, wa abuu-u bi dzanbii faghfir lii fa innahu laa yaghfirudz dzunuuba illaa anta.',
                    'translation' => 'Ya Allah, Engkau adalah Tuhanku, tiada tuhan yang berhak disembah selain Engkau. Engkau yang menciptakan aku dan aku adalah hamba-Mu. Aku berusaha memenuhi janji-Mu semampuku. Aku berlindung kepada-Mu dari keburukan perbuatanku. Aku mengakui nikmat-Mu kepadaku dan aku mengakui dosaku, maka ampunilah aku, sesungguhnya tiada yang mengampuni dosa selain Engkau.',
                    'target_count' => 1,
                    'benefit' => 'Barangsiapa membacanya di pagi hari dengan meyakininya lalu ia meninggal di hari itu sebelum petang, ia termasuk penghuni surga.',
                    'source' => 'HR. Bukhari no. 6306',
                    'order_index' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'pagi',
                    'title' => 'Perlindungan dari Marabahaya (Bismillahilladzii)',
                    'arabic' => 'بِسْمِ اللَّهِ الَّذِي لَا يَضُرُّ مَعَ اسْمِهِ شَيْءٌ فِي الْأَرْضِ وَلَا فِي السَّمَاءِ وَهُوَ السَّمِيعُ الْعَلِيمُ',
                    'latin' => 'Bismillaahilladzii laa yadhurru ma\'asmihii syay-un fil ardhi wa laa fis samaa-i wa huwas samii\'ul \'aliim.',
                    'translation' => 'Dengan nama Allah yang dengan nama-Nya tidak ada sesuatu pun yang dapat mencelakakan di bumi maupun di langit, dan Dia Maha Mendengar lagi Maha Mengetahui.',
                    'target_count' => 3,
                    'benefit' => 'Tidak akan terkena bahaya/mudharat hingga petang hari.',
                    'source' => 'HR. Abu Dawud & At-Tirmidzi',
                    'order_index' => 4,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'pagi',
                    'title' => 'Keridhaan kepada Allah, Islam, & Rasulullah',
                    'arabic' => 'رَضِيتُ بِاللَّهِ رَبًّا، وَبِالْإِسْلَامِ دِينًا، وَبِمُحَمَّدٍ صَلَّى اللَّهُ عَلَيْهِ وَسَلَّمَ نَبِيًّا',
                    'latin' => 'Radhiitu billaahi rabbaa, wa bil islaami diinaa, wa bi Muhammadin shallallaahu \'alayhi wa sallama nabiyyaa.',
                    'translation' => 'Aku rela Allah sebagai Tuhanku, Islam sebagai agamaku, dan Nabi Muhammad shallallahu \'alaihi wa sallam sebagai Nabiku.',
                    'target_count' => 3,
                    'benefit' => 'Allah berhak membuatnya ridha pada hari kiamat.',
                    'source' => 'HR. Abu Dawud & At-Tirmidzi',
                    'order_index' => 5,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'petang',
                    'title' => 'Doa Berpetang Hari (Amsayna)',
                    'arabic' => 'أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَهَ إِلَّا اللهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ',
                    'latin' => 'Amsaynaa wa amsal mulku lillaahi, wal hamdu lillaah, laa ilaaha illallaahu wahdahu laa syariika lah, lahul mulku wa lahul hamdu wa huwa \'alaa kulli syay-in qadiir.',
                    'translation' => 'Kami memasuki waktu petang dan kerajaan ini milik Allah, segala puji bagi Allah. Tiada tuhan selain Allah semata, tidak ada sekutu bagi-Nya. Milik-Nya kerajaan dan bagi-Nya segala puji dan Dia Mahakuasa atas segala sesuatu.',
                    'target_count' => 1,
                    'benefit' => 'Dzikir pembuka perlindungan petang dan malam hari.',
                    'source' => 'HR. Muslim no. 2723',
                    'order_index' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'petang',
                    'title' => 'Perlindungan dari Kalimat Allah yang Sempurna',
                    'arabic' => 'أَعُوذُ بِكَلِمَاتِ اللَّهِ التَّامَّاتِ مِنْ شَرِّ مَا خَلَقَ',
                    'latin' => 'A\'uudzu bikalimaatillaahit taammaati min syarri maa khalaq.',
                    'translation' => 'Aku berlindung dengan kalimat-kalimat Allah yang sempurna dari kejahatan makhluk yang Dia ciptakan.',
                    'target_count' => 3,
                    'benefit' => 'Tidak akan terkena gigitan berbisa atau marabahaya malam itu.',
                    'source' => 'HR. Muslim no. 2709',
                    'order_index' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'petang',
                    'title' => 'Sayyidul Istighfar (Petang)',
                    'arabic' => 'اللَّهُمَّ أَنْتَ رَبِّي لَا إِلَهَ إِلَّا أَنْتَ، خَلَقْتَنِي وَأَنَا عَبْدُكَ، وَأَنَا عَلَى عَهْدِكَ وَوَعْدِكَ مَا اسْتَطَعْتُ، أَعُوذُ بِكَ مِنْ شَرِّ مَا صَنَعْتُ، أَبُوءُ لَكَ بِنِعْمَتِكَ عَلَيَّ، وَأَبُوءُ بِذَنْبِي فَاغْفِرْ لِي فَإِنَّهُ لَا يَغْفِرُ الذُّنُوبَ إِلَّا أَنْتَ',
                    'latin' => 'Allahumma anta rabbii laa ilaaha illaa anta, khalaqtanii wa ana \'abduka, wa ana \'alaa \'ahdika wa wa\'dika mastatha\'tu, a\'uudzu bika min syarri maa shana\'tu, abuu-u laka bini\'matika \'alayya, wa abuu-u bi dzanbii faghfir lii fa innahu laa yaghfirudz dzunuuba illaa anta.',
                    'translation' => 'Ya Allah, Engkau adalah Tuhanku, tiada tuhan selain Engkau...',
                    'target_count' => 1,
                    'benefit' => 'Barangsiapa membacanya di petang hari lalu meninggal di malam itu, ia termasuk penghuni surga.',
                    'source' => 'HR. Bukhari no. 6306',
                    'order_index' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'tidur',
                    'title' => 'Doa Sebelum Tidur (Bismikallahumma)',
                    'arabic' => 'بِاسْمِكَ اللَّهُمَّ أَحْيَا وَأَمُوتُ',
                    'latin' => 'Bismika Allahumma ahyaa wa amuutu.',
                    'translation' => 'Dengan nama-Mu ya Allah, aku hidup dan aku mati.',
                    'target_count' => 1,
                    'benefit' => 'Sunnah Rasulullah shallallahu \'alaihi wa sallam setiap hendak tidur.',
                    'source' => 'HR. Bukhari & Muslim',
                    'order_index' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'tidur',
                    'title' => 'Ayat Kursi Sebelum Tidur',
                    'arabic' => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ...',
                    'latin' => 'Allahu laa ilaaha illaa huwal hayyul qayyuum...',
                    'translation' => 'Allah, tidak ada tuhan selain Dia. Yang Mahahidup, Yang terus-menerus mengurus makhluk-Nya...',
                    'target_count' => 1,
                    'benefit' => 'Penjagaan malaikat dari Allah dan tidak didekati setan sampai subuh.',
                    'source' => 'HR. Bukhari no. 2311',
                    'order_index' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'tidur',
                    'title' => 'Membaca 2 Ayat Terakhir Surat Al-Baqarah',
                    'arabic' => 'آمَنَ الرَّسُولُ بِمَا أُنْزِلَ إِلَيْهِ مِنْ رَبِّهِ وَالْمُؤْمِنُونَ ۚ كُلٌّ آمَنَ بِاللَّهِ وَمَلَائِكَتِهِ وَكُتُبِهِ وَرُسُلِهِ...',
                    'latin' => 'Aamanar rasuulu bimaa unzila ilayhi mir rabbihii wal mu\'minuun...',
                    'translation' => 'Rasul (Muhammad) telah beriman kepada apa yang diturunkan kepadanya dari Tuhannya, demikian pula orang-orang yang beriman...',
                    'target_count' => 1,
                    'benefit' => 'Barangsiapa membaca dua ayat terakhir Al-Baqarah pada malam hari, itu sudah mencukupinya.',
                    'source' => 'HR. Bukhari & Muslim',
                    'order_index' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category' => 'tidur',
                    'title' => 'Meniup Kedua Telapak Tangan & Membaca 3 Qul (Al-Ikhlas, Al-Falaq, An-Nas)',
                    'arabic' => 'قُلْ هُوَ اللَّهُ أَحَدٌ ... قُلْ أَعُوذُ بِرَبِّ الْفَلَقِ ... قُلْ أَعُوذُ بِرَبِّ النَّاسِ ...',
                    'latin' => 'Qul huwallahu ahad... Qul a\'uudzu bi rabbil falaq... Qul a\'uudzu bi rabbin naas...',
                    'translation' => 'Katakanlah: Dialah Allah Yang Maha Esa... lalu usapkan ke seluruh tubuh yang terjangkau.',
                    'target_count' => 3,
                    'benefit' => 'Sunnah Nabi shallallahu \'alaihi wa sallam mengusapkan ke kepala, wajah, dan depan tubuh sebanyak 3x.',
                    'source' => 'HR. Bukhari & Muslim',
                    'order_index' => 4,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            DB::table('ingetin_dzikirs')->insert($dzikirs);
        }

        // 4. Hadith Table
        if (!Schema::hasTable('ingetin_hadiths')) {
            Schema::create('ingetin_hadiths', function (Blueprint $table) {
                $table->id();
                $table->text('text');
                $table->string('narrator');
                $table->string('topic')->nullable();
                $table->integer('day_of_year')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            $hadiths = [
                [
                    'text' => 'Sesungguhnya amalan yang paling dicintai oleh Allah adalah amalan yang rutin dikerjakan meskipun sedikit.',
                    'narrator' => 'HR. Bukhari no. 6464 & Muslim no. 783',
                    'topic' => 'Konsistensi Ibadah',
                    'day_of_year' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'text' => 'Sedekah itu tidak akan mengurangi harta, dan tidaklah Allah menambah bagi seorang hamba karena sifat memaafkannya kecuali kemuliaan.',
                    'narrator' => 'HR. Muslim no. 2588',
                    'topic' => 'Sedekah',
                    'day_of_year' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'text' => 'Pada setiap persendian kalian ada sedekahnya setiap pagi... dan semua itu cukup digantikan dengan dua rakaat shalat Dhuha.',
                    'narrator' => 'HR. Muslim no. 720',
                    'topic' => 'Keutamaan Dhuha',
                    'day_of_year' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'text' => 'Jadikanlah akhir sholat malam kalian adalah sholat Witir.',
                    'narrator' => 'HR. Bukhari no. 998 & Muslim no. 751',
                    'topic' => 'Sholat Witir',
                    'day_of_year' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'text' => 'Perumpamaan orang yang berdzikir kepada Rabbnya dengan orang yang tidak berdzikir adalah seperti orang yang hidup dengan orang yang mati.',
                    'narrator' => 'HR. Bukhari no. 6407',
                    'topic' => 'Keutamaan Dzikir',
                    'day_of_year' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            DB::table('ingetin_hadiths')->insert($hadiths);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingetin_settings');
        Schema::dropIfExists('ingetin_donation_stats');
        Schema::dropIfExists('ingetin_dzikirs');
        Schema::dropIfExists('ingetin_hadiths');
    }
};
