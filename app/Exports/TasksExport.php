<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;
    protected $user;

    public function __construct($startDate, $endDate, $status, $user)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->user = $user;
    }

    public function collection()
    {
        $query = Task::with('user');
        
        if ($this->user->role === 'engineer') {
            $query->where('assigned_to', $this->user->id);
        }

        if (!empty($this->startDate)) {
            $query->where('date', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->where('date', '<=', $this->endDate);
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Tugas',
            'Deskripsi Tugas',
            'Status'
        ];
    }

    public function map($task): array
    {
        return [
            $task->date,
            $task->title,
            $task->description,
            $task->status,
        ];
    }
}
