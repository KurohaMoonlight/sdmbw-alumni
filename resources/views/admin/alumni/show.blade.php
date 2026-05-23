@extends('layouts.admin')

@section('title', 'Detail Alumni')
@section('page-title', 'Detail Alumni')

@section('content')
    <admin-alumni-detail 
        :alumni="{{ json_encode($alumni->load(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos'])) }}" 
        verify-url="{{ route('admin.alumni.verify', $alumni) }}"
        reset-password-url="{{ route('admin.alumni.reset-password', $alumni) }}"
        delete-url="{{ route('admin.alumni.destroy', $alumni) }}"
        :can-edit="{{ auth()->user()->isAdmin() ? 'true' : 'false' }}">
    </admin-alumni-detail>
@endsection
