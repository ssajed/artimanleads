<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\CallLog;
use App\Models\Assignment;
use App\Models\Contact;
use Morilog\Jalali\Jalalian;

class CallLogController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();
    $query = CallLog::with(['project', 'user']);

    // ===== فیلتر بر اساس دسترسی =====
    if ($user->role === 'admin' || $user->role === 'sales_manager') {
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
    } else {
        $query->where('user_id', $user->id);
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
    }

    // ===== جستجو =====
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('subject', 'LIKE', "%{$search}%")
              ->orWhere('contact_person', 'LIKE', "%{$search}%")
              ->orWhere('result', 'LIKE', "%{$search}%")
              ->orWhereHas('project', function($q2) use ($search) {
                  $q2->where('title', 'LIKE', "%{$search}%")
                     ->orWhere('address', 'LIKE', "%{$search}%")
                     ->orWhere('region', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('user', function($q2) use ($search) {
                  $q2->where('name', 'LIKE', "%{$search}%");
              });
        });
    }

    // ===== سورت =====
    $sort = $request->get('sort', 'call_date');
    $order = $request->get('order', 'desc');
    
    $allowedSorts = ['call_date', 'next_call_date', 'subject', 'contact_person', 'created_at'];
    
    if (in_array($sort, $allowedSorts)) {
        $query->orderBy($sort, $order);
    } else {
        $query->latest('call_date');
    }

    $callLogs = $query->paginate(20);
    $sortParams = ['sort' => $sort, 'order' => $order];
    
    // ارسال پارامترهای جستجو به ویو
    $searchQuery = $request->get('search', '');
    
    return view('call-logs.index', compact('callLogs', 'sortParams', 'searchQuery'));
}

    public function selectProject()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin' || $user->role === 'sales_manager') {
            $projects = Project::with('user')->latest()->paginate(20);
        } elseif ($user->role === 'sales_expert') {
            $projects = Project::whereHas('assignments', function($q) use ($user) {
                $q->where('assigned_to', $user->id)->where('status', 'accepted');
            })->latest()->paginate(20);
        } else {
            $projects = Project::where('marketer_id', $user->id)->latest()->paginate(20);
        }
        
        return view('call-logs.select-project', compact('projects'));
    }

    public function create(Project $project)
    {
        $user = auth()->user();
        
        if ($user->role === 'sales_expert') {
            $isAssigned = Assignment::where('project_id', $project->id)
                                    ->where('assigned_to', $user->id)
                                    ->where('status', 'accepted')
                                    ->exists();
            
            if (!$isAssigned) {
                abort(403, 'شما به این پروژه دسترسی ندارید. ابتدا ارجاع را تایید کنید.');
            }
        } elseif (!in_array($user->role, ['admin', 'sales_manager'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $project->load('contacts');
        return view('call-logs.create', compact('project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'call_date_shamsi' => 'required|string',
            'call_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'result' => 'required|string',
            'next_call_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'new_contact_name' => 'nullable|string|max:255',
            'new_contact_mobile' => 'nullable|string|max:20',
            'new_contact_position' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $project = Project::find($request->project_id);

        if ($user->role === 'sales_expert') {
            $isAssigned = Assignment::where('project_id', $project->id)
                                    ->where('assigned_to', $user->id)
                                    ->where('status', 'accepted')
                                    ->exists();
            if (!$isAssigned) {
                abort(403, 'شما به این پروژه دسترسی ندارید.');
            }
        } elseif (!in_array($user->role, ['admin', 'sales_manager'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $contactPerson = $request->contact_person;
        if ($request->contact_person === 'new' && $request->new_contact_name) {
            $existingContact = Contact::where('project_id', $request->project_id)
                                      ->where('name', $request->new_contact_name)
                                      ->first();
            
            if (!$existingContact) {
                $contact = Contact::create([
                    'project_id' => $request->project_id,
                    'name' => $request->new_contact_name,
                    'mobile' => $request->new_contact_mobile,
                    'position' => $request->new_contact_position,
                ]);
                $contactPerson = $request->new_contact_name;
            } else {
                $contactPerson = $existingContact->name;
            }
        }

        CallLog::create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'call_date' => $request->call_date,
            'subject' => $request->subject,
            'contact_person' => $contactPerson,
            'result' => $request->result,
            'next_call_date' => $request->next_call_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('projects.show', $request->project_id)
                         ->with('success', 'تماس با موفقیت ثبت شد.');
    }

    public function show(CallLog $callLog)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'sales_manager']) && $callLog->user_id !== $user->id) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        return view('call-logs.show', compact('callLog'));
    }
}