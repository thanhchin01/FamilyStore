@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Users</h1>

    @if(isset($users) && $users->count())
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr>
                    <th class="text-left p-2">ID</th>
                    <th class="text-left p-2">Name</th>
                    <th class="text-left p-2">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="p-2">{{ $user->id }}</td>
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @else
        <p>No users found.</p>
    @endif

@endsection
