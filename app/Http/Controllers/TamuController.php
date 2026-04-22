<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TamuController extends Controller
{
    // === PRIVATE: Logika query tamu (NO DUPLIKAT!) ===
    private function getTamu(Request $request, $perPage = 10)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');
        
        $allowedColumns = ['nama', 'alamat', 'no_hp', 'keperluan_kunjungan', 'created_at'];
        if (!in_array($sortBy, $allowedColumns)) $sortBy = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';
        
        return Tamu::query()
            ->when($search, fn($q) => $q
                ->where('nama', 'like', "%$search%")
                ->orWhere('alamat', 'like', "%$search%")
                ->orWhere('no_hp', 'like', "%$search%")
                ->orWhere('keperluan_kunjungan', 'like', "%$search%")
            )
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends(['search' => $search, 'sort_by' => $sortBy, 'sort_dir' => $sortDir]);
    }

    // === FORM ISI TAMU ===
    public function create()
    {
        return view('tamu.create');
    }

    // === SANITASI INPUT ===
    private function sanitizeInput($value)
    {
        return trim(strip_tags($value));
    }

    // === SIMPAN DATA TAMU ===
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'keperluan_kunjungan' => 'required|string',
            'paraf_file' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10240',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10240',
        ]);

        $data = [
            'nama' => $this->sanitizeInput($request->nama),
            'alamat' => $this->sanitizeInput($request->alamat),
            'no_hp' => $this->sanitizeInput($request->no_hp),
            'keperluan_kunjungan' => $this->sanitizeInput($request->keperluan_kunjungan),
        ];
        $data['ip_address'] = $request->ip();

        if ($request->hasFile('paraf_file')) {
            $data['paraf'] = $request->file('paraf_file')->store('tamu', 'public');
        }
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('tamu', 'public');
        }

        Tamu::create($data);

        // Jika user login (admin), redirect ke dashboard; else ke daftar
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('success', 'Data tamu berhasil ditambahkan!');
        }

        return redirect()->route('tamu.daftar')->with('success', 'Terima kasih! Data Anda telah tersimpan.');
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
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');
        
        // Statistik untuk grafik (7 hari terakhir)
        $labelsHari = [];
        $dataHari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $labelsHari[] = $tanggal->translatedFormat('d M');
            $dataHari[] = \App\Models\Tamu::whereDate('created_at', $tanggal)->count();
        }
        
        // Statistik 12 bulan terakhir
        $labelsBulan = [];
        $dataBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $labelsBulan[] = $bulan->translatedFormat('F');
            $dataBulan[] = \App\Models\Tamu::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }
        
        return view('dashboard', compact('tamu', 'sortBy', 'sortDir', 'labelsHari', 'dataHari', 'labelsBulan', 'dataBulan'));
    }

    // === DAFTAR USER ADMIN ===
    public function admin()
    {
        $users = \App\Models\User::orderBy('created_at', 'desc')->get();
        return view('admin.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin')->with('success', 'User Admin berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin')->with('success', 'User Admin berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        // Mencegah menghapus diri sendiri
        if (Auth::id() == $user->id) {
            return redirect()->route('admin')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('admin')->with('success', 'User Admin berhasil dihapus!');
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

    // === EXPORT EXCEL ===
    public function exportCsv(Request $request)
    {
        $tamu = Tamu::query();
        
        $title = 'Semua Data';
        
        // Filter per minggu
        if ($request->has('week') && $request->week) {
            list($year, $week) = explode('-W', $request->week);
            $startOfWeek = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $tamu->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            $title = 'Minggu ' . $week . ' Tahun ' . $year;
        }
        
        // Filter per bulan
        if ($request->has('month') && $request->month) {
            list($year, $month) = explode('-', $request->month);
            $tamu->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $title = \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        }
        
        $tamu = $tamu->orderBy('created_at', 'desc')->get();
        $filename = 'Buku-Tamu-' . ($request->week ? $request->week : ($request->month ? $request->month : now()->format('Y-m-d'))) . '.xls';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"></head>';
        echo '<body>';
        echo '<h2 style="text-align: center;">Buku Tamu Digital - ' . $title . '</h2>';
        echo '<p style="text-align: center;">Dicetak pada: ' . now()->translatedFormat('d F Y H:i') . '</p>';
        echo '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">';
        
        // Header
        echo '<tr style="background-color: #0d6efd; color: white; font-weight: bold;">';
        echo '<th>No</th>';
        echo '<th>Nama</th>';
        echo '<th>Alamat</th>';
        echo '<th>No. HP</th>';
        echo '<th>Keperluan Kunjungan</th>';
        echo '<th>Waktu Kunjungan</th>';
        echo '</tr>';
        
        // Data
        foreach ($tamu as $i => $t) {
            echo '<tr>';
            echo '<td>' . ($i + 1) . '</td>';
            echo '<td>' . htmlspecialchars($t->nama) . '</td>';
            echo '<td>' . htmlspecialchars($t->alamat) . '</td>';
            echo '<td>' . htmlspecialchars($t->no_hp) . '</td>';
            echo '<td>' . htmlspecialchars($t->keperluan_kunjungan) . '</td>';
            echo '<td>' . $t->created_at->format('d/m/Y H:i') . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</body></html>';
        exit;
    }

    // === EXPORT PDF ===
    public function exportPdf(Request $request)
    {
        $tamu = Tamu::query();
        
        $title = 'Semua Data';
        
        // Filter per minggu
        if ($request->has('week') && $request->week) {
            list($year, $week) = explode('-W', $request->week);
            $startOfWeek = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $tamu->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            $title = 'Minggu ' . $week . ' Tahun ' . $year;
        }
        
        // Filter per bulan
        if ($request->has('month') && $request->month) {
            list($year, $month) = explode('-', $request->month);
            $tamu->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $title = \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        }
        
        $tamu = $tamu->orderBy('created_at', 'desc')->get();
        $filename = 'Buku-Tamu-' . ($request->week ? $request->week : ($request->month ? $request->month : now()->format('Y-m-d'))) . '.pdf';

        $html = view('tamu.export-pdf', compact('tamu', 'title'))->render();
        
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download($filename);
        }
        
        return back()->with('error', 'Package dompdf belum terinstall! Gunakan terminal Laragon untuk install: composer require barryvdh/laravel-dompdf');
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
        $validated = $request->validate([
            'nama' => 'required', 'alamat' => 'required',
            'no_hp' => 'required', 'keperluan_kunjungan' => 'required',
            'foto' => 'nullable|image', 'paraf_file' => 'nullable|image',
        ]);

        $data = [
            'nama' => $this->sanitizeInput($request->nama),
            'alamat' => $this->sanitizeInput($request->alamat),
            'no_hp' => $this->sanitizeInput($request->no_hp),
            'keperluan_kunjungan' => $this->sanitizeInput($request->keperluan_kunjungan),
        ];

        if ($request->hasFile('foto')) {
            if ($tamu->foto) Storage::disk('public')->delete($tamu->foto);
            $data['foto'] = $request->file('foto')->store('tamu', 'public');
        }
        if ($request->hasFile('paraf_file')) {
            if ($tamu->paraf) Storage::disk('public')->delete($tamu->paraf);
            $data['paraf'] = $request->file('paraf_file')->store('tamu', 'public');
        }

        $tamu->update($data);
        return redirect()->route('dashboard')->with('success', 'Data tamu berhasil diupdate!');
    }
}
