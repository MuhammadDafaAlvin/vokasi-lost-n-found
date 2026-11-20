<?php

namespace App\Http\Controllers;

use Inertia\Response;

class AdminReportContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('admin/reports/index');
    }
}
