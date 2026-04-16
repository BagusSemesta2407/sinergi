<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class SesiController extends Controller
{
    public function index()
    {
        $sesi = SesiAbsensi::all();
        return view('admin.sesi.index', compact('sesi'));
    }

    public function create()
    {
        return view('admin.sesi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sesi' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string'
        ]);
        
        // Nonaktifkan semua sesi lainnya jika ini akan diaktifkan
        if ($request->aktif) {
            SesiAbsensi::where('aktif', true)->update(['aktif' => false]);
        }
        
        SesiAbsensi::create($request->all());
        
        return redirect()->route('admin.sesi.index')
            ->with('success', 'Sesi berhasil ditambahkan.');
    }

    public function edit(SesiAbsensi $sesi)
    {
        return view('admin.sesi.edit', compact('sesi'));
    }

    public function update(Request $request, SesiAbsensi $sesi)
    {
        $request->validate([
            'nama_sesi' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string'
        ]);
        
        // Nonaktifkan semua sesi lainnya jika ini akan diaktifkan
        if ($request->aktif) {
            SesiAbsensi::where('aktif', true)
                ->where('id', '!=', $sesi->id)
                ->update(['aktif' => false]);
        }
        
        $sesi->update($request->all());
        
        return redirect()->route('admin.sesi.index')
            ->with('success', 'Sesi berhasil diperbarui.');
    }

    public function toggleAktif(SesiAbsensi $sesi)
    {
        if ($sesi->aktif) {
            $sesi->update(['aktif' => false]);
        } else {
            // Nonaktifkan semua yang lain
            SesiAbsensi::where('aktif', true)->update(['aktif' => false]);
            $sesi->update(['aktif' => true]);
        }
        
        return redirect()->back()->with('success', 'Status sesi diperbarui.');
    }
}
