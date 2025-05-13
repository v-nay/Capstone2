<?php

namespace App\Http\Controllers;

use App\Models\Motel;
use Illuminate\Http\Request;
use App\Exports\HotelsExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{

    // public function index(Request $request)
    // {
    //     dd('here');
    //     $data =  $request->all();
    //     $results = $this->getAllData($data);
    //     $query = 'seymour';
    //     return view('dashboard', compact('results', 'query'));
    // }
    public function filter(Request $request)
    {

        $data =  $request->all();
        $results = $this->getAllData($data);
        $query = 'seymour';
        return view('dashboard', compact('results', 'query'));
    }
    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {

        $query = Motel::query()
            ->leftJoin('rankings', 'motels.id', '=', 'rankings.motel_id')
            ->select('motels.*'); // prevent column conflict

        // Filter
        if (!empty($data['motel'])) {
            $query->where('motels.name', 'LIKE', '%' . $data['motel'] . '%');
        }
        if (!empty($data['price'])) {
            $query->where('motels.price', 'LIKE', '%' . $data['price'] . '%');
        }
        if (!empty($data['rating'])) {
            $query->where('rankings.rating', '>=', $data['rating']);
        }
        if (!empty($data['score'])) {
            $query->where('rankings.score', '>=', $data['score']);
        }
        if (!empty($data['rank'])) {
            $query->where('rankings.rank', '=', $data['rank']);
        }

        // Order by rank from related table
        $query->orderBy('rankings.rank', 'ASC'); // or DESC for reverse order

        // Pagination
        if ($pagination) {

            return $query->paginate(6);
        } else {

            return $query->get();
        }
    }
    public function export(Request $request)
    {
        $hotels = Motel::with('ranking')->get()->toArray();

        // dd($hotels);
        // $hotels = $request->input('hotels');
        $queryraw = $request->input('query');
        // $query = str_replace(' ', '_', $queryraw);
        $query = 'seymour';


        if (!$hotels) {
            return back()->with('error', 'No hotel data received for export.');
        }

        // $hotels = json_decode(base64_decode($hotels), true);

        if (!is_array($hotels)) {

            return back()->with('error', 'Invalid hotel data format.');
        }
        // Unique filename with timestamp
        $filename = 'Motels_in_' . $query . '_.xlsx';

        return Excel::download(new HotelsExport($hotels), $filename);
    }
}
