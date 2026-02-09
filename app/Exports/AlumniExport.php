<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AlumniExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query dengan filter
     */
    public function collection()
    {
        $query = Alumni::with(['angkatan', 'pendidikan', 'pekerjaan']);

        // Filter status verifikasi
        if (!empty($this->filters['status'])) {
            $query->where('status_verifikasi', $this->filters['status']);
        }

        // Filter angkatan
        if (!empty($this->filters['angkatan_id'])) {
            $query->where('angkatan_id', $this->filters['angkatan_id']);
        }

        // Filter kelengkapan profil
        if (isset($this->filters['complete']) && $this->filters['complete'] !== '') {
            $query->where('is_profile_complete', (int)$this->filters['complete']);
        }

        return $query->orderBy('nama_lengkap')->get();
    }

    /**
     * Header Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama Lengkap',
            'Angkatan',
            'Tahun Ajaran',
            'Tahun Lulus',
            'Alamat',
            'No. HP / WhatsApp',
            'Email',
            'Status Verifikasi',
            'Profil Lengkap',
            'Pendidikan Terakhir',
            'Pekerjaan Terkini',
            'Tanggal Registrasi',
        ];
    }

    /**
     * Mapping data ke kolom
     */
    public function map($alumni): array
    {
        // Ambil pendidikan terakhir
        $pendidikanTerakhir = $alumni->pendidikan()
            ->orderBy('tahun_lulus', 'desc')
            ->first();
        $pendidikanText = $pendidikanTerakhir
            ? $pendidikanTerakhir->jenjang . ' - ' . $pendidikanTerakhir->nama_instansi
            : '-';

        // Ambil pekerjaan terkini
        $pekerjaanTerkini = $alumni->pekerjaan()
            ->where('is_current', true)
            ->first();
        $pekerjaanText = $pekerjaanTerkini
            ? $pekerjaanTerkini->jabatan . ' di ' . $pekerjaanTerkini->nama_perusahaan
            : '-';

        // Status verifikasi dalam bahasa Indonesia
        $statusText = match($alumni->status_verifikasi) {
            'verified' => 'Terverifikasi',
            'pending' => 'Menunggu Verifikasi',
            'rejected' => 'Ditolak',
            default => $alumni->status_verifikasi,
        };

        // Kelengkapan profil
        $profilLengkap = $alumni->is_profile_complete ? 'Lengkap' : 'Belum Lengkap';

        static $counter = 1;

        return [
            $counter++,
            $alumni->nisn,
            $alumni->nama_lengkap,
            $alumni->angkatan->nama_angkatan ?? '-',
            $alumni->angkatan->tahun_ajaran ?? '-',
            $alumni->tahun_lulus,
            $alumni->alamat ?? '-',
            $alumni->no_hp ?? '-',
            $alumni->email ?? '-',
            $statusText,
            $profilLengkap,
            $pendidikanText,
            $pekerjaanText,
            $alumni->created_at->format('d-m-Y H:i'),
        ];
    }

    /**
     * Styling Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Header styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '213448'], // Primary color
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
            ],
        ];
    }
}
