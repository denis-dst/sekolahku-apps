<?php

namespace App\Http\Controllers\Ingetin;

use App\Http\Controllers\Controller;
use App\Models\Ingetin\IngetinSetting;
use App\Models\Ingetin\IngetinDzikir;
use App\Models\Ingetin\IngetinHadith;
use App\Models\Ingetin\IngetinDonationStat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IngetinApiController extends Controller
{
    private function jsonSuccess(array $data, int $status = 200): JsonResponse
    {
        return response()->json($data, $status, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        ]);
    }

    /**
     * GET /ingetin/api/settings & /ingetin/api/settings.php
     */
    public function settings(Request $request): JsonResponse
    {
        $stats = IngetinDonationStat::first() ?? new IngetinDonationStat([
            'total_donors' => 2847,
            'total_amount_text' => '47.2jt',
            'total_programs' => 12,
            'note' => 'Seluruh donasi disalurkan 100% tanpa potongan biaya administrasi.'
        ]);

        $rawQris = IngetinSetting::getVal('qris_image_url', 'uploads/ingetin/qris_default.png');
        if (str_starts_with($rawQris, 'http://') || str_starts_with($rawQris, 'https://')) {
            $fullQrisUrl = $rawQris;
        } else {
            $fullQrisUrl = url(ltrim($rawQris, '/'));
        }

        return $this->jsonSuccess([
            'status' => 'success',
            'data' => [
                'app_name' => IngetinSetting::getVal('app_name', 'Ingetin'),
                'app_version' => IngetinSetting::getVal('app_version', '1.0.0'),
                'merchant' => [
                    'name' => IngetinSetting::getVal('merchant_name', 'Ingetin Sedekah Peduli'),
                    'city' => IngetinSetting::getVal('merchant_city', 'Jakarta Selatan'),
                    'qris_image_url' => $fullQrisUrl,
                    'donation_info' => IngetinSetting::getVal('donation_info', 'Sedekah tidak akan mengurangi harta. Semoga menjadi amal jariyah.')
                ],
                'donation_stats' => [
                    'total_donors' => (int)$stats->total_donors,
                    'total_amount_text' => (string)$stats->total_amount_text,
                    'total_programs' => (int)$stats->total_programs,
                    'note' => (string)$stats->note,
                ],
                'default_times' => [
                    'dzikir_pagi' => '06:00',
                    'dhuha_start' => IngetinSetting::getVal('dhuha_start_time', '07:00'),
                    'dhuha_end' => IngetinSetting::getVal('dhuha_end_time', '11:00'),
                    'dzikir_petang' => '17:00',
                    'witir' => '21:30',
                    'tidur' => '22:00'
                ]
            ]
        ]);
    }

    /**
     * GET /ingetin/api/dzikir & /ingetin/api/dzikir.php
     */
    public function dzikir(Request $request): JsonResponse
    {
        $category = strtolower(trim($request->query('category', 'all')));

        $query = IngetinDzikir::where('is_active', 1);

        if ($category !== 'all' && in_array($category, ['pagi', 'petang', 'tidur', 'lainnya'])) {
            $query->where('category', $category)->orderBy('order_index', 'asc')->orderBy('id', 'asc');
        } else {
            $query->orderBy('category', 'asc')->orderBy('order_index', 'asc')->orderBy('id', 'asc');
        }

        $items = $query->get()->map(function ($row) {
            return [
                'id' => (int)$row->id,
                'category' => $row->category,
                'title' => $row->title,
                'arabic' => $row->arabic,
                'latin' => $row->latin,
                'translation' => $row->translation,
                'target_count' => (int)$row->target_count,
                'benefit' => (string)($row->benefit ?? ''),
                'source' => (string)($row->source ?? ''),
                'order_index' => (int)$row->order_index,
            ];
        });

        return $this->jsonSuccess([
            'status' => 'success',
            'total' => $items->count(),
            'category' => $category,
            'data' => $items,
        ]);
    }

    /**
     * GET /ingetin/api/hadith & /ingetin/api/hadith.php
     */
    public function hadith(Request $request): JsonResponse
    {
        $dayOfYear = (int)date('z');
        $hadiths = IngetinHadith::where('is_active', 1)->orderBy('id', 'asc')->get();

        if ($hadiths->isEmpty()) {
            return $this->jsonSuccess([
                'status' => 'success',
                'data' => [
                    'id' => 1,
                    'text' => 'Sesungguhnya amalan yang paling dicintai oleh Allah adalah amalan yang rutin dikerjakan meskipun sedikit.',
                    'narrator' => 'HR. Bukhari & Muslim',
                    'topic' => 'Konsistensi Ibadah'
                ],
                'all' => []
            ]);
        }

        $index = $dayOfYear % $hadiths->count();
        $todayHadith = $hadiths[$index];

        return $this->jsonSuccess([
            'status' => 'success',
            'day_of_year' => $dayOfYear,
            'data' => [
                'id' => (int)$todayHadith->id,
                'text' => $todayHadith->text,
                'narrator' => $todayHadith->narrator,
                'topic' => $todayHadith->topic,
            ],
            'all' => $hadiths->map(function ($h) {
                return [
                    'id' => (int)$h->id,
                    'text' => $h->text,
                    'narrator' => $h->narrator,
                    'topic' => $h->topic,
                ];
            }),
        ]);
    }

    /**
     * GET /ingetin/api/prayers & /ingetin/api/prayers.php
     * Astronomical prayer calculation (Kemenag standard)
     */
    public function prayers(Request $request): JsonResponse
    {
        $lat = (float)$request->query('lat', -6.2088);
        $lng = (float)$request->query('lng', 106.8456);
        $timezone = (float)$request->query('tz', 7.0);
        $dateStr = $request->query('date', date('Y-m-d'));

        $time = strtotime($dateStr) ?: time();
        $year = (int)date('Y', $time);
        $month = (int)date('m', $time);
        $day = (int)date('d', $time);

        // Julian Day Calculation
        if ($month <= 2) {
            $year -= 1;
            $month += 12;
        }
        $a = floor($year / 100);
        $b = 2 - $a + floor($a / 4);
        $jd = floor(365.25 * ($year + 4716)) + floor(30.6001 * ($month + 1)) + $day + $b - 1524.5;
        $d = $jd - 2451545.0;

        // Mean anomaly of the Sun
        $g = deg2rad(fmod(357.529 + 0.98560028 * $d, 360));
        // Mean longitude of the Sun
        $q = deg2rad(fmod(280.459 + 0.98564736 * $d, 360));
        // Geocentric apparent ecliptic longitude
        $L = deg2rad(fmod(rad2deg($q) + 1.915 * sin($g) + 0.020 * sin(2 * $g), 360));
        // Mean obliquity of the ecliptic
        $e = deg2rad(23.439 - 0.00000036 * $d);
        // Sun Declination
        $decl = asin(sin($e) * sin($L));
        // Right Ascension
        $RA = atan2(cos($e) * sin($L), cos($L));
        $RA = fmod($RA + 2 * M_PI, 2 * M_PI);

        // Equation of Time in minutes
        $qHours = rad2deg($q) / 15.0;
        $raHours = rad2deg($RA) / 15.0;
        $eqt = ($qHours - $raHours) * 60.0;
        while ($eqt > 20) $eqt -= 1440;
        while ($eqt < -20) $eqt += 1440;

        // Solar Transit (Dzuhur)
        $midday = 12.0 + $timezone - ($lng / 15.0) - ($eqt / 60.0);

        $getHourAngle = function ($altitudeDeg, $latDeg, $declRad) {
            $latRad = deg2rad($latDeg);
            $altRad = deg2rad($altitudeDeg);
            $cosH = (sin($altRad) - sin($latRad) * sin($declRad)) / (cos($latRad) * cos($declRad));
            if ($cosH > 1.0) return 0.0;
            if ($cosH < -1.0) return 180.0;
            return rad2deg(acos($cosH)) / 15.0;
        };

        $haSubuh = $getHourAngle(-20.0, $lat, $decl);
        $haSunrise = $getHourAngle(-0.833, $lat, $decl);
        $haIsya = $getHourAngle(-18.0, $lat, $decl);

        // Ashar
        $phi = deg2rad($lat);
        $delta = $decl;
        $noonAlt = M_PI / 2.0 - abs($phi - $delta);
        $noonShadow = tan(M_PI / 2.0 - $noonAlt);
        $asrAltRad = atan(1.0 / (1.0 + $noonShadow));
        $haAshar = $getHourAngle(rad2deg($asrAltRad), $lat, $decl);

        $formatTime = function ($hours, $ihtiyatMinutes = 2) {
            $totalMinutes = round($hours * 60) + $ihtiyatMinutes;
            $h = floor($totalMinutes / 60) % 24;
            $m = $totalMinutes % 60;
            if ($h < 0) $h += 24;
            return sprintf('%02d:%02d', $h, $m);
        };

        $subuhHour = $midday - $haSubuh;
        $sunriseHour = $midday - $haSunrise;
        $dhuhaStartHour = $sunriseHour + (20 / 60.0);
        $dzuhurHour = $midday;
        $asharHour = $midday + $haAshar;
        $maghribHour = $midday + $haSunrise;
        $isyaHour = $midday + $haIsya;

        $prayers = [
            'subuh' => $formatTime($subuhHour, 2),
            'terbit' => $formatTime($sunriseHour, -2),
            'dhuha' => $formatTime($dhuhaStartHour, 0),
            'dzuhur' => $formatTime($dzuhurHour, 2),
            'ashar' => $formatTime($asharHour, 2),
            'maghrib' => $formatTime($maghribHour, 2),
            'isya' => $formatTime($isyaHour, 2),
        ];

        $nowMinutes = (int)date('H') * 60 + (int)date('i');
        $prayerMinutes = [];
        foreach ($prayers as $name => $formatted) {
            [$hh, $mm] = explode(':', $formatted);
            $prayerMinutes[$name] = (int)$hh * 60 + (int)$mm;
        }

        $nextPrayer = 'subuh';
        $nextTime = $prayers['subuh'];
        foreach ($prayerMinutes as $name => $mins) {
            if ($mins > $nowMinutes) {
                $nextPrayer = $name;
                $nextTime = $prayers[$name];
                break;
            }
        }

        return $this->jsonSuccess([
            'status' => 'success',
            'location' => [
                'latitude' => $lat,
                'longitude' => $lng,
                'timezone' => $timezone
            ],
            'date' => $dateStr,
            'schedule' => $prayers,
            'next' => [
                'name' => ucfirst($nextPrayer),
                'time' => $nextTime
            ]
        ]);
    }
}
