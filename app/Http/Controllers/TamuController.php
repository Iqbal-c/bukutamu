<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TamuController extends Controller
{
    // === PRIVATE: Logika query tamu (NO DUPLIKAT!) ===
    private function getTamu(Request $request, $perPage = 10)
    {
        $search = $request->query('search');
        return Tamu::query()
            ->when($search, fn($q) => $q
                ->where('nama', 'like', "%$search%")
                ->orWhere('no_hp', 'like', "%$search%")
            )
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['search' => $search]);
    }

    // === HALAMAN UTAMA (HOME) === tidak aktif lagi bisa dihapus
    public function index(Request $request)
    {
        $tamu = $this->getTamu($request, 10);
        return view('home', compact('tamu'));
    }

    // === FORM ISI TAMU ===
    public function create()
    {
        return view('tamu.create');
    }

    // === SIMPAN DATA TAMU ===
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'pesan_kesan' => 'required|string',
            'paraf_file' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['nama', 'alamat', 'no_hp', 'pesan_kesan']);
        $data['ip_address'] = $request->ip();

        if ($request->hasFile('paraf_file')) {
            // $data['paraf'] = $request->paraf_text ?? 'Tidak ada paraf';
            $data['paraf'] = $request->file('paraf_file')->store('uploads', 'public');
        }
        if ($request->hasFile('foto')) {
            // $data['foto'] = $request->file('foto')->store('foto', 'public');
            $data['foto'] = $request->file('foto')->store('uploads', 'public');
        }

        Tamu::create($data);

        return redirect('/daftar')->with('success', 'Terima kasih! Data Anda telah tersimpan.');
    }

    // === DAFTAR TAMU FRONTEND (PUBLIK) ===
    public function daftar(Request $request)
    {
        $search = $request->query('search');
        $tamu = Tamu::query()
            ->when($search, fn($q) => $q
                ->where('nama', 'like', "%$search%")
                ->orWhere('no_hp', 'like', "%$search%")
            )
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tamu.daftar', compact('tamu'));
    }

    // === DASHBOARD BACKEND ===
    public function dashboard(Request $request)
    {
        $tamu = $this->getTamu($request, 10);
        return view('dashboard', compact('tamu'));
    }

    // === ADMIN PANEL ===
    public function admin(Request $request)
    {
        $tamu = $this->getTamu($request, 10);
        return view('admin.index', compact('tamu'));
    }

    // === HAPUS ===
    public function destroy(string $id)
    {
        $tamu = Tamu::findOrFail($id);
        if ($tamu->paraf) Storage::disk('public')->delete($tamu->paraf);
        if ($tamu->foto) Storage::disk('public')->delete($tamu->foto);
        $tamu->delete();
        return back()->with('success', 'Data tamu telah dihapus.');
    }

    // === EXPORT CSV ===
    public function exportCsv()
    {
        $tamu = Tamu::orderBy('created_at', 'desc')->get();
        $filename = 'buku-tamu-' . now()->format('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['No', 'Nama', 'Alamat', 'No. HP', 'Pesan', 'Waktu']);
        foreach ($tamu as $i => $t) {
            fputcsv($handle, [
                $i + 1, $t->nama, $t->alamat, $t->no_hp,
                $t->pesan_kesan, $t->created_at->format('d/m/Y H:i')
            ]);
        }
        fclose($handle);
        exit;
    }

    // === EDIT & UPDATE ===
    public function edit($id)
    {
        $tamu = Tamu::findOrFail($id);
        return view('tamu.edit', compact('tamu'));
    }

    public function update(Request $request, $id)
    {
        $tamu = Tamu::findOrFail($id);
        $data = $request->validate([
            'nama' => 'required', 'alamat' => 'required',
            'no_hp' => 'required', 'pesan_kesan' => 'required',
            'foto' => 'nullable|image', 'paraf_file' => 'nullable|image',
        ]);

        if ($request->hasFile('foto')) {
            Storage::delete('public/' . $tamu->foto);
            $data['foto'] = $request->file('foto')->store('tamu', 'public');
        }
        if ($request->hasFile('paraf_file')) {
            Storage::delete('public/' . $tamu->paraf);
            $data['paraf'] = $request->file('paraf_file')->store('tamu', 'public');
        }

        $tamu->update($data);
        return redirect('/admin')->with('success', 'Data tamu berhasil diupdate!');
    }
}