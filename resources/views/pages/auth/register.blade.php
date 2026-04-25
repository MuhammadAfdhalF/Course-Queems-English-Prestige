@extends('layouts.auth')

@section('content')
<section class="flex min-h-[calc(100vh-56px)] items-center justify-center px-4 py-6 lg:px-8 lg:py-10">
    <div class="w-full max-w-[1440px] overflow-hidden rounded-[34px] border border-slate-400 bg-[#f8f8f6] shadow-sm">
        <div class="grid min-h-[860px] lg:grid-cols-[0.76fr_1.24fr]">
            <div class="reveal">
                @include('partials.auth.register-brand-panel')
            </div>

            <div class="reveal reveal-delay-1">
                @include('partials.auth.register-form')
            </div>
        </div>
    </div>
</section>
@endsection