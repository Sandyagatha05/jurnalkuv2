@if($assignments->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Paper Title</th>
                    <th>Author</th>
                    <th>Assigned Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assignment)
                    <tr class="hover-shadow {{ $assignment->due_date < now() ? 'table-warning' : '' }}">
                        <td>
                            <div class="assignment-title" title="{{ $assignment->paper->title }}">
                                <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="text-decoration-none fw-semibold text-primary">
                                    {{ Str::limit($assignment->paper->title, 60) }}
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $assignment->paper->author->name }}</div>
                            <small class="text-muted">{{ $assignment->paper->author->institution }}</small>
                        </td>
                        <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                        <td>
                            <div class="due-date">
                                {{ $assignment->due_date->format('M d, Y') }}
                                <br>
                                @if($assignment->due_date < now())
                                    <small class="text-danger fw-semibold">
                                        <i class="fas fa-exclamation-circle me-1"></i> Overdue
                                    </small>
                                @else
                                    <small class="text-muted">
                                        {{ $assignment->due_date->diffForHumans() }}
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $assignment->status == 'pending' ? 'warning' : ($assignment->status == 'accepted' ? 'primary' : 'secondary') }} fw-semibold">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($assignment->status == 'pending')
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Accept Assignment"
                                            onclick="acceptAssignment({{ $assignment->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Decline Assignment"
                                            onclick="declineAssignment({{ $assignment->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif

                                @if($assignment->status == 'accepted' || $assignment->status == 'pending')
                                    <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Submit Review">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif

                                <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Download Paper">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Custom Pagination -->
    @if($assignments instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
            </div>
            <nav>
                <ul class="pagination mb-0">
                    {{-- Previous Page Link --}}
                    <li class="page-item {{ $assignments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $assignments->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    {{-- Pagination Elements --}}
                    @foreach($assignments->getUrlRange(1, $assignments->lastPage()) as $page => $url)
                        <li class="page-item {{ $assignments->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next Page Link --}}
                    <li class="page-item {{ $assignments->currentPage() == $assignments->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $assignments->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
        <p class="text-muted">No assignments found in this category.</p>
    </div>
@endif

@push('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .assignment-title a:hover {
        text-decoration: underline;
        color: #4361ee;
        transition: all 0.2s ease;
    }
    .due-date small {
        font-size: 0.8rem;
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

    function acceptAssignment(assignmentId) {
        if(confirm('Are you sure you want to accept this review assignment?')) {
            fetch(`/reviewer/assignments/${assignmentId}/accept`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(res => res.json()).then(data => {
                if(data.success) location.reload();
            }).catch(err => console.error(err));
        }
    }

    function declineAssignment(assignmentId) {
        if(confirm('Are you sure you want to decline this review assignment?')) {
            fetch(`/reviewer/assignments/${assignmentId}/decline`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(res => res.json()).then(data => {
                if(data.success) location.reload();
            }).catch(err => console.error(err));
        }
    }
</script>
@endpush
