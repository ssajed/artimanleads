<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Assignment;
use Morilog\Jalali\Jalalian;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Project::with(['user', 'latestAssignment.assignedTo']);

        if ($user->role === 'marketer') {
            $query->where('marketer_id', $user->id);
        } elseif ($user->role === 'sales_expert') {
            $query->whereHas('assignments', function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->whereIn('status', ['pending', 'accepted']);
            });
        }

        if ($request->has('level') && $request->level != '') {
            $levelMap = [
                'A' => 'A_hot',
                'B' => 'B_followup',
                'C' => 'C_archive'
            ];
            $query->where('project_level', $levelMap[$request->level] ?? $request->level);
        }

        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('marketer_id', $request->user_id);
        }

        if ($request->has('purchase_stage') && $request->purchase_stage != '') {
            $query->where('purchase_status', $request->purchase_stage);
        }

        if ($request->has('assignment_status') && $request->assignment_status != '') {
            $query->whereHas('latestAssignment', function($q) use ($request) {
                $q->where('status', $request->assignment_status);
            });
        }

        if ($request->has('project_status') && $request->project_status != '') {
            $query->where('project_status', $request->project_status);
        }

        $projects = $query->latest()->paginate(15);
        $filter = $request->all();
        
        return view('projects.index', compact('projects', 'filter'));
    }

    public function selectType()
    {
        return view('projects.select-type');
    }

    public function create()
    {
        $type = request()->get('type', 'building');
        
        if ($type === 'industrial') {
            return view('projects.industrial');
        }
        
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visit_date_shamsi'     => 'required|regex:/^\d{4}\/\d{2}\/\d{2}$/',
            'visit_date'            => 'required|date',
            'title'                 => 'required|string|max:255',
            'address'               => 'required|string',
            'region'                => 'required|string',
            'building_type'         => 'required|array|min:1',
            'building_type.*'       => 'in:مسکونی,اداری,تجاری,هتل,بیمارستان,مختلط',
            'marketer_name'         => 'nullable|string|max:255',
            'floors'                => 'nullable|integer',
            'blocks'                => 'nullable|integer',
            'has_project_manager'   => 'nullable|boolean',
            'project_manager'       => 'nullable|string',
            'project_manager_mobile'=> 'nullable|string',
            'has_site_manager'      => 'nullable|boolean',
            'site_manager'          => 'nullable|string',
            'site_manager_mobile'   => 'nullable|string',
            'has_facilities_supervisor' => 'nullable|boolean',
            'facilities_supervisor' => 'nullable|string',
            'facilities_supervisor_mobile' => 'nullable|string',
            'has_purchasing_manager'=> 'nullable|boolean',
            'purchasing_manager'    => 'nullable|string',
            'purchasing_manager_mobile' => 'nullable|string',
            'has_mechanical_consultant' => 'nullable|boolean',
            'mechanical_consultant' => 'nullable|string',
            'mechanical_consultant_mobile' => 'nullable|string',
            'has_hvac_contractor'   => 'nullable|boolean',
            'hvac_contractor'       => 'nullable|string',
            'hvac_contractor_mobile'=> 'nullable|string',
            'contractor'            => 'nullable|string',
            'employer'              => 'nullable|string',
            'consultant'            => 'nullable|string',
            'has_chiller'           => 'required|in:yes,no',
            'chiller_brand'         => 'required_if:has_chiller,yes|nullable|string',
            'chiller_photo'         => 'nullable|image|max:2048',
            'has_cooling_tower'     => 'required|in:yes,no',
            'current_cooling_brand' => 'required_if:has_cooling_tower,yes|nullable|string',
            'capacity_tr'           => 'required_if:has_cooling_tower,yes|nullable|numeric',
            'cooling_tower_photo'   => 'nullable|image|max:2048',
            'purchase_stage'        => 'required|in:exclusive,public_tender,limited_tender,inquiry',
            'expected_purchase_date_shamsi' => 'required|regex:/^\d{4}\/\d{2}\/\d{2}$/',
            'competitors'           => 'nullable|string',
            'vendor_list'           => 'nullable|string',
            'previous_purchases'    => 'nullable|string',
            'competitor_seller'     => 'nullable|string',
            'approx_price'          => 'nullable|numeric',
            'score_floor'           => 'nullable|integer|min:0|max:10',
            'score_building_type'   => 'nullable|integer|min:0|max:15',
            'score_facilities_phase'=> 'nullable|integer|min:0|max:20',
            'score_purchase_access' => 'nullable|integer|min:0|max:20',
            'score_not_purchased'   => 'nullable|integer|min:0|max:30',
            'score_capacity'        => 'nullable|integer|min:0|max:20',
            'total_score'           => 'nullable|integer',
            'level'                 => 'required|in:A,B,C',
            'notes'                 => 'nullable|string',
        ]);

        $totalScore = ($validated['score_floor'] ?? 0) + 
                      ($validated['score_building_type'] ?? 0) + 
                      ($validated['score_facilities_phase'] ?? 0) + 
                      ($validated['score_purchase_access'] ?? 0) + 
                      ($validated['score_not_purchased'] ?? 0) + 
                      ($validated['score_capacity'] ?? 0);

        $chillerPhotoPath = null;
        if ($request->hasFile('chiller_photo')) {
            $chillerPhotoPath = $request->file('chiller_photo')->store('chillers', 'public');
        }

        $coolingTowerPhotoPath = null;
        if ($request->hasFile('cooling_tower_photo')) {
            $coolingTowerPhotoPath = $request->file('cooling_tower_photo')->store('cooling_towers', 'public');
        }

        $expectedPurchaseDate = null;
        if ($request->has('expected_purchase_date_shamsi') && $request->expected_purchase_date_shamsi) {
            try {
                $jalaliDate = $request->expected_purchase_date_shamsi;
                $jalalian = Jalalian::fromFormat('Y/m/d', $jalaliDate);
                $expectedPurchaseDate = $jalalian->toCarbon()->format('Y-m-d');
            } catch (\Exception $e) {
                $expectedPurchaseDate = null;
            }
        }

        $levelMap = [
            'A' => 'A_hot',
            'B' => 'B_followup',
            'C' => 'C_archive'
        ];
        $dbLevel = $levelMap[$validated['level']] ?? 'B_followup';

        $marketerId = auth()->id();

        Project::create([
            'title'                 => $validated['title'],
            'visit_date'            => $validated['visit_date'],
            'address'               => $validated['address'],
            'region'                => $validated['region'],
            'building_type'         => json_encode($validated['building_type'] ?? []),
            'marketer_name'         => $validated['marketer_name'] ?? null,
            'floors'                => $validated['floors'] ?? null,
            'blocks'                => $validated['blocks'] ?? null,
            'has_project_manager'   => $validated['has_project_manager'] ?? 0,
            'project_manager'       => $validated['project_manager'] ?? null,
            'project_manager_mobile'=> $validated['project_manager_mobile'] ?? null,
            'has_site_manager'      => $validated['has_site_manager'] ?? 0,
            'site_manager'          => $validated['site_manager'] ?? null,
            'site_manager_mobile'   => $validated['site_manager_mobile'] ?? null,
            'has_facilities_supervisor' => $validated['has_facilities_supervisor'] ?? 0,
            'facilities_supervisor' => $validated['facilities_supervisor'] ?? null,
            'facilities_supervisor_mobile' => $validated['facilities_supervisor_mobile'] ?? null,
            'has_purchasing_manager'=> $validated['has_purchasing_manager'] ?? 0,
            'purchasing_manager'    => $validated['purchasing_manager'] ?? null,
            'purchasing_manager_mobile' => $validated['purchasing_manager_mobile'] ?? null,
            'has_mechanical_consultant' => $validated['has_mechanical_consultant'] ?? 0,
            'mechanical_consultant' => $validated['mechanical_consultant'] ?? null,
            'mechanical_consultant_mobile' => $validated['mechanical_consultant_mobile'] ?? null,
            'has_hvac_contractor'   => $validated['has_hvac_contractor'] ?? 0,
            'hvac_contractor'       => $validated['hvac_contractor'] ?? null,
            'hvac_contractor_mobile'=> $validated['hvac_contractor_mobile'] ?? null,
            'contractor'            => $validated['contractor'] ?? null,
            'employer'              => $validated['employer'] ?? null,
            'consultant'            => $validated['consultant'] ?? null,
            'has_chiller'           => $validated['has_chiller'],
            'chiller_brand'         => $validated['chiller_brand'] ?? null,
            'chiller_photo'         => $chillerPhotoPath,
            'has_cooling_tower'     => $validated['has_cooling_tower'],
            'current_cooling_brand' => $validated['current_cooling_brand'] ?? null,
            'capacity_tr'           => $validated['capacity_tr'] ?? null,
            'cooling_tower_photo'   => $coolingTowerPhotoPath,
            'purchase_status'       => $validated['purchase_stage'],
            'estimated_purchase_date'=> $expectedPurchaseDate,
            'competitors'           => $validated['competitors'] ?? null,
            'vendor_list'           => $validated['vendor_list'] ?? null,
            'previous_purchases'    => $validated['previous_purchases'] ?? null,
            'competitor_seller'     => $validated['competitor_seller'] ?? null,
            'approx_price'          => $validated['approx_price'] ?? null,
            'score_floor'           => $validated['score_floor'] ?? 0,
            'score_building_type'   => $validated['score_building_type'] ?? 0,
            'score_facilities_phase'=> $validated['score_facilities_phase'] ?? 0,
            'score_purchase_access' => $validated['score_purchase_access'] ?? 0,
            'score_not_purchased'   => $validated['score_not_purchased'] ?? 0,
            'score_capacity'        => $validated['score_capacity'] ?? 0,
            'total_score'           => $totalScore,
            'project_level'         => $dbLevel,
            'project_status'        => 'lead',
            'notes'                 => $validated['notes'] ?? null,
            'user_id'               => auth()->id(),
            'marketer_id'           => $marketerId,
        ]);

        return redirect()->route('projects.index')
                         ->with('success', 'فرم بازدید پروژه با موفقیت ثبت شد.');
    }

    public function show(Project $project)
    {
        $project->load(['assignments.assignedTo', 'assignments.assignedBy', 'callLogs.user', 'contacts']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        // ... کد update (مشابه store با تغییرات)
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')
                         ->with('success', 'پروژه با موفقیت حذف شد.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:lead,assigned,negotiation,sold,archived,lost',
        ]);

        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'sales_manager', 'sales_expert'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        if ($user->role === 'sales_expert') {
            $assignment = Assignment::where('project_id', $project->id)
                                    ->where('assigned_to', $user->id)
                                    ->where('status', 'accepted')
                                    ->first();
            
            if (!$assignment) {
                abort(403, 'شما به این پروژه دسترسی ندارید.');
            }
            
            if (!in_array($request->status, ['sold', 'archived', 'lost'])) {
                abort(403, 'شما فقط می‌توانید وضعیت را به فروخته شده، بایگانی یا بازنده تغییر دهید.');
            }
        }

        $project->update(['project_status' => $request->status]);

        if (in_array($request->status, ['sold', 'archived', 'lost'])) {
            $assignment = Assignment::where('project_id', $project->id)
                                    ->where('status', 'accepted')
                                    ->first();
            if ($assignment) {
                $assignment->update(['status' => 'completed']);
            }
        }

        return redirect()->route('projects.show', $project)
                         ->with('success', 'وضعیت پروژه با موفقیت به‌روزرسانی شد.');
    }

    public function assignedToMe()
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'sales_manager', 'sales_expert'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }
        
        $projects = Project::whereHas('assignments', function($q) use ($user) {
            $q->where('assigned_to', $user->id)
              ->whereIn('status', ['pending', 'accepted']);
        })->latest()->paginate(15);
        
        return view('projects.assigned-to-me', compact('projects'));
    }
}