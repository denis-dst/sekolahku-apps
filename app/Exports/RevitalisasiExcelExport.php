<?php

namespace App\Exports;

use App\Models\RevitalisasiProyek;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RevitalisasiExcelExport
{
    protected RevitalisasiProyek $proyek;

    public function __construct(RevitalisasiProyek $proyek)
    {
        $this->proyek = $proyek;
    }

    public function download(string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Simulasi_Pembukuan_Revitalisasi_' . ($this->proyek->tahun_anggaran ?? date('Y')) . '.xlsx';

        $spreadsheet = new Spreadsheet();
        
        // Build 3 sheets: BKU, BPKT, BB
        $this->buildBkuSheet($spreadsheet);
        $this->buildBpktSheet($spreadsheet);
        $this->buildBbSheet($spreadsheet);

        // Set active sheet to BKU
        $spreadsheet->setActiveSheetIndex(0);

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * Sheet 1: BKU (Buku Kas Umum - Format 2 Sisi Penerimaan vs Pengeluaran)
     */
    protected function buildBkuSheet(Spreadsheet $spreadsheet): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('BKU');

        // Header Top Row
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Penerimaan');
        $sheet->mergeCells('F1:K1');
        $sheet->setCellValue('F1', 'Pengeluaran');

        // Table Header Columns (Row 2)
        $sheet->setCellValue('A2', 'No.');
        $sheet->setCellValue('B2', 'Tanggal');
        $sheet->setCellValue('C2', 'Uraian');
        $sheet->setCellValue('D2', 'No. Bukti');
        $sheet->setCellValue('E2', 'Jumlah (Rp)');

        $sheet->setCellValue('F2', 'No.');
        $sheet->setCellValue('G2', 'Tanggal');
        $sheet->setCellValue('H2', 'Uraian');
        $sheet->setCellValue('I2', 'No. Bukti');
        $sheet->setCellValue('J2', 'Jenis Biaya');
        $sheet->setCellValue('K2', 'Jumlah (Rp)');

        $transaksis = $this->proyek->transaksis()->with('items')->get();

        $receiptRows = [];
        $expenseRows = [];

        $penerimaanNo = 1;
        $pengeluaranNo = 1;

        foreach ($transaksis as $trx) {
            if ($trx->jenis_transaksi === 'terima_dana') {
                $receiptRows[] = [
                    'no' => $penerimaanNo++,
                    'tanggal' => $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '',
                    'uraian' => $trx->uraian,
                    'no_bukti' => $trx->nomor_bukti,
                    'jumlah' => (float) $trx->nominal,
                ];
            } elseif (in_array($trx->jenis_transaksi, ['belanja_tunai', 'belanja_transfer'])) {
                if ($trx->items->isNotEmpty()) {
                    // Header transaction row
                    $expenseRows[] = [
                        'is_parent' => true,
                        'no' => $pengeluaranNo++,
                        'tanggal' => $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '',
                        'uraian' => $trx->uraian,
                        'no_bukti' => $trx->nomor_bukti,
                        'jenis_biaya' => '',
                        'jumlah' => null,
                    ];
                    // Sub-items
                    foreach ($trx->items as $item) {
                        $expenseRows[] = [
                            'is_parent' => false,
                            'no' => '',
                            'tanggal' => '',
                            'uraian' => '- ' . $item->uraian_item,
                            'no_bukti' => '',
                            'jenis_biaya' => $item->jenis_biaya ?: $trx->jenis_biaya,
                            'jumlah' => (float) $item->subtotal,
                        ];
                    }
                } else {
                    $expenseRows[] = [
                        'is_parent' => false,
                        'no' => $pengeluaranNo++,
                        'tanggal' => $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '',
                        'uraian' => $trx->uraian,
                        'no_bukti' => $trx->nomor_bukti,
                        'jenis_biaya' => $trx->jenis_biaya,
                        'jumlah' => (float) $trx->nominal,
                    ];
                }
            }
        }

        $maxCount = max(count($receiptRows), count($expenseRows));
        $currentRow = 3;

        for ($i = 0; $i < $maxCount; $i++) {
            if (isset($receiptRows[$i])) {
                $r = $receiptRows[$i];
                $sheet->setCellValue('A' . $currentRow, $r['no']);
                $sheet->setCellValue('B' . $currentRow, $r['tanggal']);
                $sheet->setCellValue('C' . $currentRow, $r['uraian']);
                $sheet->setCellValue('D' . $currentRow, $r['no_bukti']);
                $sheet->setCellValue('E' . $currentRow, $r['jumlah']);
            }

            if (isset($expenseRows[$i])) {
                $e = $expenseRows[$i];
                $sheet->setCellValue('F' . $currentRow, $e['no']);
                $sheet->setCellValue('G' . $currentRow, $e['tanggal']);
                $sheet->setCellValue('H' . $currentRow, $e['uraian']);
                $sheet->setCellValue('I' . $currentRow, $e['no_bukti']);
                $sheet->setCellValue('J' . $currentRow, $e['jenis_biaya']);
                if ($e['jumlah'] !== null) {
                    $sheet->setCellValue('K' . $currentRow, $e['jumlah']);
                }
            }

            $currentRow++;
        }

        $dataEndRow = max(3, $currentRow - 1);
        $totalRow = $currentRow;

        // TOTAL ROW
        $sheet->mergeCells('A' . $totalRow . ':D' . $totalRow);
        $sheet->setCellValue('A' . $totalRow, 'TOTAL');
        $sheet->setCellValue('E' . $totalRow, '=SUM(E3:E' . $dataEndRow . ')');

        $sheet->mergeCells('F' . $totalRow . ':J' . $totalRow);
        $sheet->setCellValue('F' . $totalRow, 'TOTAL');
        $sheet->setCellValue('K' . $totalRow, '=SUM(K3:K' . $dataEndRow . ')');

        // SALDO ROWS
        $saldoKasUmumRow = $totalRow + 1;
        $sheet->mergeCells('F' . $saldoKasUmumRow . ':J' . $saldoKasUmumRow);
        $sheet->setCellValue('F' . $saldoKasUmumRow, 'SALDO KAS UMUM');
        $sheet->setCellValue('K' . $saldoKasUmumRow, '=E' . $totalRow . '-K' . $totalRow);

        $saldoKasTunaiRow = $totalRow + 2;
        $sheet->mergeCells('F' . $saldoKasTunaiRow . ':J' . $saldoKasTunaiRow);
        $sheet->setCellValue('F' . $saldoKasTunaiRow, 'SALDO KAS TUNAI');
        $sheet->setCellValue('K' . $saldoKasTunaiRow, "='BPKT'!G" . $this->getBpktLastRowNumber());

        $saldoBankRow = $totalRow + 3;
        $sheet->mergeCells('F' . $saldoBankRow . ':J' . $saldoBankRow);
        $sheet->setCellValue('F' . $saldoBankRow, 'SALDO BANK');
        $sheet->setCellValue('K' . $saldoBankRow, "='BB'!G" . $this->getBbLastRowNumber());

        // Penutupan Kas Note
        $noteRow = $saldoBankRow + 2;
        $tglTutup = $this->proyek->tanggal_tutup_buku ? $this->proyek->tanggal_tutup_buku->translatedFormat('d F Y') : date('d F Y');
        $sheet->setCellValue('B' . $noteRow, 'Pada hari ini Tanggal ' . $tglTutup . ' Buku Kas Umum ditutup dengan keadaan/posisi Buku sebagai berikut :');
        
        $sheet->setCellValue('B' . ($noteRow + 2), 'Saldo Buku Kas Umum');
        $sheet->setCellValue('D' . ($noteRow + 2), '=K' . $saldoKasUmumRow);

        $sheet->setCellValue('B' . ($noteRow + 3), 'terdiri dari :');
        $sheet->setCellValue('B' . ($noteRow + 4), '1. Saldo Bank');
        $sheet->setCellValue('C' . ($noteRow + 4), '=K' . $saldoBankRow);

        $sheet->setCellValue('B' . ($noteRow + 5), '2. Saldo Kas Tunai');
        $sheet->setCellValue('C' . ($noteRow + 5), '=K' . $saldoKasTunaiRow);

        // Signatures Block
        $sigStart = $noteRow + 7;
        $sheet->setCellValue('B' . $sigStart, 'Mengetahui :');
        
        $sheet->setCellValue('B' . ($sigStart + 1), 'Penanggung Jawab / KS');
        $sheet->setCellValue('D' . ($sigStart + 1), 'Ketua P2SP');
        $sheet->setCellValue('G' . ($sigStart + 1), 'Bendahara');
        $sheet->setCellValue('J' . ($sigStart + 1), 'Pengawas');

        $sheet->setCellValue('B' . ($sigStart + 5), $this->proyek->penanggung_jawab_nama ?: '( ................................ )');
        $sheet->setCellValue('D' . ($sigStart + 5), $this->proyek->ketua_p2sp_nama ?: '( ................................ )');
        $sheet->setCellValue('G' . ($sigStart + 5), $this->proyek->bendahara_nama ?: '( ................................ )');
        $sheet->setCellValue('J' . ($sigStart + 5), $this->proyek->pengawas_nama ?: '( ................................ )');

        if ($this->proyek->penanggung_jawab_nip) {
            $sheet->setCellValue('B' . ($sigStart + 6), 'NIP. ' . $this->proyek->penanggung_jawab_nip);
        }
        if ($this->proyek->ketua_p2sp_nip) {
            $sheet->setCellValue('D' . ($sigStart + 6), 'NIP. ' . $this->proyek->ketua_p2sp_nip);
        }
        if ($this->proyek->bendahara_nip) {
            $sheet->setCellValue('G' . ($sigStart + 6), 'NIP. ' . $this->proyek->bendahara_nip);
        }
        if ($this->proyek->pengawas_nip) {
            $sheet->setCellValue('J' . ($sigStart + 6), 'NIP. ' . $this->proyek->pengawas_nip);
        }

        // Apply formatting & borders
        $this->formatBkuStyles($sheet, $totalRow, $saldoBankRow);
    }

    /**
     * Sheet 2: BPKT (Buku Pembantu Kas Tunai)
     */
    protected function buildBpktSheet(Spreadsheet $spreadsheet): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BPKT');

        // Table Header Columns (Row 2)
        $sheet->setCellValue('A2', 'No.');
        $sheet->setCellValue('B2', 'Tanggal');
        $sheet->setCellValue('C2', 'Uraian');
        $sheet->setCellValue('D2', 'No. Bukti');
        $sheet->setCellValue('E2', 'Penerimaan (Rp)');
        $sheet->setCellValue('F2', 'Pengeluaran (Rp)');
        $sheet->setCellValue('G2', 'Saldo (Rp)');

        $transaksis = $this->proyek->transaksis()->with('items')->get();
        $currentRow = 3;
        $noUrut = 1;

        foreach ($transaksis as $trx) {
            if ($trx->jenis_transaksi === 'tarik_bank') {
                $sheet->setCellValue('A' . $currentRow, $noUrut++);
                $sheet->setCellValue('B' . $currentRow, $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '');
                $sheet->setCellValue('C' . $currentRow, $trx->uraian ?: 'Penarikan tunai');
                $sheet->setCellValue('D' . $currentRow, $trx->nomor_bukti);
                $sheet->setCellValue('E' . $currentRow, (float) $trx->nominal);

                if ($currentRow === 3) {
                    $sheet->setCellValue('G' . $currentRow, '=E3');
                } else {
                    $prevRow = $currentRow - 1;
                    $sheet->setCellValue('G' . $currentRow, '=G' . $prevRow . '+E' . $currentRow . '-F' . $currentRow);
                }
                $currentRow++;
            } elseif ($trx->jenis_transaksi === 'belanja_tunai') {
                if ($trx->items->isNotEmpty()) {
                    // Header belanja
                    $sheet->setCellValue('A' . $currentRow, $noUrut++);
                    $sheet->setCellValue('B' . $currentRow, $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '');
                    $sheet->setCellValue('C' . $currentRow, $trx->uraian);
                    $sheet->setCellValue('D' . $currentRow, $trx->nomor_bukti);
                    $prevRow = $currentRow - 1;
                    $sheet->setCellValue('G' . $currentRow, '=G' . $prevRow);
                    $currentRow++;

                    // Sub-items
                    foreach ($trx->items as $item) {
                        $sheet->setCellValue('C' . $currentRow, '- ' . $item->uraian_item);
                        $sheet->setCellValue('F' . $currentRow, (float) $item->subtotal);
                        $prevRow = $currentRow - 1;
                        $sheet->setCellValue('G' . $currentRow, '=G' . $prevRow . '-F' . $currentRow);
                        $currentRow++;
                    }
                } else {
                    $sheet->setCellValue('A' . $currentRow, $noUrut++);
                    $sheet->setCellValue('B' . $currentRow, $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '');
                    $sheet->setCellValue('C' . $currentRow, $trx->uraian);
                    $sheet->setCellValue('D' . $currentRow, $trx->nomor_bukti);
                    $sheet->setCellValue('F' . $currentRow, (float) $trx->nominal);
                    if ($currentRow === 3) {
                        $sheet->setCellValue('G' . $currentRow, '=E3-F3');
                    } else {
                        $prevRow = $currentRow - 1;
                        $sheet->setCellValue('G' . $currentRow, '=G' . $prevRow . '+E' . $currentRow . '-F' . $currentRow);
                    }
                    $currentRow++;
                }
            }
        }

        $dataEndRow = max(3, $currentRow - 1);
        $totalRow = $currentRow;

        // TOTAL ROW
        $sheet->mergeCells('A' . $totalRow . ':D' . $totalRow);
        $sheet->setCellValue('A' . $totalRow, 'TOTAL');
        $sheet->setCellValue('E' . $totalRow, '=SUM(E3:E' . $dataEndRow . ')');
        $sheet->setCellValue('F' . $totalRow, '=SUM(F3:F' . $dataEndRow . ')');
        $sheet->setCellValue('G' . $totalRow, '=G' . $dataEndRow);

        // Signatures Block
        $sigStart = $totalRow + 3;
        $sheet->setCellValue('B' . $sigStart, 'Mengetahui :');
        
        $sheet->setCellValue('B' . ($sigStart + 1), 'Penanggung Jawab / KS');
        $sheet->setCellValue('C' . ($sigStart + 1), 'Ketua P2SP');
        $sheet->setCellValue('E' . ($sigStart + 1), 'Bendahara');
        $sheet->setCellValue('G' . ($sigStart + 1), 'Pengawas');

        $sheet->setCellValue('B' . ($sigStart + 5), $this->proyek->penanggung_jawab_nama ?: '( ................................ )');
        $sheet->setCellValue('C' . ($sigStart + 5), $this->proyek->ketua_p2sp_nama ?: '( ................................ )');
        $sheet->setCellValue('E' . ($sigStart + 5), $this->proyek->bendahara_nama ?: '( ................................ )');
        $sheet->setCellValue('G' . ($sigStart + 5), $this->proyek->pengawas_nama ?: '( ................................ )');

        $this->formatLedgerStyles($sheet, $totalRow);
    }

    /**
     * Sheet 3: BB (Buku Bank)
     */
    protected function buildBbSheet(Spreadsheet $spreadsheet): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BB');

        // Table Header Columns (Row 2)
        $sheet->setCellValue('A2', 'No.');
        $sheet->setCellValue('B2', 'Tanggal');
        $sheet->setCellValue('C2', 'Uraian');
        $sheet->setCellValue('D2', 'No. Bukti');
        $sheet->setCellValue('E2', 'Penerimaan (Rp)');
        $sheet->setCellValue('F2', 'Pengeluaran (Rp)');
        $sheet->setCellValue('G2', 'Saldo (Rp)');

        $transaksis = $this->proyek->transaksis()->get();
        $currentRow = 3;
        $noUrut = 1;

        foreach ($transaksis as $trx) {
            if ($trx->jenis_transaksi === 'terima_dana') {
                $sheet->setCellValue('A' . $currentRow, $noUrut++);
                $sheet->setCellValue('B' . $currentRow, $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '');
                $sheet->setCellValue('C' . $currentRow, $trx->uraian);
                $sheet->setCellValue('D' . $currentRow, $trx->nomor_bukti);
                $sheet->setCellValue('E' . $currentRow, (float) $trx->nominal);

                if ($currentRow === 3) {
                    $sheet->setCellValue('G' . $currentRow, '=E3');
                } else {
                    $prevRow = $currentRow - 1;
                    $sheet->setCellValue('G' . $currentRow, '=G' . $prevRow . '+E' . $currentRow . '-F' . $currentRow);
                }
                $currentRow++;
            } elseif (in_array($trx->jenis_transaksi, ['tarik_bank', 'belanja_transfer'])) {
                $sheet->setCellValue('A' . $currentRow, $noUrut++);
                $sheet->setCellValue('B' . $currentRow, $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '');
                $sheet->setCellValue('C' . $currentRow, $trx->uraian);
                $sheet->setCellValue('D' . $currentRow, $trx->nomor_bukti);
                $sheet->setCellValue('F' . $currentRow, (float) $trx->nominal);

                if ($currentRow === 3) {
                    $sheet->setCellValue('G' . $currentRow, '=E3-F3');
                } else {
                    $prevRow = $currentRow - 1;
                    $sheet->setCellValue('G' . $currentRow, '=G' . $prevRow . '+E' . $currentRow . '-F' . $currentRow);
                }
                $currentRow++;
            }
        }

        $dataEndRow = max(3, $currentRow - 1);
        $totalRow = $currentRow;

        // TOTAL ROW
        $sheet->mergeCells('A' . $totalRow . ':D' . $totalRow);
        $sheet->setCellValue('A' . $totalRow, 'TOTAL');
        $sheet->setCellValue('E' . $totalRow, '=SUM(E3:E' . $dataEndRow . ')');
        $sheet->setCellValue('F' . $totalRow, '=SUM(F3:F' . $dataEndRow . ')');
        $sheet->setCellValue('G' . $totalRow, '=G' . $dataEndRow);

        // Signatures Block
        $sigStart = $totalRow + 3;
        $sheet->setCellValue('B' . $sigStart, 'Mengetahui :');
        
        $sheet->setCellValue('B' . ($sigStart + 1), 'Penanggung Jawab / KS');
        $sheet->setCellValue('C' . ($sigStart + 1), 'Ketua P2SP');
        $sheet->setCellValue('E' . ($sigStart + 1), 'Bendahara');
        $sheet->setCellValue('G' . ($sigStart + 1), 'Pengawas');

        $sheet->setCellValue('B' . ($sigStart + 5), $this->proyek->penanggung_jawab_nama ?: '( ................................ )');
        $sheet->setCellValue('C' . ($sigStart + 5), $this->proyek->ketua_p2sp_nama ?: '( ................................ )');
        $sheet->setCellValue('E' . ($sigStart + 5), $this->proyek->bendahara_nama ?: '( ................................ )');
        $sheet->setCellValue('G' . ($sigStart + 5), $this->proyek->pengawas_nama ?: '( ................................ )');

        $this->formatLedgerStyles($sheet, $totalRow);
    }

    protected function getBpktLastRowNumber(): int
    {
        $transaksis = $this->proyek->transaksis()->with('items')->get();
        $row = 3;
        foreach ($transaksis as $trx) {
            if ($trx->jenis_transaksi === 'tarik_bank') {
                $row++;
            } elseif ($trx->jenis_transaksi === 'belanja_tunai') {
                if ($trx->items->isNotEmpty()) {
                    $row += 1 + $trx->items->count();
                } else {
                    $row++;
                }
            }
        }
        return max(3, $row);
    }

    protected function getBbLastRowNumber(): int
    {
        $transaksis = $this->proyek->transaksis()->get();
        $row = 3;
        foreach ($transaksis as $trx) {
            if (in_array($trx->jenis_transaksi, ['terima_dana', 'tarik_bank', 'belanja_transfer'])) {
                $row++;
            }
        }
        return max(3, $row);
    }

    private function formatBkuStyles($sheet, int $totalRow, int $saldoBankRow): void
    {
        // Table Header Styling
        $sheet->getStyle('A1:K2')->getFont()->setBold(true);
        $sheet->getStyle('A1:K2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:K2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:K2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');

        // Borders
        $sheet->getStyle('A1:K' . $saldoBankRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A' . $totalRow . ':K' . $saldoBankRow)->getFont()->setBold(true);

        // Number format
        $sheet->getStyle('E3:E' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('K3:K' . $saldoBankRow)->getNumberFormat()->setFormatCode('#,##0');

        // Column widths auto
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function formatLedgerStyles($sheet, int $totalRow): void
    {
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');

        $sheet->getStyle('A2:G' . $totalRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A' . $totalRow . ':G' . $totalRow)->getFont()->setBold(true);

        $sheet->getStyle('E3:G' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
