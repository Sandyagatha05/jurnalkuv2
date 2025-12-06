@extends('layouts.app')

@section('title', 'Reviewer Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fas fa-search"></i> {{ __('Reviewer Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Welcome, Reviewer!</h3>
                <p class="mb-4">Review assigned papers and contribute to the peer-review process.</p>
                
                @php
                    $user = auth()->user();
                    $pendingAssignments = $user->reviewAssignments()->where('status', 'pending')->count();
                    $completedAssignments = $user->reviewAssignments()->where('status', 'completed')->count();
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-100 p-4 rounded">
                        <h4 class="font-bold">Pending Reviews</h4>
                        <p class="text-3xl font-bold mt-2">{{ $pendingAssignments }}</p>
                        <p class="text-sm">Papers waiting for your review</p>
                    </div>
                    
                    <div class="bg-green-100 p-4 rounded">
                        <h4 class="font-bold">Completed Reviews</h4>
                        <p class="text-3xl font-bold mt-2">{{ $completedAssignments }}</p>
                        <p class="text-sm">Papers you have reviewed</p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h4 class="font-bold mb-4">Reviewer Actions</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('reviewer.assignments.pending') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded text-center">
                            <i class="fas fa-tasks fa-2x mb-2"></i>
                            <p>Pending Assignments</p>
                        </a>
                        
                        <a href="{{ route('reviewer.assignments.completed') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded text-center">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p>Review History</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection