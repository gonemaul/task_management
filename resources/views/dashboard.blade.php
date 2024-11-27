@extends('base.admin')
@section('title', 'Dashboard')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    @include('components.header', [
        'title' => 'Dashboard',
    ])
    <div class="container">
        <div class="row d-flex justify-content-between mt-2">
            <div class="col-md-2 p-3 rounded bg-primary btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $all }}</div>
                <h5 class="text-white">Total Task</h5>
            </div>
            <div class="col-md-2 p-3 rounded bg-success btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $completed }}</div>
                <h5 class="text-white">Completed</h5>
            </div>
            <div class="col-md-2 p-3 rounded bg-info btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $on_going }}</div>
                <h5 class="text-white">On Going</h5>
            </div>
            <div class="col-md-2 p-3 rounded bg-warning btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $pending }}</div>
                <h5 class="text-white">Pending</h5>
            </div>
            <div class="col-md-2 p-3 rounded bg-secondary btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $task->count() }}</div>
                <h5 class="text-white">Task Aktif</h5>
            </div>
        </div>
        <div class="row d-flex justify-content-between mt-3">
            <div class="col-md-3 p-3 rounded bg-danger btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $all }}</div>
                <h5 class="text-white">Priority High</h5>
            </div>
            <div class="col-md-3 p-3 rounded bg-primary btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $all }}</div>
                <h5 class="text-white">Priority Normal</h5>
            </div>
            <div class="col-md-3 p-3 rounded bg-secondary btn_task">
                <div class="count fs-3 fw-semibold text-white">{{ $all }}</div>
                <h5 class="text-white">Priority Low</h5>
            </div>
        </div>
        <div class="card my-4 p-3">
            <div class="top d-flex justify-content-between px-3">
                <div class="title ms-4">
                    <div class="fs-4 fw-semibold me-4">Task diselesaikan</div>
                </div>
                <div class="mx-2">
                    <select class="form-select" id="year">
                        @foreach ($years as $year)
                            @if ($year == $currentYear)
                                <option value="{{ $year }}" selected>{{ $year }}
                                </option>
                            @else
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @include('chart.line')
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('.btn_task').css({
            'cursor': 'pointer'
        });

        $('.btn_task').on('click', function() {
            window.location.href = '/tasks'
        })
    </script>
@endpush
