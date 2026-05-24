@extends('layouts.auth')

@section('content')
<section class="min-h-screen bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-7xl overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_20px_70px_rgba(15,23,42,0.12)]">
        <div class="grid lg:grid-cols-[0.8fr_1.2fr]">
            @include('partials.auth.register-brand-panel')

            @include('partials.auth.register-form')
        </div>
    </div>
</section>
@endsection