<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\VisionsMission;
use Illuminate\Http\Request;

class VisionsMissionController extends Controller
{
    public function index()
    {
        $visionMission = VisionsMission::query()
            ->with('missionItems')
            ->first();

        return view('pages.admin.cms.about.vision-mission.index', compact('visionMission'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'vision' => ['nullable', 'string'],
            'missions' => ['nullable', 'array'],
            'missions.*' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $visionMission = VisionsMission::query()->first();

        if (! $visionMission) {
            $visionMission = VisionsMission::create([
                'vision' => $validated['vision'] ?? null,
                'mission' => null,
                'is_active' => $request->boolean('is_active'),
            ]);
        } else {
            $visionMission->update([
                'vision' => $validated['vision'] ?? null,
                'mission' => null,
                'is_active' => $request->boolean('is_active'),
            ]);
        }

        $missionItems = collect($validated['missions'] ?? [])
            ->filter(fn($mission) => filled($mission))
            ->values();

        $visionMission->missionItems()->delete();

        foreach ($missionItems as $index => $mission) {
            $visionMission->missionItems()->create([
                'type' => 'mission',
                'content' => $mission,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('admin.cms.vision-mission.index')
            ->with('success', 'Vision & Mission has been saved successfully.');
    }
}
