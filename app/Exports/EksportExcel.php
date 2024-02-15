<?php

namespace App\Exports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EksportExcel implements FromCollection, WithHeadings, WithEvents
{
    protected $data;
    protected $totalPenduduk;

    public function __construct(array $data, $totalPenduduk)
    {
        $this->data = $data;
        $this->totalPenduduk = $totalPenduduk;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'NIK',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Alamat',
            'Kabupaten',
            'Provinsi',
            'Timestamp',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Menambahkan total penduduk di bagian bawah laporan
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $lastColumn = $event->sheet->getDelegate()->getHighestColumn();
                $event->sheet->setCellValue('A' . ($lastRow + 1), 'Total Penduduk: ' . $this->totalPenduduk);
                $event->sheet->mergeCells('A' . ($lastRow + 1) . ':' . $lastColumn . ($lastRow + 1));
                $event->sheet->getStyle('A' . ($lastRow + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
