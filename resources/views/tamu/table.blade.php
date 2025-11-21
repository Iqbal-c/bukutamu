<!-- COPY TABEL DARI home.blade.php KE SINI -->
<table class="table table-striped">
    <!-- thead, tbody, pagination -->
    <div class="card mt-4 shadow-sm">
        

        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tamu</h5>
            <input type="text" class="form-control form-control-sm w-50" placeholder="Cari nama / HP..."
                   value="{{ request('search') }}" 
                   onkeyup="if(event.key==='Enter') location.href='/daftar?search='+encodeURIComponent(this.value)">
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Hari/Tanggal</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Pesan & Kesan</th>
                            <th>Paraf</th>
                            <th>Foto</th>
                            @auth
                            <th>Aksi</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tamu as $i => $t)
                        <tr @if($loop->first) class="table-success" @endif>
                            <td>{{ $tamu->firstItem() + $i }}</td>
                            <td>{{ $t->created_at->translatedFormat('l, d M Y H:i') }}</td>
                            <td><strong>{{ $t->nama }}</strong></td>
                            <td>{{ $t->alamat }}</td>
                            <td>{{ $t->no_hp }}</td>
                            <td>{{ Str::limit($t->pesan_kesan, 40) }}</td>
                            <td>
                                @if($t->paraf)
                                    <img src="{{ asset('storage/'.$t->paraf) }}" class="paraf-img" alt="Paraf">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($t->foto)
                                    <img src="{{ asset('storage/'.$t->foto) }}" class="foto-img" alt="Foto">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            @auth
                            <td>
                                <a href="{{ route('admin.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.destroy', $t->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                                </form>
                            </td>
                            @endauth
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted">Belum ada tamu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light">
                {{ $tamu->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
</table>

