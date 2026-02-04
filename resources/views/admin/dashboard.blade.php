@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>
    <p>Welcome to the admin dashboard. Tóm tắt nhanh các thông tin ở đây.</p>
    <div class="mt-6">
        <a href="{{ route('admin.users') }}" class="underline">Manage users</a>
    </div>
@endsection
