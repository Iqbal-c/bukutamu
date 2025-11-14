@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Edit Tamu: {{ $tamu->nama }}</h2>
    
    <form action="{{ route('admin.update', $tamu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        @include('tamu.form')
    </form>
</div>
@endsection