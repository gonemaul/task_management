@extends('base.admin')
@section('title', 'Edit Task')

@section('content')
    @include('components.header', [
        'title' => 'Edit Task',
        'backButton' => '',
    ])
    <div class="container mt-5 pb-3">
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Task<span class="text-danger fw-bold"> *</span></label>
                <input type="text"
                    class="form-control @error('name')
                    is-invalid
                @enderror"
                    id="name" name="name" autofocus value="{{ old('name', $task->name) }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @if ($task->status != 'completed')
                <div class="mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai<span class="text-danger fw-bold">
                            *</span></label>
                    <input type="date"
                        class="form-control @error('start_date')
                    is-invalid
                @enderror"
                        id="start_date" name="start_date" value="{{ old('start_date', $task->start_date) }}">
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
                        id="deadline" name="deadline" value="{{ old('deadline', $task->deadline) }}">
                    @error('deadline')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @endif
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="10"
                    value="{{ old('description', $task->description) }}"></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Submit</button>
            <button class="btn btn-secondary" onclick="window.location.href='/tasks'" type="button">Cancel</button>
        </form>
    </div>
@endsection
