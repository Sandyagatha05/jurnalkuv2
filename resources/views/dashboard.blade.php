@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Welcome to Jurnalku</h2>
                <p class="mb-4">Please use the navigation menu to access your role-specific dashboard.</p>
                
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection