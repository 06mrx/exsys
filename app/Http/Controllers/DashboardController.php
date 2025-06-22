<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamResult;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Counter data
        
        
            $user = auth()->user();
            if($user->is_admin) {
                $totalQuizzes = Quiz::count();
                $totalExams = Exam::count();
                $totalUsers = User::count();
                $totalExamResults = ExamResult::count();

                // Aktivitas terbaru (gabungan quiz dan ujian)
                $recentActivities = DB::table('quizzes')
                    ->select('id', 'name', 'created_at', DB::raw('"Quiz" as type'))
                    ->union(
                        DB::table('exams')
                            ->select('id', 'name', 'created_at', DB::raw('"Ujian" as type'))
                    )
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                return view('dashboard', compact(
                    'totalQuizzes',
                    'totalExams',
                    'totalUsers',
                    'totalExamResults',
                    'recentActivities'
                ));
            } else {
                return view('student.dashboard');
            }
        
    }
}