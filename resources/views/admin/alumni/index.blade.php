@extends('layouts.admin')

@section('title', 'Kelola Alumni')
@section('page-title', 'Kelola Alumni')

@section('content')
    <alumni-table 
        :rows="{{ json_encode($alumnis->items()) }}"
        :total-rows="{{ $alumnis->total() }}"
        :angkatans="{{ json_encode($angkatans) }}"
        detail-url="{{ url('admin/alumni') }}"
        export-url="{{ auth()->user()->isAdmin() ? route('admin.alumni.exportForm') : '' }}"
        import-url="{{ auth()->user()->isAdmin() ? route('admin.alumni.importForm') : '' }}"
        delete-all-url="{{ auth()->user()->isAdmin() ? route('admin.alumni.deleteAll') : '' }}"
        :can-edit="{{ auth()->user()->isAdmin() ? 'true' : 'false' }}">
        
        <template #pagination>
            {{ $alumnis->links() }}
        </template>
    </alumi-table>n
@endsection
