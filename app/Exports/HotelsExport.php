<?php

namespace App\Exports;

use App\Models\Motel;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HotelsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $hotels;
    protected $city;

    public function __construct(array $hotels, string $city = 'city')
    {
        $this->hotels = $hotels;
        $this->city = $city;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row font size and bold
            1 => ['font' => ['bold' => true, 'size' => 14]],

            // Set font size for all other rows (optional)
            'A2:Z1000' => ['font' => ['size' => 14]]
        ];
    }

    public function collection()
    {

        return collect($this->hotels)->map(function ($hotel) {
            return [
                $hotel['name'] ?? '',
                $hotel['address'] ?? '',
                $hotel['ranking']['rating'] ?? '',
                $hotel['ranking']['user_total_rating'] ?? '',
                $hotel['price'] ?? '',
                $hotel['distance'] ?? '',
                $hotel['website'] ?? '',
                // $hotel['status'] ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'Address', 'Rating', 'Total Rating', 'Price (AUD)', 'Distance (km)', 'Website', 'Operating Status'];
    }
    public function getCity()
    {
        return $this->city;
    }
}
