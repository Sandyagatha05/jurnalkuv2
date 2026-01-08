@if($papers->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Last Updated</th>
                    <th>Reviews</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($papers as $paper)
                    <tr class="hover-shadow">
                        <td class="paper-title" title="{{ $paper->title }}">
                            <a href="{{ route('author.papers.show', $paper) }}" class="text-decoration-none fw-semibold text-primary">
                                {{ Str::limit($paper->title, 60) }}
                            </a>
                            @if($paper->doi)
                                <small class="text-muted d-block">DOI: {{ $paper->doi }}</small>
                            @endif
                        </td>

                        <td>
                            @include('components.status-badge', ['status' => $paper->status])
                            @if($paper->revision_count > 0)
                                <small class="text-muted d-block">Revision {{ $paper->revision_count }}</small>
                            @endif
                        </td>

                        <td>{{ optional($paper->submitted_at)->format('M d, Y') ?? '-' }}</td>
                        <td>{{ $paper->updated_at->format('M d, Y') }}</td>

                        <td>
                            @php
                                $completed = $paper->reviewAssignments()->where('status', 'completed')->count();
                                $total = $paper->reviewAssignments()->count();
                            @endphp

                            @if($total > 0)
                                <span class="badge bg-info">{{ $completed }}/{{ $total }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($paper->status === 'submitted')
                                    <a href="{{ route('author.papers.edit', $paper) }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Delete"
                                            onclick="event.preventDefault();
                                            customConfirm('Are you sure you want to delete?<br>This action can not be undone.').then(result => { 
                                            if(result) document.getElementById('delete-paper-{{ $paper->id }}').submit(); });">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <form id="delete-paper-{{ $paper->id }}" action="{{ route('author.papers.destroy', $paper) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif

                                @if(in_array($paper->status, ['revision_minor', 'revision_major']))
                                    <a href="{{ route('author.papers.revision', $paper) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Submit Revision">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                @endif

                                @if($paper->status === 'published')
                                    <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Download">
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

    {{-- ========================= --}}
    {{-- CUSTOM PAGINATION --}}
    {{-- ========================= --}}
    @if($papers instanceof \Illuminate\Pagination\LengthAwarePaginator && $papers->lastPage() > 1)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $papers->firstItem() }}–{{ $papers->lastItem() }} of {{ $papers->total() }} papers
            </div>

            <nav>
                <ul class="pagination mb-0">
                    {{-- Previous --}}
                    <li class="page-item {{ $papers->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $papers->previousPageUrl() ?? '#' }}" aria-label="Previous">&laquo;</a>
                    </li>

                    {{-- Page Numbers --}}
                    @foreach($papers->getUrlRange(1, $papers->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $papers->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next --}}
                    <li class="page-item {{ $papers->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $papers->nextPageUrl() ?? '#' }}" aria-label="Next">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    @endif

@else
    <div class="text-center py-5">
        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-0">No papers found in this category.</p>
    </div>
@endif

@push('styles')
<style>
.hover-shadow:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.paper-title a:hover {
    text-decoration: underline;
    color: #4361ee;
    transition: all 0.2s ease;
}
.btn-group .btn {
    transition: all 0.2s ease;
}
.btn-group .btn:hover {
    transform: translateY(-2px);
}
.pagination .page-link {
    color: #4361ee;
    font-weight: 500;
    border-radius: 6px;
}
.pagination .page-item.active .page-link {
    background-color: #4361ee;
    color: #fff;
    border-color: #4361ee;
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
