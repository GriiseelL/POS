<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class chartController extends Controller
{
    public function getData() {
        $data = Transaction::selectRaw('MONTH(created_at) as month, SUM(total) as total')
                ->groupBy('month')
                ->get();

        return response()->json($data);
    }
}
