@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4 text-primary">Admin Panel - Buku Tamu</h1>
    
    <a href="{{ route('admin.csv') }}" class="btn btn-success mb-3">Export CSV</a>
    
    <!-- SEARCH -->
    <form class="mb-3">
        <input type="text" name="search" class="form-control w-50" 
               placeholder="Cari nama / HP..." value="{{ request('search') }}">
    </form>

    <!-- TABEL -->
    @include('tamu.table')
</div>
@endsection