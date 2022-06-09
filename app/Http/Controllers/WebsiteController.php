<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Http\Resources\WebsiteResource;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WebsiteResource::collection(Website::latest()->paginate());
    }
}
