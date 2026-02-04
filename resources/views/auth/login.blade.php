@extends('admin.layout')

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Login</h1>

        @if($errors->any())
            <div class="mb-4 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="block text-sm">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 border" />
            </div>
            <div class="mb-3">
                <label class="block text-sm">Password</label>
                <input type="password" name="password" required class="w-full p-2 border" />
            </div>
            <div class="mb-3">
                <label class="inline-flex items-center text-sm">
                    <input type="checkbox" name="remember" class="mr-2" /> Remember me
                </label>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-black text-white">Login</button>
                <a href="/" class="ml-3 text-sm">Cancel</a>
            </div>
        </form>
    </div>
@endsection
