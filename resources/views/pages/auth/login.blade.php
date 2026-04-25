@extends('layouts.auth')

@section('content')
    <section class="flex min-h-[calc(100vh-56px)] items-center justify-center px-4 py-8 lg:px-8 lg:py-12">
        <div class="w-full max-w-[1280px] overflow-hidden rounded-[34px] border border-slate-400 bg-[#f8f8f6] shadow-sm">
            <div class="grid min-h-[760px] lg:grid-cols-[0.88fr_1.12fr]">
                @include('partials.auth.brand-panel')
                @include('partials.auth.login-form')
            </div>
        </div>
    </section>
@endsection