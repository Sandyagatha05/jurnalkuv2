@extends('layouts.app')

@section('title', 'Author Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fas fa-user-edit"></i> {{ __('Author Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Welcome, Author!</h3>
                <p class="mb-4">Submit and track your papers through the review process.</p>
                
                @php
                    $user = auth()->user();
                    $papers = $user->papers;
                    
                    $stats = [
                        'submitted' => $papers->where('status', 'submitted')->count(),
                        'under_review' => $papers->where('status', 'under_review')->count(),
                        'accepted' => $papers->where('status', 'accepted')->count(),
                        'published' => $papers->where('status', 'published')->count(),
                    ];
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-100 p-4 rounded text-center">
                        <h4 class="font-bold">Submitted</h4>
                        <p class="text-3xl font-bold mt-2">{{ $stats['submitted'] }}</p>
                    </div>
                    
                    <div class="bg-yellow-100 p-4 rounded text-center">
                        <h4 class="font-bold">Under Review</h4>
                        <p class="text-3xl font-bold mt-2">{{ $stats['under_review'] }}</p>
                    </div>
                    
                    <div class="bg-green-100 p-4 rounded text-center">
                        <h4 class="font-bold">Accepted</h4>
                        <p class="text-3xl font-bold mt-2">{{ $stats['accepted'] }}</p>
                    </div>
                    
                    <div class="bg-purple-100 p-4 rounded text-center">
                        <h4 class="font-bold">Published</h4>
                        <p class="text-3xl font-bold mt-2">{{ $stats['published'] }}</p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h4 class="font-bold mb-4">Author Actions</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('author.papers.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded text-center">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <p>Submit New Paper</p>
                        </a>
                        
                        <a href="{{ route('author.papers.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded text-center">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <p>My Submissions</p>
                        </a>
                    </div>
                </div>
                
                @if($papers->count() > 0)
                <div class="mt-8">
                    <h4 class="font-bold mb-4">Recent Submissions</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Title</th>
                                    <th class="py-2 px-4 border-b">Status</th>
                                    <th class="py-2 px-4 border-b">Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($papers->take(5) as $paper)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ Str::limit($paper->title, 50) }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <span class="px-2 py-1 rounded text-xs 
                                            {{ $paper->status == 'published' ? 'bg-green-200 text-green-800' : 
                                               ($paper->status == 'accepted' ? 'bg-blue-200 text-blue-800' :
                                               ($paper->status == 'under_review' ? 'bg-yellow-200 text-yellow-800' :
                                               'bg-gray-200 text-gray-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border-b">{{ $paper->submitted_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection