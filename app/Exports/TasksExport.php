<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromCollection, WithHeadings, WithMapping
{
    protected $date;
    protected $status;
    protected $user;

    public function __construct($date, $status, $user)
    {
        $this->date = $date;
        $this->status = $status;
        $this->user = $user;
    }

    public function collection()
    {
        $query = Task::with('user');
        
        if ($this->user->role === 'engineer') {
            $query->where('assigned_to', $this->user->id);
        }

        if (!empty($this->date)) {
            $query->whereDate('date', $this->date);
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
