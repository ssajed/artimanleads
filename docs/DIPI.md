=== purchase_status Analysis ===
--------------------------------
app/Http/Controllers/DashboardController.php:52:                        Project::where('purchase_status', 'no_inquiry')->count(),
app/Http/Controllers/DashboardController.php:53:                        Project::where('purchase_status', 'inquiry')->count(),
app/Http/Controllers/DashboardController.php:54:                        Project::where('purchase_status', 'negotiation')->count(),
app/Http/Controllers/DashboardController.php:55:                        Project::where('purchase_status', 'purchased')->count(),
app/Http/Controllers/LeadAssignmentController.php:48:        if ($project->purchase_status === 'no_inquiry') {
app/Http/Controllers/LeadAssignmentController.php:49:            $project->update(['purchase_status' => 'inquiry']);
app/Http/Controllers/ProjectController.php:40:            $query->where('purchase_status', $request->purchase_stage);
app/Http/Controllers/ProjectController.php:208:            'purchase_status'       => $validated['purchase_stage'],
app/Models/Project.php:67:        'purchase_status',

   ReflectionException 

  Class "App\Http\Controllers\Admin\BackupController" does not exist

  at vendor/laravel/framework/src/Illuminate/Foundation/Console/RouteListCommand.php:235
    231▕             if ($this->isFrameworkController($route)) {
    232▕                 return false;
    233▕             }
    234▕ 
  ➜ 235▕             $path = (new ReflectionClass($route->getControllerClass()))
    236▕                 ->getFileName();
    237▕         } else {
    238▕             return false;
    239▕         }

      [2m+3 vendor frames [22m

  4   [internal]:0
      Illuminate\Foundation\Console\RouteListCommand::Illuminate\Foundation\Console\{closure}()
      [2m+17 vendor frames [22m

  22  artisan:13
      Illuminate\Foundation\Application::handleCommand()


=== purchase_stage Analysis ===
--------------------------------
app/Http/Controllers/ProjectController.php:39:        if ($request->has('purchase_stage') && $request->purchase_stage != '') {
app/Http/Controllers/ProjectController.php:40:            $query->where('purchase_status', $request->purchase_stage);
app/Http/Controllers/ProjectController.php:116:            'purchase_stage'        => 'required|in:exclusive,public_tender,limited_tender,inquiry',
app/Http/Controllers/ProjectController.php:208:            'purchase_status'       => $validated['purchase_stage'],
app/Models/Project.php:58:        'purchase_stage',
resources/views/projects/edit.blade.php:161:                        <label><input type="radio" name="purchase_stage" value="no_inquiry" {{ old('purchase_stage', $project->purchase_stage) == 'no_inquiry' ? 'checked' : '' }}> بدون استعلام</label>
resources/views/projects/edit.blade.php:162:                        <label><input type="radio" name="purchase_stage" value="inquiry" {{ old('purchase_stage', $project->purchase_stage) == 'inquiry' ? 'checked' : '' }}> استعلام</label>
resources/views/projects/edit.blade.php:163:                        <label><input type="radio" name="purchase_stage" value="negotiation" {{ old('purchase_stage', $project->purchase_stage) == 'negotiation' ? 'checked' : '' }}> مذاکره</label>
resources/views/projects/edit.blade.php:164:                        <label><input type="radio" name="purchase_stage" value="purchased" {{ old('purchase_stage', $project->purchase_stage) == 'purchased' ? 'checked' : '' }}> خرید شده</label>
resources/views/projects/create.blade.php:241:                    <div class="flex flex-wrap gap-4 p-3 border rounded-2xl @error('purchase_stage') border-red-500 @enderror">
resources/views/projects/create.blade.php:242:                        <label><input type="radio" name="purchase_stage" value="no_inquiry" {{ old('purchase_stage') == 'no_inquiry' ? 'checked' : '' }}> بدون استعلام</label>
resources/views/projects/create.blade.php:243:                        <label><input type="radio" name="purchase_stage" value="inquiry" {{ old('purchase_stage') == 'inquiry' ? 'checked' : '' }}> استعلام</label>
resources/views/projects/create.blade.php:244:                        <label><input type="radio" name="purchase_stage" value="negotiation" {{ old('purchase_stage') == 'negotiation' ? 'checked' : '' }}> مذاکره</label>
resources/views/projects/create.blade.php:245:                        <label><input type="radio" name="purchase_stage" value="purchased" {{ old('purchase_stage') == 'purchased' ? 'checked' : '' }}> خرید شده</label>
resources/views/projects/create.blade.php:247:                    @error('purchase_stage')
resources/views/projects/create.blade.php:633:        const purchaseStage = document.querySelector('input[name="purchase_stage"]:checked');
resources/views/projects/show.blade.php:427:                        <span class="text-base font-medium">{{ translatePurchaseStage($project->purchase_stage) }}</span>

=== level Analysis ===
--------------------------------
app/Http/Controllers/DashboardController.php:18:        $levelBLeadsCount = 0;
app/Http/Controllers/DashboardController.php:19:        $levelCLeadsCount = 0;
app/Http/Controllers/DashboardController.php:32:                $wonLeadsCount = Project::where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:33:                $levelBLeadsCount = Project::where('project_level', 'B_followup')->count();
app/Http/Controllers/DashboardController.php:34:                $levelCLeadsCount = Project::where('project_level', 'C_archive')->count();
app/Http/Controllers/DashboardController.php:40:                    $won = Project::where('marketer_id', $marketer->id)->where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:62:                $myWonCount = Project::where('marketer_id', $user->id)->where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:66:                $levelBLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'B_followup')->count();
app/Http/Controllers/DashboardController.php:67:                $levelCLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'C_archive')->count();
app/Http/Controllers/DashboardController.php:84:            'levelBLeadsCount',
app/Http/Controllers/DashboardController.php:85:            'levelCLeadsCount',
app/Http/Controllers/ProjectController.php:26:        if ($request->has('level') && $request->level != '') {
app/Http/Controllers/ProjectController.php:27:            $levelMap = [
app/Http/Controllers/ProjectController.php:32:            $query->where('project_level', $levelMap[$request->level] ?? $request->level);
app/Http/Controllers/ProjectController.php:130:            'level'                 => 'required|in:A,B,C',
app/Http/Controllers/ProjectController.php:162:        $levelMap = [
app/Http/Controllers/ProjectController.php:167:        $dbLevel = $levelMap[$validated['level']] ?? 'B_followup';
app/Http/Controllers/ProjectController.php:222:            'project_level'         => $dbLevel,
app/Models/Project.php:60:        'level',
app/Models/Project.php:66:        'project_level',
resources/views/projects/edit.blade.php:276:                <input type="radio" name="level" value="A" {{ old('level') == 'A' ? 'checked' : '' }}> 
resources/views/projects/edit.blade.php:280:                <input type="radio" name="level" value="B" {{ old('level') == 'B' ? 'checked' : '' }}> 
resources/views/projects/edit.blade.php:284:                <input type="radio" name="level" value="C" {{ old('level') == 'C' ? 'checked' : '' }}> 
resources/views/projects/assigned-to-me.blade.php:65:                                $level = $project->project_level ?? 'B_followup';
resources/views/projects/assigned-to-me.blade.php:66:                                $levelMap = [
resources/views/projects/assigned-to-me.blade.php:71:                                $levelInfo = $levelMap[$level] ?? ['bg-gray-100 text-gray-800', $level];
resources/views/projects/assigned-to-me.blade.php:73:                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $levelInfo[0] }}">
resources/views/projects/assigned-to-me.blade.php:74:                                {{ $levelInfo[1] }}
resources/views/projects/create.blade.php:363:                <div class="flex gap-6 p-3 border rounded-2xl @error('level') border-red-500 @enderror">
resources/views/projects/create.blade.php:365:                        <input type="radio" name="level" value="A" {{ old('level') == 'A' ? 'checked' : '' }}> 
resources/views/projects/create.blade.php:370:                        <input type="radio" name="level" value="B" {{ old('level') == 'B' ? 'checked' : '' }}> 
resources/views/projects/create.blade.php:375:                        <input type="radio" name="level" value="C" {{ old('level') == 'C' ? 'checked' : '' }}> 
resources/views/projects/create.blade.php:380:                @error('level')
resources/views/projects/create.blade.php:640:        const level = document.querySelector('input[name="level"]:checked');
resources/views/projects/create.blade.php:641:        if (!level) {
resources/views/projects/index.blade.php:46:                @if(isset($filter['level']))
resources/views/projects/index.blade.php:48:                        سطح: {{ $filter['level'] == 'A' ? '🔥 داغ' : ($filter['level'] == 'B' ? '⏳ پیگیری' : '🗄️ آرشیو') }}
resources/views/projects/index.blade.php:106:                            $level = $project->project_level ?? 'B_followup';
resources/views/projects/index.blade.php:107:                            $levelMap = [
resources/views/projects/index.blade.php:112:                            $levelInfo = $levelMap[$level] ?? ['bg-gray-100 text-gray-800', $level];
resources/views/projects/index.blade.php:114:                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $levelInfo[0] }}">
resources/views/projects/index.blade.php:115:                            {{ $levelInfo[1] }}
resources/views/projects/show.blade.php:17:        function translateLevel($level) {
resources/views/projects/show.blade.php:19:            return $map[$level] ?? $level;
resources/views/projects/show.blade.php:266:                            $levelClass = match($project->level) {
resources/views/projects/show.blade.php:272:                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $levelClass }}">
resources/views/projects/show.blade.php:273:                            {{ translateLevel($project->level) }}
resources/views/dashboard.blade.php:15:            <a href="{{ route('projects.index', ['level' => 'A']) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
resources/views/dashboard.blade.php:21:            <a href="{{ route('projects.index', ['level' => 'B']) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
resources/views/dashboard.blade.php:23:                <div class="text-2xl font-bold text-yellow-600">{{ $levelBLeadsCount ?? 0 }}</div>
resources/views/dashboard.blade.php:27:            <a href="{{ route('projects.index', ['level' => 'C']) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
resources/views/dashboard.blade.php:29:                <div class="text-2xl font-bold text-gray-600">{{ $levelCLeadsCount ?? 0 }}</div>
resources/views/dashboard.blade.php:81:                    <a href="{{ route('projects.index', ['level' => 'A', 'user_id' => auth()->id()]) }}" class="bg-green-50 p-4 rounded-lg hover:bg-green-100 transition cursor-pointer block">

=== project_level Analysis ===
--------------------------------
app/Http/Controllers/DashboardController.php:32:                $wonLeadsCount = Project::where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:33:                $levelBLeadsCount = Project::where('project_level', 'B_followup')->count();
app/Http/Controllers/DashboardController.php:34:                $levelCLeadsCount = Project::where('project_level', 'C_archive')->count();
app/Http/Controllers/DashboardController.php:40:                    $won = Project::where('marketer_id', $marketer->id)->where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:62:                $myWonCount = Project::where('marketer_id', $user->id)->where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:66:                $levelBLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'B_followup')->count();
app/Http/Controllers/DashboardController.php:67:                $levelCLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'C_archive')->count();
app/Http/Controllers/ProjectController.php:32:            $query->where('project_level', $levelMap[$request->level] ?? $request->level);
app/Http/Controllers/ProjectController.php:222:            'project_level'         => $dbLevel,
app/Models/Project.php:66:        'project_level',
resources/views/projects/assigned-to-me.blade.php:65:                                $level = $project->project_level ?? 'B_followup';
resources/views/projects/index.blade.php:106:                            $level = $project->project_level ?? 'B_followup';

=== Database Schema ===
--------------------------------

                                         
  The "--schema" option does not exist.  
                                         

Use manual check:
database/migrations/2026_07_01_145937_create_projects_table.php-            $table->boolean('cooling_tower_selected')->default(false);
database/migrations/2026_07_01_145937_create_projects_table.php-            $table->string('current_cooling_brand')->nullable();
database/migrations/2026_07_01_145937_create_projects_table.php-            $table->decimal('capacity_tr', 10, 2)->nullable();
database/migrations/2026_07_01_145937_create_projects_table.php-            
database/migrations/2026_07_01_145937_create_projects_table.php-            // وضعیت خرید
database/migrations/2026_07_01_145937_create_projects_table.php:            $table->enum('purchase_status', ['no_inquiry', 'inquiry', 'negotiation', 'purchased'])->default('no_inquiry');
database/migrations/2026_07_01_145937_create_projects_table.php-            $table->date('estimated_purchase_date')->nullable();
database/migrations/2026_07_01_145937_create_projects_table.php:            $table->enum('project_level', ['A_hot', 'B_followup', 'C_archive'])->default('B_followup');
database/migrations/2026_07_01_145937_create_projects_table.php-            $table->text('notes')->nullable();
database/migrations/2026_07_01_145937_create_projects_table.php-            
database/migrations/2026_07_01_145937_create_projects_table.php-            // ارتباط با کاربران
database/migrations/2026_07_01_145937_create_projects_table.php-            $table->foreignId('marketer_id')->constrained('users')->onDelete('cascade');
database/migrations/2026_07_01_145937_create_projects_table.php-            

=== Sample Data from Database ===
--------------------------------
   Illuminate\Database\QueryException  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'level' in 'SELECT' (Connection: mysql, SQL: select `id`, `title`, `purchase_status`, `purchase_stage`, `level`, `project_level` from `projects` limit 10).

=== Dashboard & Reports ===
--------------------------------
18:        $levelBLeadsCount = 0;
19:        $levelCLeadsCount = 0;
32:                $wonLeadsCount = Project::where('project_level', 'A_hot')->count();
33:                $levelBLeadsCount = Project::where('project_level', 'B_followup')->count();
34:                $levelCLeadsCount = Project::where('project_level', 'C_archive')->count();
40:                    $won = Project::where('marketer_id', $marketer->id)->where('project_level', 'A_hot')->count();
52:                        Project::where('purchase_status', 'no_inquiry')->count(),
53:                        Project::where('purchase_status', 'inquiry')->count(),
54:                        Project::where('purchase_status', 'negotiation')->count(),
55:                        Project::where('purchase_status', 'purchased')->count(),
62:                $myWonCount = Project::where('marketer_id', $user->id)->where('project_level', 'A_hot')->count();
66:                $levelBLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'B_followup')->count();
67:                $levelCLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'C_archive')->count();
84:            'levelBLeadsCount',
85:            'levelCLeadsCount',

=== Notifications ===
--------------------------------

=== Filters & Queries ===
--------------------------------
app/Http/Controllers/DashboardController.php:32:                $wonLeadsCount = Project::where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:33:                $levelBLeadsCount = Project::where('project_level', 'B_followup')->count();
app/Http/Controllers/DashboardController.php:34:                $levelCLeadsCount = Project::where('project_level', 'C_archive')->count();
app/Http/Controllers/DashboardController.php:40:                    $won = Project::where('marketer_id', $marketer->id)->where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:52:                        Project::where('purchase_status', 'no_inquiry')->count(),
app/Http/Controllers/DashboardController.php:53:                        Project::where('purchase_status', 'inquiry')->count(),
app/Http/Controllers/DashboardController.php:54:                        Project::where('purchase_status', 'negotiation')->count(),
app/Http/Controllers/DashboardController.php:55:                        Project::where('purchase_status', 'purchased')->count(),
app/Http/Controllers/DashboardController.php:62:                $myWonCount = Project::where('marketer_id', $user->id)->where('project_level', 'A_hot')->count();
app/Http/Controllers/DashboardController.php:66:                $levelBLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'B_followup')->count();
app/Http/Controllers/DashboardController.php:67:                $levelCLeadsCount = Project::where('marketer_id', $user->id)->where('project_level', 'C_archive')->count();
app/Http/Controllers/ProjectController.php:32:            $query->where('project_level', $levelMap[$request->level] ?? $request->level);
app/Http/Controllers/ProjectController.php:40:            $query->where('purchase_status', $request->purchase_stage);
app/Http/Controllers/ProjectController.php:54:        $filter = $request->all();
app/Http/Controllers/ProjectController.php:56:        return view('projects.index', compact('projects', 'filter'));
