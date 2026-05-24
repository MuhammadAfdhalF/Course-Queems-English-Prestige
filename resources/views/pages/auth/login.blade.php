@extends('layouts.auth')

@section('content')
<section class="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">
    <div class="w-full max-w-6xl overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_20px_70px_rgba(15,23,42,0.12)]">
        <div class="grid lg:grid-cols-[0.95fr_1.05fr]">
            @include('partials.auth.brand-panel')

            @include('partials.auth.login-form')
        </div>
    </div>
</section>
@endsection