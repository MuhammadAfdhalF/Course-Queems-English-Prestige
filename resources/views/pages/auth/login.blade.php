@extends('layouts.auth')

@section('content')
<section class="flex min-h-screen items-center justify-center px-4 py-10">
    <x-ui.card class="w-full max-w-md space-y-6">
        <div>
            <h1 class="text-3xl font-bold">Login</h1>
            <p class="mt-2 text-slate-600">Sign in to continue learning.</p>
        </div>

        <x-ui.alert variant="info">
            This is a demo auth form for Queens English Prestige.
        </x-ui.alert>

        <div class="space-y-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <x-ui.input type="email" placeholder="Enter your email" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <x-ui.input type="password" placeholder="Enter your password" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Role</label>
                <x-ui.select>
                    <option>Select role</option>
                    <option>Student</option>
                    <option>Admin</option>
                </x-ui.select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                <x-ui.textarea rows="4" placeholder="Write your message"></x-ui.textarea>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <x-ui.badge variant="info">Student Area</x-ui.badge>
            <x-ui.button>Login</x-ui.button>
        </div>
    </x-ui.card>
</section>
@endsection