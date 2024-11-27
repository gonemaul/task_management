<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dashboard()
    {
        if (request()->ajax()) {
            $monts = [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des'
            ];
            $data = [];
            foreach ($monts as $key => $mont) {
                $year = request()->year ?? Carbon::now()->year;
                $data['labels'][] = $mont;

                $result = Task::whereNotNull('end_date')
                    ->where('status', 'completed')
                    ->whereRaw('strftime("%Y", end_date) = ? AND strftime("%m", end_date) = ?', [$year, sprintf("%02d", $key + 1)])
                    ->count();

                $data['datasets'][0]['data'][] = $result;
            }

            $data['datasets'][0]['label'] = 'Revenue';
            $data['datasets'][0]['backgroundColor'] = 'rgba(255, 209, 102, 0.2)';
            $data['datasets'][0]['borderColor'] = '#ffd166';
            $data['datasets'][0]['borderWidth'] = 4;
            $data['datasets'][0]['fill'] = true;
            $data['datasets'][0]['pointBackgroundColor'] = '#f6d047';
            $data['datasets'][0]['pointBorderColor'] = '#f6d047';
            $data['datasets'][0]['pointHoverBackgroundColor'] = '#f6d047';
            $data['datasets'][0]['pointHoverBorderColor'] = '#f6d047';
            $data['datasets'][0]['tension'] = 0.4;
            $data['datasets'][0]['pointRadius'] = 0;
            $data['datasets'][0]['pointHoverRadius'] = 0;

            $respond['data'] = $data;
            $max = max($data['datasets'][0]['data']);
            if ($max > 1000000000000) {
                $maxValue = ceil($max / 1000000000000) * 1000000000000;
            } elseif ($max > 1000000000) {
                $maxValue = ceil($max / 1000000000) * 1000000000;
            } elseif ($max > 1000000) {
                $maxValue = ceil($max / 1000000) * 1000000;
            } elseif ($max > 1000) {
                $maxValue = ceil($max / 1000) * 1000;
            } else {
                $maxValue = $max;
            }
            // $respond['total'] = 'Rp. ' . number_format($total, 0, ',', '.');
            $respond['maxValue'] = $maxValue;
            return response($respond);
        }
        $task = Task::all();
        $completed = $task->where('status', 'completed')->count();
        $on_going = $task->where('status', 'on-going')->count();
        $pending = $task->where('status', 'pending')->count();
        $years = [];
        $currentYear = Carbon::now()->year;
        for ($i = 0; $i < 3; $i++) {
            $years[] = $currentYear - $i;
        }
        $all = Task::withTrashed()->count();
        return view('dashboard', get_defined_vars());
    }
    public function index()
    {
        if (request()->ajax()) {
            $task = Task::query()
                ->when(!empty(request()->date), function ($query) {
                    $query->where('start_date', request()->date)
                        ->orWhere('end_date', request()->date)
                        ->orWhere('deadline', request()->date);
                })
                ->when(!empty(request()->priority), function ($query) {
                    $query->where('priority', request()->priority);
                })
                ->when(!empty(request()->status), function ($query) {
                    $query->where('status', request()->status);
                });
            return DataTables::of($task)
                ->editColumn('priority', function ($item) {
                    if ($item->priority == 'high') {
                        return '<span class="badge text-bg-danger">High</span>';
                    } elseif ($item->priority == 'normal') {
                        return '<span class="badge text-bg-primary">Normal</span>';
                    } elseif ($item->priority == 'low') {
                        return '<span class="badge text-bg-secondary">Low</span>';
                    }
                })
                ->editColumn('status', function ($item) {
                    if ($item->status == 'pending') {
                        return '<span class="badge text-bg-warning">Pending</span>';
                    } elseif ($item->status == 'on-going') {
                        return '<span class="badge text-bg-info">On Going</span>';
                    } elseif ($item->status == 'completed') {
                        return '<span class="badge text-bg-success">Completed</span>';
                    }
                })
                ->editColumn('action', function ($item) {
                    return view('tasks.action', get_defined_vars());
                })
                ->rawColumns(['priority', 'status', 'action'])
                ->toJson();
        }
        return view('tasks.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = request()->validate([
            'name' => ['required', 'max:250'],
            'start_date' => ['date'],
            'deadline' => ['required', 'date'],
            'priority' => ['in:high,normal,low'],
            'status' => ['in:pending,on-going,completed'],
            'description' => ['max:250', 'string', 'nullable']
        ]);

        try {
            Task::create($validatedData);
            return redirect()->route('tasks.index')->with('success', 'Task berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan.', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = request()->validate([
            'name' => ['required', 'max:250'],
            'start_date' => ['date', 'nullable'],
            'deadline' => ['nullable', 'date'],
            'description' => ['max:250', 'string', 'nullable']
        ]);


        try {
            $task->update($validatedData);
            return redirect()->route('tasks.index')->with('success', 'Task berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan.', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(['success' => true, 'message' => 'Task berhasil dihapus,']);
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', 'Terjadi kesalahan.');
        }
    }

    public function markAsCompleted(Task $task)
    {
        try {
            $task->update([
                'status' => 'completed',
                'end_date' => now()
            ]);
            return redirect()->route('tasks.index')->with('success', 'Task berhasil diselesaikan.');
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', 'Terjadi kesalahan.');
        }
    }

    public function change(Task $task)
    {
        try {
            $task->update([
                'status' => request()->status,
                'priority' => request()->priority
            ]);
            return response()->json(['success' => true, 'message' => 'Status berhasil diubah', 'status' => request()->status, 'priority' => request()->priority]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.', 'error' => $e->getMessage()]);
        }
    }
}
