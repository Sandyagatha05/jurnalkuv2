// In index method:
public function index()
{
    $papers = Paper::where('author_id', Auth::id())
        ->with(['issue', 'reviewAssignments.reviewer'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    return view('author.papers.index', compact('papers'));
}

// In dashboard method (tambahkan di DashboardController atau buat method baru):
public function dashboard()
{
    $user = Auth::user();
    $papers = $user->papers;
    
    $stats = [
        'submitted' => $papers->where('status', 'submitted')->count(),
        'under_review' => $papers->where('status', 'under_review')->count(),
        'accepted' => $papers->where('status', 'accepted')->count(),
        'published' => $papers->where('status', 'published')->count(),
    ];
    
    $recentPapers = $user->papers()
        ->with(['issue', 'reviewAssignments'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    
    return view('author.dashboard', compact('stats', 'recentPapers'));
}