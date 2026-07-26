<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use App\Models\Module;
use App\Models\ModuleMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ModuleMaterialController extends Controller
{
    public function index(Module $module)
    {
        $module->load('courseLevel.courseProgram');

        $materials = $module->materials()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.materials.index', compact(
            'module',
            'materials'
        ));
    }

    public function preview(Module $module)
    {
        $module->load('courseLevel.courseProgram');

        $materials = $module->materials()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.materials.preview', compact(
            'module',
            'materials'
        ));
    }

    public function create(Module $module)
    {
        $module->load('courseLevel.courseProgram');

        $nextSortOrder = ((int) $module->materials()->max('sort_order')) + 1;

        return view('pages.admin.course-management.materials.create', compact(
            'module',
            'nextSortOrder'
        ));
    }

    // ==========================================
    // SCOPED BUILDER AJAX ENDPOINTS (IRONCLAD SECURITY)
    // ==========================================

    public function builderStore(Request $request, CourseProgram $courseProgram, Module $module)
    {
        $module->load('courseLevel');

        if ($module->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: module does not belong to this course program.'
            ], 403);
        }

        $validated = $this->validateMaterial($request);

        $validated['module_id'] = $module->id;
        $validated['sort_order'] = $validated['sort_order'] ?? (((int) $module->materials()->max('sort_order')) + 1);
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['material_type'] === 'text') {
            $validated['file_path'] = null;
        } else {
            $validated['content'] = null;
            $validated['file_path'] = $this->storeUploadedFile($request, $validated['material_type']);
        }

        $material = ModuleMaterial::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Module material has been created successfully.',
            'material_id' => $material->id,
            'redirect_node' => [
                'level' => $module->course_level_id,
                'module' => $module->id,
                'exam' => null,
                'tab' => 'materials'
            ]
        ]);
    }

    public function builderEdit(Request $request, CourseProgram $courseProgram, ModuleMaterial $moduleMaterial)
    {
        $moduleMaterial->load('module.courseLevel');

        if ($moduleMaterial->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: material does not belong to this course program.'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $moduleMaterial->id,
                'module_id' => $moduleMaterial->module_id,
                'title' => $moduleMaterial->title,
                'material_type' => $moduleMaterial->material_type,
                'content' => $moduleMaterial->content,
                'file_path' => $moduleMaterial->file_path,
                'file_url' => $moduleMaterial->file_path ? Storage::url($moduleMaterial->file_path) : null,
                'sort_order' => $moduleMaterial->sort_order,
                'is_active' => (bool) $moduleMaterial->is_active,
                'update_url' => route('admin.course-management.programs.builder.materials.update', ['courseProgram' => $courseProgram->id, 'moduleMaterial' => $moduleMaterial->id]),
            ]
        ]);
    }

    public function builderUpdate(Request $request, CourseProgram $courseProgram, ModuleMaterial $moduleMaterial)
    {
        $moduleMaterial->load('module.courseLevel');

        if ($moduleMaterial->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: material does not belong to this course program.'
            ], 403);
        }

        $validated = $this->validateMaterial($request, $moduleMaterial);

        $validated['sort_order'] = $validated['sort_order'] ?? $moduleMaterial->sort_order;
        $validated['is_active'] = $request->boolean('is_active');

        $oldFilePath = $moduleMaterial->file_path;
        $newFilePath = null;

        if ($validated['material_type'] === 'text') {
            $validated['file_path'] = null;
        } else {
            $validated['content'] = null;
            if ($request->hasFile('file_path')) {
                $newFilePath = $this->storeUploadedFile($request, $validated['material_type']);
                $validated['file_path'] = $newFilePath;
            } else {
                $validated['file_path'] = $oldFilePath;
            }
        }

        try {
            DB::transaction(function () use ($moduleMaterial, $validated) {
                $moduleMaterial->update($validated);
            });

            // Safe File Cleanup: Delete old file ONLY after DB transaction succeeds
            if (($newFilePath || $validated['material_type'] === 'text') && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }
        } catch (\Throwable $e) {
            // Fail-safe: Cleanup newly uploaded file if DB update fails to avoid orphan files
            if ($newFilePath && Storage::disk('public')->exists($newFilePath)) {
                Storage::disk('public')->delete($newFilePath);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Database update failed: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Module material has been updated successfully.',
            'material_id' => $moduleMaterial->id,
            'redirect_node' => [
                'level' => $moduleMaterial->module->course_level_id,
                'module' => $moduleMaterial->module_id,
                'exam' => null,
                'tab' => 'materials'
            ]
        ]);
    }

    public function builderDestroy(Request $request, CourseProgram $courseProgram, ModuleMaterial $moduleMaterial)
    {
        $moduleMaterial->load('module.courseLevel');

        if ($moduleMaterial->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: material does not belong to this course program.'
            ], 403);
        }

        $module = $moduleMaterial->module;

        try {
            DB::transaction(function () use ($moduleMaterial) {
                $moduleMaterial->delete();
            });

            if ($moduleMaterial->file_path && Storage::disk('public')->exists($moduleMaterial->file_path)) {
                Storage::disk('public')->delete($moduleMaterial->file_path);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete material: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Module material has been deleted successfully.',
            'redirect_node' => [
                'level' => $module->course_level_id,
                'module' => $module->id,
                'exam' => null,
                'tab' => 'materials'
            ]
        ]);
    }

    // ==========================================
    // LEGACY FULL-PAGE ENDPOINTS (BACKWARD COMPATIBILITY)
    // ==========================================

    public function store(Request $request, Module $module)
    {
        $validated = $this->validateMaterial($request);

        $validated['module_id'] = $module->id;
        $validated['sort_order'] = $validated['sort_order'] ?? (((int) $module->materials()->max('sort_order')) + 1);
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['material_type'] === 'text') {
            $validated['file_path'] = null;
        } else {
            $validated['content'] = null;
            $validated['file_path'] = $this->storeUploadedFile($request, $validated['material_type']);
        }

        ModuleMaterial::create($validated);

        return redirect()
            ->route('admin.course-management.modules.materials.index', $module)
            ->with('success', 'Module material has been created successfully.');
    }

    public function edit(ModuleMaterial $moduleMaterial)
    {
        $moduleMaterial->load('module.courseLevel.courseProgram');

        return view('pages.admin.course-management.materials.edit', compact('moduleMaterial'));
    }

    public function update(Request $request, ModuleMaterial $moduleMaterial)
    {
        $validated = $this->validateMaterial($request, $moduleMaterial);

        $validated['sort_order'] = $validated['sort_order'] ?? $moduleMaterial->sort_order;
        $validated['is_active'] = $request->boolean('is_active');

        $oldFilePath = $moduleMaterial->file_path;
        $newFilePath = null;

        if ($validated['material_type'] === 'text') {
            $validated['file_path'] = null;
        } else {
            $validated['content'] = null;
            if ($request->hasFile('file_path')) {
                $newFilePath = $this->storeUploadedFile($request, $validated['material_type']);
                $validated['file_path'] = $newFilePath;
            } else {
                $validated['file_path'] = $oldFilePath;
            }
        }

        DB::transaction(function () use ($moduleMaterial, $validated) {
            $moduleMaterial->update($validated);
        });

        if (($newFilePath || $validated['material_type'] === 'text') && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            Storage::disk('public')->delete($oldFilePath);
        }

        return redirect()
            ->route('admin.course-management.modules.materials.index', $moduleMaterial->module)
            ->with('success', 'Module material has been updated successfully.');
    }

    public function destroy(ModuleMaterial $moduleMaterial)
    {
        $module = $moduleMaterial->module;

        if ($moduleMaterial->file_path && Storage::disk('public')->exists($moduleMaterial->file_path)) {
            Storage::disk('public')->delete($moduleMaterial->file_path);
        }

        $moduleMaterial->delete();

        return redirect()
            ->route('admin.course-management.modules.materials.index', $module)
            ->with('success', 'Module material has been deleted successfully.');
    }

    private function validateMaterial(Request $request, ?ModuleMaterial $moduleMaterial = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'material_type' => ['required', 'in:text,image,video,audio,pdf,file'],
            'content' => ['nullable', 'string'],
            'file_path' => ['nullable', 'file'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['material_type'] === 'text') {
            $request->validate([
                'content' => ['required', 'string'],
            ]);

            return $validated;
        }

        $fileRequiredRule = $moduleMaterial && $moduleMaterial->file_path
            ? 'nullable'
            : 'required';

        match ($validated['material_type']) {
            'image' => $request->validate([
                'file_path' => [$fileRequiredRule, 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]),
            'video' => $request->validate([
                'file_path' => [$fileRequiredRule, 'file', 'mimes:mp4,webm,mov', 'max:51200'],
            ]),
            'audio' => $request->validate([
                'file_path' => [$fileRequiredRule, 'file', 'mimes:mp3,wav,m4a', 'max:20480'],
            ]),
            'pdf' => $request->validate([
                'file_path' => [$fileRequiredRule, 'file', 'mimes:pdf', 'max:20480'],
            ]),
            'file' => $request->validate([
                'file_path' => [$fileRequiredRule, 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar', 'max:51200'],
            ]),
            default => null,
        };

        return $validated;
    }

    private function storeUploadedFile(Request $request, string $materialType): ?string
    {
        if (! $request->hasFile('file_path')) {
            return null;
        }

        $folder = match ($materialType) {
            'image' => 'module-materials/images',
            'video' => 'module-materials/videos',
            'audio' => 'module-materials/audios',
            'pdf' => 'module-materials/pdfs',
            default => 'module-materials/files',
        };

        return $request->file('file_path')->store($folder, 'public');
    }

    public function builderReorder(Request $request, CourseProgram $courseProgram, Module $module)
    {
        $module->load('courseLevel');

        if ($module->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: module does not belong to this program.'
            ], 403);
        }

        $validated = $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['required', 'integer'],
            'original_ordered_ids' => ['nullable', 'array'],
            'original_ordered_ids.*' => ['integer'],
        ]);

        $orderedIds = array_map('intval', $validated['ordered_ids']);
        $originalOrderedIds = isset($validated['original_ordered_ids']) ? array_map('intval', $validated['original_ordered_ids']) : null;

        try {
            return DB::transaction(function () use ($module, $orderedIds, $originalOrderedIds) {
                // 1. Explicitly execute parent SQL query lock
                $lockedModule = Module::whereKey($module->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2. Lock & read current server siblings
                $siblings = $lockedModule->materials()
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get(['id', 'sort_order']);

                $currentServerOrderedIds = $siblings->pluck('id')->values()->all();

                $existingIdsCopy = $currentServerOrderedIds;
                $orderedIdsCopy = $orderedIds;
                sort($existingIdsCopy);
                sort($orderedIdsCopy);

                if (count($orderedIds) !== count($currentServerOrderedIds) || $existingIdsCopy !== $orderedIdsCopy || count(array_unique($orderedIds)) !== count($orderedIds)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The item list has changed. Reload the latest order and try again.'
                    ], 422);
                }

                if ($originalOrderedIds !== null && $currentServerOrderedIds !== $originalOrderedIds) {
                    return response()->json([
                        'status' => 'conflict',
                        'message' => 'The order has been changed by another administrator. Reload the latest order and try again.'
                    ], 409);
                }

                foreach ($orderedIds as $index => $id) {
                    ModuleMaterial::where('id', $id)->update(['sort_order' => $index + 1]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Materials order updated successfully.'
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->isConcurrencyException($e)) {
                return response()->json([
                    'status' => 'conflict',
                    'message' => 'A database concurrency conflict occurred. Reload the latest order and try again.'
                ], 409);
            }
            \Illuminate\Support\Facades\Log::error('Material reorder query error: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    protected function isConcurrencyException(\Illuminate\Database\QueryException $e): bool
    {
        $sqlState = (string) $e->getCode();
        $driverCode = $e->errorInfo[1] ?? null;

        return $sqlState === '40001' || $driverCode === 1213 || $driverCode === 1205;
    }
}
