<?php

namespace App\Exports;

use App\Models\Incident;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncidentMonthlyExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $month;
    protected int $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return Incident::with(['reporter', 'location', 'details'])
            ->whereMonth('incident_date', $this->month)
            ->whereYear('incident_date', $this->year)
            ->get();
    }

    public function headings(): array
    {
        return ['No. Tiket', 'Pelapor', 'Lokasi', 'Kategori', 'Status', 'Keparahan', 'Tanggal Kejadian'];
    }

    public function map($incident): array
    {
        return [
            $incident->ticket_number,
            $incident->reporter->name,
            $incident->location->site_name,
            $incident->category,
            $incident->status,
            $incident->severity ?? 'Unclassified',
            $incident->incident_date,
        ];
    }
}
