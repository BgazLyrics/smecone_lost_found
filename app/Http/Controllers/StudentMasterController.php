<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentMasterController extends Controller
{
    public function index(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') abort(403);

        $query = \App\Models\StudentMaster::query();
        
        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $query->where(function($builder) use ($q) {
                $builder->where('nis', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
            });
        }

        if ($request->has('kelas') && !empty($request->kelas)) {
            $query->where('kelas', $request->kelas);
        }

        $students = $query->latest('id')->paginate(15)->withQueryString();
        
        $kelasList = \App\Models\StudentMaster::select('kelas')
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $totalRegistered = \App\Models\StudentMaster::where('is_registered', true)->count();
        $totalStudents = \App\Models\StudentMaster::count();

        return view('admin.student_masters.index', compact('students', 'totalRegistered', 'totalStudents', 'kelasList'));
    }

    public function importCsv(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120'
        ]);

        $file = $request->file('csv_file');
        $inserted = 0;
        $updated = 0;

        if (($handle = fopen($file->getRealPath(), "r")) !== false) {
            // Auto-detect delimiter
            $firstLine = fgets($handle);
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            rewind($handle);

            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (count($data) >= 2) {
                    $nis = trim($data[0]);
                    $name = trim($data[1]);
                    $kelas = isset($data[2]) ? trim($data[2]) : null;

                    if (empty($nis) || strtolower($nis) === 'nis' || strtolower($nis) === 'nomor induk') {
                        continue;
                    }

                    $student = \App\Models\StudentMaster::where('nis', $nis)->first();
                    if ($student) {
                        $student->name = $name;
                        if ($kelas) $student->kelas = $kelas;
                        $student->save();
                        $updated++;
                    } else {
                        \App\Models\StudentMaster::create([
                            'nis' => $nis,
                            'name' => $name,
                            'kelas' => $kelas,
                            'is_registered' => false
                        ]);
                        $inserted++;
                    }
                }
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', "Buku Induk diperbarui: {$inserted} jiwa ditambahkan, {$updated} termutakhirkan.");
    }
}
