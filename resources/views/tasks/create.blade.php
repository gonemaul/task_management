@extends('base.admin')
@section('title', 'Tambah Task')

@section('content')
    @include('components.header', [
        'title' => 'Tambah Task',
        'backButton' => '',
    ])
    <div class="container mt-5 pb-3">
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Task<span class="text-danger fw-bold"> *</span></label>
                <input type="text"
                    class="form-control @error('name')
                    is-invalid
                @enderror"
                    id="name" name="name" autofocus value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai<span class="text-danger fw-bold"> *</span></label>
                <input type="date"
                    class="form-control @error('start_date')
                    is-invalid
                @enderror"
                    id="start_date" name="start_date" value="{{ old('start_date') }}">
                @error('start_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">Deadline<span class="text-danger fw-bold"> *</span></label>
                <input type="date"
                    class="form-control @error('deadline')
                    is-invalid
                @enderror"
                    id="deadline" name="deadline" value="{{ old('deadline') }}">
                @error('deadline')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" name="priority" id="priority">
                    <option value="">Pilih</option>
                    <option value="high">High</option>
                    <option value="normal">Normal</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" id="status">
                    <option value="">Pilih</option>
                    <option value="pending">Pending</option>
                    <option value="on-going">On Going</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="10"
                    value="{{ old('description') }}"></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Submit</button>
            <button class="btn btn-secondary" onclick="window.location.href='/tasks'" type="button">Cancel</button>
        </form>
    </div>
@endsection
