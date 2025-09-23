<?php

namespace App\Http\Controllers\Admin;

use App\Models\Raperda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RaperdaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $raperdas = Raperda::latest()->paginate(10);
        return view('admin.raperdas.index', compact('raperdas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.raperdas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'number'   => 'nullable|string|max:50',
            'title'    => 'required|string|max:200',
            'year'     => 'nullable|digits:4',
            'category' => 'nullable|string|max:100',
            'status'   => 'required|in:draft,final',
            'summary'  => 'nullable|string',
            'file'     => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('raperdas', 'public');
        }

        Raperda::create($data);
        return redirect()->route('admin.raperdas.index')->with('success', 'Raperda berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
