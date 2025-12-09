@if($papers->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Last Updated</th>
                    <th>Reviews</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($papers as $paper)
                    <tr>
                        <td>
                            <div class="paper-title" title="{{ $paper->title }}">
                                <a href="{{ route('author.papers.show', $paper) }}" class="text-decoration-none">
                                    {{ $paper->title }}
                                </a>
                                @if($paper->doi)
                                    <small class="text-muted d-block">DOI: {{ $paper->doi }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @include('components.status-badge', ['status' => $paper->status])
                            @if($paper->revision_count > 0)
                                <small class="text-muted d-block">Revision {{ $paper->revision_count }}</small>
                            @endif
                        </td>
                        <td>{{ $paper->submitted_at->format('M d, Y') }}</td>
                        <td>{{ $paper->updated_at->format('M d, Y') }}</td>
                        <td>
                            @php
                                $completedReviews = $paper->reviewAssignments()->where('status', 'completed')->count();
                                $totalReviews = $paper->reviewAssignments()->count();
                            @endphp
                            @if($totalReviews > 0)
                                <span class="badge bg-info">{{ $completedReviews }}/{{ $totalReviews }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($paper->status == 'submitted')
                                    <a href="{{ route('author.papers.edit', $paper) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete"
                                            onclick="if(confirm('Are you sure?')) document.getElementById('delete-paper-{{ $paper->id }}').submit()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-paper-{{ $paper->id }}" 
                                          action="{{ route('author.papers.destroy', $paper) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                                
                                @if(in_array($paper->status, ['revision_minor', 'revision_major']))
                                    <a href="{{ route('author.papers.revision', $paper) }}" class="btn btn-outline-warning" title="Submit Revision">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                @endif
                                
                                @if($paper->status == 'published')
                                    <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($papers instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $papers->firstItem() }} to {{ $papers->lastItem() }} of {{ $papers->total() }} papers
            </div>
            {{ $papers->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
        <p class="text-muted">No papers found in this category.</p>
    </div>
@endif