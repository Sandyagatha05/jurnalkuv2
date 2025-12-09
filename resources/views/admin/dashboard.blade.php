@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fas fa-crown"></i> {{ __('Admin Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Welcome, Admin!</h3>
                <p class="mb-4">You have full access to manage the entire journal system.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-100 p-4 rounded">
                        <h4 class="font-bold">Quick Stats</h4>
                        <ul class="mt-2">
                            <li>Users: {{ \App\Models\User::count() }}</li>
                            <li>Issues: {{ \App\Models\Issue::count() }}</li>
                            <li>Papers: {{ \App\Models\Paper::count() }}</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-100 p-4 rounded">
                        <h4 class="font-bold">System Status</h4>
                        <p class="mt-2">All systems operational</p>
                    </div>
                    
                    <div class="bg-yellow-100 p-4 rounded">
                        <h4 class="font-bold">Recent Activity</h4>
                        <p class="mt-2">No recent issues</p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h4 class="font-bold mb-4">Admin Actions</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>Manage Users</p>
                        </a>
                        
                        <a href="{{ route('admin.roles.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded text-center">
                            <i class="fas fa-user-tag fa-2x mb-2"></i>
                            <p>Manage Roles</p>
                        </a>
                        
                        {{-- <a href="{{ route('admin.system.settings') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded text-center">
                            <i class="fas fa-cog fa-2x mb-2"></i>
                            <p>System Settings</p>
                        </a>
                        
                        <a href="{{ route('admin.reports') }}" class="bg-red-500 hover:bg-red-600 text-white p-4 rounded text-center">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p>View Reports</p>
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection