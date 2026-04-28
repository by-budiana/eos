<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Attendance::with('user')->orderBy('date', 'desc')->orderBy('check_in_time', 'desc');

        if (isset($this->filters['date']) && $this->filters['date'] != '') {
            $query->where('date', $this->filters['date']);
        }

        if (isset($this->filters['user_id']) && $this->filters['user_id'] != '') {
            $query->where('user_id', $this->filters['user_id']);
        }

        if (isset($this->filters['status']) && $this->filters['status'] != '') {
            $query->where('status', $this->filters['status']);
        }

        if (auth()->user()->role != 'admin') {
            $query->where('user_id', auth()->user()->id);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'Tanggal',
            'Waktu Check In',
            'Waktu Check Out',
            'Status',
            'Lat/Long In',
            'Lat/Long Out'
        ];
    }

    public function map($attendance): array
    {
        static $row = 1;
        return [
            $row++,
            $attendance->user->name ?? '-',
            $attendance->date,
            $attendance->check_in_time,
            $attendance->check_out_time ?? '-',
            ucfirst($attendance->status),
            $attendance->check_in_latitude . ', ' . $attendance->check_in_longitude,
            $attendance->check_out_latitude . ', ' . $attendance->check_out_longitude,
        ];
    }
}
