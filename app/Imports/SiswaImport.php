<?php

namespace App\Imports;

use App\Models\Rombel;
use App\Models\School;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SiswaImport implements ToCollection, WithHeadingRow
{
    protected School $school;
    protected ?int $fallbackRombelId;
    public int $importedCount = 0;
    public int $skippedCount = 0;
    public array $errors = [];

    public function __construct(School $school, ?int $fallbackRombelId = null)
    {
        $this->school = $school;
        $this->fallbackRombelId = $fallbackRombelId;
    }

    public function collection(Collection $rows)
    {
        $rombels = Rombel::where('school_id', $this->school->id)->get();

        DB::transaction(function () use ($rows, $rombels) {
            foreach ($rows as $index => $row) {
                // Find column by various possible key formats
                $namaLengkap = $this->getValue($row, ['nama_lengkap', 'nama_lengkap_wajib', 'nama']);
                if (empty($namaLengkap)) {
                    $this->skippedCount++;
                    continue;
                }

                // Check quota limit if applicable
                if (!$this->school->canAddSiswa()) {
                    $this->errors[] = "Batas kuota siswa untuk paket langganan sekolah telah tercapai saat memproses baris ke-" . ($index + 2);
                    break;
                }

                $nisn = $this->getValue($row, ['nisn']);
                $nik = $this->getValue($row, ['nik']);
                $namaPanggilan = $this->getValue($row, ['nama_panggilan', 'panggilan']);
                $rawJk = strtoupper(trim((string) $this->getValue($row, ['jenis_kelamin_lp', 'jenis_kelamin', 'jk', 'gender'])));
                $jenisKelamin = str_starts_with($rawJk, 'L') ? 'L' : (str_starts_with($rawJk, 'P') ? 'P' : 'L');

                $namaRombel = $this->getValue($row, ['nama_rombel_kelas', 'nama_rombel', 'rombel', 'kelas']);
                $rombelId = $this->fallbackRombelId;

                if (!empty($namaRombel)) {
                    $matchedRombel = $rombels->first(function ($r) use ($namaRombel) {
                        return strcasecmp(trim($r->nama_rombel), trim((string) $namaRombel)) === 0;
                    });
                    if ($matchedRombel) {
                        $rombelId = $matchedRombel->id;
                    }
                }

                $tempatLahir = $this->getValue($row, ['tempat_lahir']);
                $rawTanggalLahir = $this->getValue($row, ['tanggal_lahir_yyyy_mm_dd', 'tanggal_lahir', 'tgl_lahir']);
                $tanggalLahir = $this->parseDate($rawTanggalLahir);

                $namaOrtu = $this->getValue($row, ['nama_orang_tua_wali', 'nama_ortu', 'orang_tua', 'wali']);
                $noHpOrtu = $this->getValue($row, ['no_hp_wa_orang_tua', 'no_hp_ortu', 'no_hp', 'telepon', 'wa']);
                $alamat = $this->getValue($row, ['alamat']);

                Siswa::create([
                    'school_id' => $this->school->id,
                    'rombel_id' => $rombelId,
                    'nama_lengkap' => $namaLengkap,
                    'nama_panggilan' => $namaPanggilan,
                    'nisn' => $nisn,
                    'nik' => $nik,
                    'jenis_kelamin' => $jenisKelamin,
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    'nama_ortu' => $namaOrtu,
                    'no_hp_ortu' => $noHpOrtu,
                    'alamat' => $alamat,
                    'status' => 'Aktif',
                ]);

                $this->importedCount++;
            }
        });
    }

    protected function getValue($row, array $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && trim((string) $row[$key]) !== '') {
                return trim((string) $row[$key]);
            }
        }
        return null;
    }

    protected function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Check if it's an Excel numeric timestamp
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
