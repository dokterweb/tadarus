<?php

namespace App\Exports;

use App\Models\Iqro_history;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class IqroExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    protected $start_date;
    protected $end_date;

    // Constructor untuk menerima start_date dan end_date
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        // Ambil data berdasarkan rentang tanggal yang diberikan
        return Iqro_history::whereBetween('tgl_iqro', [$this->start_date, $this->end_date])
            ->with(['iqro', 'iqro.siswa']) // Menyertakan data surat dan siswa
            ->get()
            ->map(function($item) {
                return [
                    'Tanggal'           => \Carbon\Carbon::parse($item->tgl_iqro)->format('d-M-Y'),
                    'Nama Siswa'        => $item->iqro->siswa->user->name,
                    'Ustadz Ustadzah'   => $item->iqro->ustadz->user->name,
                    'Jilid'             => $item->iqro_jilid,
                    'Nilai'             => $item->nilai,
                    'Keterangan'        => $item->keterangan,
                ];
            });
    }

    // Menentukan headings di Excel
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Siswa',
            'Ustadz Ustadzah',
            'Jilid',
            'Nilai',
            'Keterangan',
        ];
    }

    public function styles($sheet)
    {
        // Set border for the entire table
        $sheet->getStyle('A1:F' . $sheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Make the headings bold
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Align text to center for headings
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto size columns
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function columnFormats(): array
    {
        // Format the date column (Tanggal) to d-M-Y
        return [
            'A' => 'dd-mmm-yy',
        ];
    }
}
