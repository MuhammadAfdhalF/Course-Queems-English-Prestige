<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakePublicPage extends Command
{
    protected $signature = 'make:public-page {name}';
    protected $description = 'Generate public page blade and its partial sections';

    public function handle()
    {
        $name = $this->argument('name');

        $slug = Str::kebab($name);
        $viewName = str_replace('-', '_', $slug);

        $pagePath = resource_path("views/pages/public/{$slug}.blade.php");
        $partialDir = resource_path("views/partials/public/{$slug}");

        $partials = [
            'hero',
            'highlights',
            'why-take-test',
            'how-it-works',
            'categories',
            'cta',
        ];

        if (!File::exists(dirname($pagePath))) {
            File::makeDirectory(dirname($pagePath), 0755, true);
        }

        if (!File::exists($partialDir)) {
            File::makeDirectory($partialDir, 0755, true);
        }

        if (!File::exists($pagePath)) {
            $pageContent = $this->buildPageContent($slug, $partials);
            File::put($pagePath, $pageContent);
            $this->info("Created page: resources/views/pages/public/{$slug}.blade.php");
        } else {
            $this->warn("Page already exists: resources/views/pages/public/{$slug}.blade.php");
        }

        foreach ($partials as $partial) {
            $partialPath = "{$partialDir}/{$partial}.blade.php";

            if (!File::exists($partialPath)) {
                File::put($partialPath, $this->buildPartialContent($partial));
                $this->info("Created partial: resources/views/partials/public/{$slug}/{$partial}.blade.php");
            } else {
                $this->warn("Partial already exists: resources/views/partials/public/{$slug}/{$partial}.blade.php");
            }
        }

        $this->newLine();
        $this->info("Public page '{$slug}' generated successfully.");
        return Command::SUCCESS;
    }

    protected function buildPageContent(string $slug, array $partials): string
    {
        $includes = collect($partials)
            ->map(fn ($partial) => "    @include('partials.public.{$slug}.{$partial}')")
            ->implode("\n");

        return <<<BLADE
@extends('layouts.app')

@section('content')
{$includes}
@endsection

BLADE;
    }

    protected function buildPartialContent(string $partial): string
    {
        $title = Str::title(str_replace('-', ' ', $partial));

        return <<<BLADE
<section class="{$partial}">
    <div class="container">
        <h2>{$title}</h2>
    </div>
</section>

BLADE;
    }
}