<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleMaterial;
use Illuminate\Http\Request;
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

    public function store(Request $request, Module $module)
    {
        $validated = $this->validateMaterial($request);

        $validated['module_id'] = $module->id;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
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

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['material_type'] === 'text') {
            if ($moduleMaterial->file_path) {
                Storage::disk('public')->delete($moduleMaterial->file_path);
            }

            $validated['file_path'] = null;
        } else {
            $validated['content'] = null;

            if ($request->hasFile('file_path')) {
                if ($moduleMaterial->file_path) {
                    Storage::disk('public')->delete($moduleMaterial->file_path);
                }

                $validated['file_path'] = $this->storeUploadedFile($request, $validated['material_type']);
            } else {
                $validated['file_path'] = $moduleMaterial->file_path;
            }
        }

        $moduleMaterial->update($validated);

        return redirect()
            ->route('admin.course-management.modules.materials.index', $moduleMaterial->module)
            ->with('success', 'Module material has been updated successfully.');
    }

    public function destroy(ModuleMaterial $moduleMaterial)
    {
        $module = $moduleMaterial->module;

        if ($moduleMaterial->file_path) {
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
}
