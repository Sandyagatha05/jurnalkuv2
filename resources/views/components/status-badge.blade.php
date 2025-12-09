@props(['status'])

@php
    $statusConfig = [
        'submitted' => ['class' => 'status-submitted', 'label' => 'Submitted'],
        'under_review' => ['class' => 'status-under_review', 'label' => 'Under Review'],
        'accepted' => ['class' => 'status-accepted', 'label' => 'Accepted'],
        'published' => ['class' => 'status-published', 'label' => 'Published'],
        'rejected' => ['class' => 'status-rejected', 'label' => 'Rejected'],
        'revision_minor' => ['class' => 'status-revision_minor', 'label' => 'Minor Revision'],
        'revision_major' => ['class' => 'status-revision_major', 'label' => 'Major Revision'],
        'draft' => ['class' => 'status-submitted', 'label' => 'Draft'],
        'archived' => ['class' => 'status-rejected', 'label' => 'Archived'],
    ];
    
    $config = $statusConfig[$status] ?? ['class' => 'status-submitted', 'label' => $status];
@endphp

<span class="status-badge {{ $config['class'] }}">
    {{ $config['label'] }}
</span>