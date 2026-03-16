<?php

namespace App\Imports;

use App\Models\ModelSubKegiatanPWA;
use App\Models\ModelLaporanPWA;
use App\Models\ModelDataPWA;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;

class LaporanPWAImport implements ToModel, WithHeadingRow
{
    /* membersihkan nama */
    private function cleanFileName($text)
    {
        $text = strtolower($text);
        $text = str_replace(' ', '', $text);
        $text = preg_replace('/[^a-z0-9]/', '', $text);
        return substr($text, 0, 40);
    }

    /* membersihkan nominal */
    private function cleanNominal($value)
    {
        $value = str_replace(['Rp', 'rp', '.', ',', ' '], '', $value);
        return preg_replace('/[^0-9]/', '', $value);
    }

    /* ambil id file google drive */
    private function getDriveId($url)
    {
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $match)) {
            return $match[1];
        }

        if (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $match)) {
            return $match[1];
        }

        return null;
    }

    public function model(array $row)
    {
        set_time_limit(0);
        try {
            $subId = $row['laporan_pwa_subkegiatan'] ?? null;
            $tahun = $row['laporan_pwa_tahun'] ?? null;

            if (!$subId || !$tahun) {
                \Log::error('Sub kegiatan atau tahun kosong', $row);
                return null;
            }

            /* cari data pwa */
            $datapwa = ModelDataPWA::where('data_pwa_subkegiatan', $subId)->where('data_pwa_tahun', $tahun)->first();

            if (!$datapwa) {
                \Log::error('Data PWA tidak ditemukan', [
                    'sub' => $subId,
                    'tahun' => $tahun,
                ]);
                return null;
            }

            /* ambil nama sub kegiatan */
            $subNama = $datapwa->subkegiatan->subkegiatan_pwa_nama ?? 'sub';

            $subClean = $this->cleanFileName($subNama);
            $ketClean = $this->cleanFileName($row['laporan_pwa_keterangan'] ?? 'file');

            $link = $row['laporan_pwa_file'] ?? null;

            $filePath = null;

            if ($link) {
                $fileId = $this->getDriveId($link);

                if ($fileId) {
                    $download = 'https://drive.google.com/uc?export=download&id=' . $fileId;

                    $fileContent = @file_get_contents($download);

                    if ($fileContent) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_buffer($finfo, $fileContent);
                        finfo_close($finfo);

                        $ext = explode('/', $mime)[1] ?? 'pdf';

                        $folder = public_path("assets/laporanpwa/$tahun/$subClean");

                        if (!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        $baseName = $tahun . '_' . $subClean . '_' . $ketClean;
                        $filename = $baseName . '.' . $ext;

                        $counter = 1;

                        while (file_exists($folder . '/' . $filename)) {
                            $filename = $baseName . '_' . $counter . '.' . $ext;
                            $counter++;
                        }

                        file_put_contents($folder . '/' . $filename, $fileContent);

                        $filePath = "assets/laporanpwa/$tahun/$subClean/" . $filename;
                    }
                }
            }

            return new ModelLaporanPWA([
                'laporan_pwa_data_pwa' => $datapwa->data_pwa_id,
                'laporan_pwa_keterangan' => $row['laporan_pwa_keterangan'] ?? null,
                'laporan_pwa_nominal' => $this->cleanNominal($row['laporan_pwa_nominal'] ?? 0),
                'laporan_pwa_file' => $filePath,
            ]);
        } catch (\Exception $e) {
            \Log::error('Import gagal', [
                'error' => $e->getMessage(),
                'row' => $row,
            ]);

            return null;
        }
    }
}