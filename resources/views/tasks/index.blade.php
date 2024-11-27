@extends('base.admin')
@section('title', 'Daftar Task')

@section('content')
@section('backButton', true)
@include('components.header', [
    'title' => 'Daftar Task',
])
@include('tasks.modal.detail')
@include('tasks.modal.change-status')
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.10.7/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.10.7/locale/id.js"></script>
<style>
    .action-btn {
        width: 30px;
        height: 30px;
        right: 20.73px;
        top: 0px;
        border-radius: 8.108px;
        background: #efeded;
        background-position: center;
        background-repeat: no-repeat;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='3' viewBox='0 0 9 3' fill='none'%3E%3Cpath d='M1.28383 0.802002C0.676856 0.802002 0.180237 1.29862 0.180237 1.9056C0.180237 2.51258 0.676856 3.0092 1.28383 3.0092C1.89081 3.0092 2.38743 2.51258 2.38743 1.9056C2.38743 1.29862 1.89081 0.802002 1.28383 0.802002ZM7.90542 0.802002C7.29844 0.802002 6.80183 1.29862 6.80183 1.9056C6.80183 2.51258 7.29844 3.0092 7.90542 3.0092C8.5124 3.0092 9.00902 2.51258 9.00902 1.9056C9.00902 1.29862 8.5124 0.802002 7.90542 0.802002ZM4.59463 0.802002C3.98765 0.802002 3.49103 1.29862 3.49103 1.9056C3.49103 2.51258 3.98765 3.0092 4.59463 3.0092C5.20161 3.0092 5.69823 2.51258 5.69823 1.9056C5.69823 1.29862 5.20161 0.802002 4.59463 0.802002Z' fill='black'/%3E%3C/svg%3E");
        transition: all 0.2s ease-in;

        :hover {
            transform: scale(1.1);
        }
    }
</style>
<div class="mx-5">
    @if (session()->has('success'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success"
                });
            })
        </script>
    @endif
    <div class="top justify-content-between d-flex align-items-center p-2">
        <div class="add">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-warning">Dashboard</a>
            <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary">Tambah Task</a>
        </div>
        <div class="filter d-flex">
            <div class="">
                <input type="date" class="form-control" id="date_filter">
            </div>
            <div class="mx-2">
                <select class="form-select" id="priority_filter">
                    <option value="">Priority</option>
                    <option value="high">High</option>
                    <option value="normal">Normal</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="">
                <select class="form-select" id="status_filter">
                    <option value="">Status</option>
                    <option value="completed">Completed</option>
                    <option value="on-going">On Going</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>
    </div>
    <div class="body mt-3">
        <table class="table table-bordered" id="table_tasks">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Nama Task</th>
                    <th scope="col" class="text-center">Tanggal Mulai</th>
                    <th scope="col" class="text-center">Deadline</th>
                    <th scope="col" class="text-center">Tanggal Selesai</th>
                    <th scope="col" class="text-center">Priority</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        dayjs.locale('id');
        const tableTask = $('#table_tasks').DataTable({
            pagingType: 'full_numbers',
            "language": {
                "lengthMenu": 'Row : _MENU_ <svg class="dropdown-arrow-table" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M15.8333 7.5L9.99996 13.3333L4.16663 7.5" stroke="#818489" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                "info": "_TOTAL_ Entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                },
            },
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{
                targets: [6],
                orderable: false,
                searchable: false
            }],
            ajax: {
                url: "{{ route('tasks.index') }}",
                data: function(data) {
                    data.status = $('#status_filter').val();
                    data.priority = $('#priority_filter').val();
                    data.date = $('#date_filter').val();
                }
            },
            columns: [{
                    data: 'name'
                },
                {
                    data: 'start_date',
                    className: 'text-center',
                    render: function(data, row, column) {
                        return moment(data).format('D MMMM Y');
                    }
                },
                {
                    data: 'deadline',
                    className: 'text-center',
                    render: function(data, row, column) {
                        return moment(data).format('D MMMM Y');
                    }
                },
                {
                    data: 'end_date',
                    className: 'text-center',
                    render: function(data, row, column) {
                        return data != null ? moment(data).format('D MMMM Y - HH:mm:ss') : '-';
                    }
                },
                {
                    data: 'priority',
                    className: 'text-center',
                },
                {
                    data: 'status',
                    className: 'text-center',
                },
                {
                    data: 'action',
                    className: 'text-center',

                }
            ]
        });

        $('#date_filter').on('change', function() {
            tableTask.draw();
        })
        $('#priority_filter').on('change', function() {
            tableTask.draw();
        })
        $('#status_filter').on('change', function() {
            tableTask.draw();
        })

        $(document).on('click', "[id^='detail']", function(e) {
            e.preventDefault();
            const item = $(this).data('item');
            console.log(item)
            $('#task_name').html(item.name);
            $('#start_date').html(dayjs(item.start_date).format(
                'D MMMM YYYY'));
            $('#deadline').html(dayjs(item.deadline).format(
                'D MMMM YYYY'));
            $('#end_date').html(item.end_date != null ? dayjs(item.created_at).format(
                'D MMMM YYYY, HH:mm') : '-');
            $('#description').html(item.description ?? '-');

            if (item.priority == 'high') {
                $('.priority').addClass('text-bg-danger').html('High');
            } else if (item.priority == 'normal') {
                $('.priority').addClass('text-bg-primary').html('Normal');
            } else if (item.priority == 'low') {
                $('.priority').addClass('text-bg-secondary').html('Low');
            }

            if (item.status == 'completed') {
                $('.status').addClass('text-bg-success').html('Completed');
            } else if (item.status == 'on-going') {
                $('.status').addClass('text-bg-info').html('On Going');
            } else if (item.status == 'pending') {
                $('.status').addClass('text-bg-warning').html('Pending');
            }
        })

        $(document).on('click', "[id^='change_task']", function(e) {
            e.preventDefault();
            $(this).removeData('item');
            const item = $(this).data('item');
            console.log(item)
            $('#change_status').val(item.status)
            $('#change_priority').val(item.priority);
            $('#btn_change').off('click').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('change', ':id') }}'.replace(':id', item.id),
                    type: 'POST',
                    data: {
                        status: $('#change_status').val(),
                        priority: $('#change_priority').val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#changeStatus').modal('hide');
                            $('#table_tasks').DataTable().ajax.reload(null,
                                false);
                            Swal.fire({
                                title: "Success!",
                                text: "Status berhasil diubah.",
                                icon: "success"
                            })
                        } else {
                            Swal.fire({
                                title: "Failed!",
                                text: "Gagal mengubah status.",
                                icon: "error"
                            })
                        }
                    }
                })
            })
        })

        $(document).on('click', "[id^='delete']", function(e) {
            e.preventDefault();
            $(this).removeData('id');
            const id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('tasks.destroy', ':id') }}'.replace(':id', id),
                        type: 'POST',
                        data: {
                            _method: 'DELETE'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#table_tasks').DataTable().ajax.reload(null,
                                    false);
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your data has been deleted.",
                                    icon: "success"
                                })
                            } else {
                                Swal.fire({
                                    title: "Failed!",
                                    text: "Failed delete your data.",
                                    icon: "error"
                                })
                            }
                        }
                    });
                }
            });
        })
    })
</script>
@endpush
