<?php

namespace App\Http\Controllers;

use App\Models\Raperda;
use Illuminate\Http\Request;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim($request->get('q', ''));
        $tahun  = $request->get('tahun', '');
        $status = $request->get('status', ''); // draf|final

        $raperdas = Raperda::query()
            ->where('aktif', true)
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('judul', 'like', "%{$q}%");
            })
            ->when($tahun !== '', function ($qr) use ($tahun) {
                $qr->where('tahun', $tahun);
            })
            ->when(in_array($status, ['draf', 'final']), function ($qr) use ($status) {
                $qr->where('status', $status);
            })
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        // daftar tahun untuk dropdown (dari data aktif)
        $tahunList = Raperda::where('aktif', true)
            ->whereNotNull('tahun')
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('publikasi.index', compact('raperdas', 'q', 'tahun', 'status', 'tahunList'));
    }

    public function show(Raperda $raperda)
    {
        abort_unless($raperda->aktif, 404);
        return view('publikasi.show', compact('raperda'));
    }
}
