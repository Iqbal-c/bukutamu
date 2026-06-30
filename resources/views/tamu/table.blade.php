    <div class="card mt-4 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Hari/Tanggal</th>
                            <th style="width: 15%;">Nama</th>
                            <th style="width: 15%;">Alamat</th>
                            <th style="width: 10%;">No. HP</th>
                            <th style="width: 15%;">Keperluan</th>
                            <th style="width: 8%;">Paraf</th>
                            <th style="width: 8%;">Foto</th>
                            @auth
                            <th style="width: 9%;">Aksi</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tamu as $i => $t)
                        <tr @if($loop->first) class="table-light" @endif>
                            <td class="text-center"><strong>{{ $tamu->firstItem() + $i }}</strong></td>
                            <td class="text-center">{{ ($t->tanggal_masuk ?? $t->created_at)->translatedFormat('d M Y, H:i') }}</td>
                            <td><strong>{{ $t->nama }}</strong></td>
                            <td>{{ Str::limit($t->alamat, 30) }}</td>
                            <td class="text-center">{{ $t->no_hp }}</td>
                            <td>{{ Str::limit($t->keperluan_kunjungan, 40) }}</td>
                            <td class="text-center">
                                @if($t->paraf)
                                    <img src="{{ asset('storage/'.$t->paraf) }}" class="rounded shadow-sm" style="width: 60px; height: 40px; object-fit: cover;" alt="Paraf">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($t->foto)
                                    <img src="{{ asset('storage/'.$t->foto) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="Foto">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            @auth
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.edit', $t->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.destroy', $t->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                            @endauth
                        </tr>
                        @empty
                        <tr><td colspan="@auth9@else8@endauth" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2"></i><br>Belum ada tamu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tamu->hasPages())
            <div class="card-footer bg-white border-top-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $tamu->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
