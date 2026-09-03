<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];
        $marketerStats = [];
        $allLeadsCount = 0;
        $wonLeadsCount = 0;
        $levelBLeadsCount = 0;
        $levelCLeadsCount = 0;
        $myLeadsCount = 0;
        $myWonCount = 0;
        $chartData = [
            'labels' => ['بدون استعلام', 'استعلام', 'مذاکره', 'خرید شده'],
            'data' => [0, 0, 0, 0]
        ];

        try {
            // ===== محاسبه آمار بر اساس نقش کاربر =====
            if ($user->role === 'admin' || $user->role === 'sales_manager') {
                // مدیر کل و مدیر فروش: همه آمارها
                $allLeadsCount = Project::count();
                $wonLeadsCount = Project::where('project_level', 'A_hot')->count();
                $levelBLeadsCount = Project::where('project_level', 'B_followup')->count();
                $levelCLeadsCount = Project::where('project_level', 'C_archive')->count();

                // آمار هر بازاریاب
                $marketers = User::where('role', 'marketer')->get();
                foreach ($marketers as $marketer) {
                    $leads = Project::where('marketer_id', $marketer->id)->count();
                    $won = Project::where('marketer_id', $marketer->id)->where('project_level', 'A_hot')->count();
                    $marketerStats[] = [
                        'name' => $marketer->full_name ?: $marketer->name,
                        'leads' => $leads,
                        'won' => $won,
                    ];
                }

                // آمار برای نمودار (وضعیت خرید)
                $chartData = [
                    'labels' => ['بدون استعلام', 'استعلام', 'مذاکره', 'خرید شده'],
                    'data' => [
                        Project::where('purchase_status', 'no_inquiry')->count(),
                        Project::where('purchase_status', 'inquiry')->count(),
                        Project::where('purchase_status', 'negotiation')->count(),
                        Project::where('purchase_status', 'purchased')->count(),
                    ]
                ];
                
            } else {
                // کارشناس فروش و بازاریاب: فقط آمار شخصی
                $myLeadsCount = Project::where('marketer_id', $user->id)->count();
                $myWonCount = Project::where('marketer_id', $user->id)->where('project_level', 'A_hot')->count();

                $allLeadsCount = $myLeadsCount;
                $wonLeadsCount = $myWonCount;
                $levelBLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'B_followup')->count();
                $levelCLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'C_archive')->count();

                $stats = [
                    'my_leads' => $myLeadsCount,
                    'my_won' => $myWonCount,
                ];
            }
        } catch (\Exception $e) {
            // اگر خطا بود، فقط آمار پایه را نمایش بده
            $allLeadsCount = Project::count();
        }

        return view('dashboard', compact(
            'stats', 
            'marketerStats', 
            'allLeadsCount', 
            'wonLeadsCount',
            'levelBLeadsCount',
            'levelCLeadsCount',
            'chartData'
        ));
    }
}