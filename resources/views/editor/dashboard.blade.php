@extends('layouts.app')

@section('title', 'Editor Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fas fa-edit"></i> {{ __('Editor Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Welcome, Editor!</h3>
                <p class="mb-4">Manage journal issues, papers, and review process.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-100 p-4 rounded">
                        <h4 class="font-bold">Papers Needing Attention</h4>
                        <ul class="mt-2">
                            <li>Submitted: {{ \App\Models\Paper::where('status', 'submitted')->count() }}</li>
                            <li>Under Review: {{ \App\Models\Paper::where('status', 'under_review')->count() }}</li>
                            <li>Needs Revision: {{ \App\Models\Paper::whereIn('status', ['revision_minor', 'revision_major'])->count() }}</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-100 p-4 rounded">
                        <h4 class="font-bold">Issue Management</h4>
                        <ul class="mt-2">
                            <li>Active Issues: {{ \App\Models\Issue::where('status', 'published')->count() }}</li>
                            <li>Draft Issues: {{ \App\Models\Issue::where('status', 'draft')->count() }}</li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h4 class="font-bold mb-4">Editor Actions</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('editor.papers.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded text-center">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <p>Manage Papers</p>
                        </a>
                        
                        <a href="{{ route('editor.issues.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded text-center">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <p>Create Issue</p>
                        </a>
                        
                        <a href="{{ route('editor.reviews.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <p>Review Management</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection