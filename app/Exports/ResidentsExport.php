<?php

namespace App\Exports;

use App\Models\Resident;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResidentsExport implements FromCollection, WithHeadings
{
    protected array $filters;

    /**
     * Accept optional filters as an array
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Return collection of residents
     */
    public function collection()
    {
        $query = Resident::query();

        // Apply search filter
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('block', 'like', "%{$search}%")
                  ->orWhere('lot', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['block'])) {
            $query->where('block', $this->filters['block']);
        }

        if (!empty($this->filters['lot'])) {
            $query->where('lot', $this->filters['lot']);
        }

        // Apply status filter
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['month'])) {
            $query->whereMonth('move_in_date', $this->filters['month']);
        }

        if (!empty($this->filters['year'])) {
            $query->whereYear('move_in_date', $this->filters['year']);
        }

        $customDate = $this->filters['custom_date'] ?? $this->filters['move_in_date'] ?? null;
        if (!empty($customDate)) {
            $query->whereDate('move_in_date', $customDate);
        }

        return $query->get([
            'id',
            'first_name',
            'last_name',
            'email',
            'contact_number',
            'block',
            'lot',
            'move_in_date',
            'move_out_date',
            'status',
        ]);
    }

    /**
     * Headings for Excel export
     */
    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Contact Number',
            'Block',
            'Lot',
            'Move In Date',
            'Move Out Date',
            'Status'
        ];
    }
}
