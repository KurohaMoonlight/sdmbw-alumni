@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Statistik')

@section('content')
    <laporan-dashboard 
        :stats="{{ json_encode($stats) }}" 
        :angkatan-stats="{{ json_encode($angkatanStats) }}"
        :pendidikan-stats="{{ json_encode($pendidikanStats) }}"
        :pekerjaan-stats="{{ json_encode($pekerjaanStats) }}"
        export-pdf-url="{{ route('admin.laporan.export-pdf') }}">
    </laporan-dashboard>
@endsection
