@extends('admin.layouts.sidebar')

@section('title', 'Questions')
@section('topbar-title', 'Product Questions & Answers')

@push('styles')
<style>
    .filters{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end}
    .filter-group{display:flex;flex-direction:column;gap:4px}
    .filter-label{font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px}
    .filter-input,.filter-select{border:2px solid var(--border);border-radius:8px;padding:7px 11px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;background:#fff;color:#1a1a2e;min-width:220px}
    .filter-btn{background:var(--ocean);color:#fff;border:none;padding:8px 18px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700}
    .filter-reset{font-size:.82rem;color:var(--tide);text-decoration:none;font-weight:600;align-self:center}
    .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden}
    .table{width:100%;border-collapse:collapse}
    .table th{text-align:left;padding:12px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);background:#f8fbfc}
    .table td{padding:14px 16px;font-size:.88rem;border-bottom:1px solid var(--foam);vertical-align:top}
    .table tr:last-child td{border-bottom:none}
    .qa-product{font-weight:700;font-size:.95rem;}
    .qa-text{color:#334155;line-height:1.6;max-width:460px;word-wrap:break-word;}
    .qa-meta{display:flex;flex-wrap:wrap;gap:10px;color:var(--muted);font-size:.82rem}
    .qa-question{background:#fffbeb;border-left:3px solid var(--gold);padding:8px;border-radius:4px;margin-bottom:6px}
    .qa-answer{background:#dcfce7;border-left:3px solid var(--fresh);padding:8px;border-radius:4px}
    .qa-unanswered{background:#fee2e2;border-left:3px solid var(--coral);padding:8px;border-radius:4px}
    
    /* Modal */
    .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
    .modal.active{display:flex}
    .modal-content{background:#fff;border-radius:16px;padding:28px;max-width:600px;width:90%;max-height:80vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.3)}
    .modal-header{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--ocean);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
    .modal-close{float:right;font-size:1.5rem;color:var(--muted);cursor:pointer;border:none;background:none;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center}
    .modal-close:hover{color:var(--ocean)}
    .form-group{margin-bottom:16px}
    .form-label{display:block;font-size:.82rem;font-weight:700;color:var(--ocean);margin-bottom:6px}
    .form-textarea{width:100%;border:2px solid var(--border);border-radius:8px;padding:10px 14px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;color:#1a1a2e;resize:vertical;min-height:140px}
    .form-textarea:focus{border-color:var(--tide)}
    .form-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)}
    .btn-submit{background:var(--fresh);color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700;transition:.2s}
    .btn-submit:hover{opacity:.9}
    .btn-cancel{background:var(--foam);color:var(--ocean);border:1px solid var(--border);padding:10px 20px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700;transition:.2s}
    .btn-cancel:hover{background:var(--border)}
    .answer-btn{background:var(--tide);color:#fff;border:none;padding:6px 12px;border-radius:6px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.8rem;font-weight:700}
    .answer-btn:hover{opacity:.9}
</style>
@endpush

@section('content')

@if($message = session('success'))
    <div style="background:#d4f7e7;border:1px solid #1a7a4a;color:#1a7a4a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500">{{ $message }}</div>
@endif

<form method="GET" action="{{ route('admin.questions') }}">
    <div class="filters">
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="filter-input" placeholder="Product, consumer, question, answer…" value="{{ request('search') }}">
        </div>
        <button type="submit" class="filter-btn">Filter</button>
        @if(request()->filled('search'))
            <a href="{{ route('admin.questions') }}" class="filter-reset">✕ Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Consumer</th>
                <th>Question</th>
                <th>Answer Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($questions as $question)
                <tr>
                    <td>
                        <div class="qa-product">{{ $question->product->name ?? 'Deleted product' }}</div>
                        <div class="qa-meta">Producer: {{ $question->product->producer->name ?? 'Unknown' }}</div>
                    </td>
                    <td>
                        <div>{{ $question->consumer->name ?? 'Unknown' }}</div>
                        <div class="qa-meta">{{ $question->consumer->email ?? '—' }}</div>
                    </td>
                    <td>
                        <div class="qa-question">
                            <strong>Q:</strong> {{ $question->question }}
                        </div>
                        @if($question->answer)
                            <div class="qa-answer">
                                <strong>A:</strong> {{ $question->answer }}
                            </div>
                            <div class="qa-meta">Answered by: {{ $question->producer->name ?? 'Unknown' }}</div>
                        @else
                            <div class="qa-unanswered">
                                <em>No answer yet</em>
                            </div>
                        @endif
                    </td>
                    <td style="text-align:center;white-space:nowrap">
                        @if($question->answer)
                            <span style="color:var(--fresh);font-weight:700">✓ Answered</span>
                        @else
                            <span style="color:var(--coral);font-weight:700">✕ Pending</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;text-align:center">
                        @if(!$question->answer)
                            <button type="button" class="answer-btn" onclick="openAnswerModal({{ $question->id }}, '{{ addslashes($question->question) }}')">
                                Answer
                            </button>
                        @else
                            <button type="button" class="answer-btn" style="background:var(--muted)" onclick="openAnswerModal({{ $question->id }}, '{{ addslashes($question->question) }}')">
                                Edit
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:32px;color:var(--muted)">No questions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($questions->hasPages())
        <div class="pagination-wrap" style="padding:16px 20px;border-top:1px solid var(--border)">
            {{ $questions->links() }}
        </div>
    @endif
</div>

<!-- Answer Modal -->
<div class="modal" id="answerModal">
    <div class="modal-content">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="modal-header">Answer Question</div>
            <button type="button" class="modal-close" onclick="closeAnswerModal()">✕</button>
        </div>

        <div style="margin-bottom:16px;color:var(--muted);font-size:.85rem">
            <strong>Question:</strong> <span id="questionText"></span>
        </div>

        <form method="POST" id="answerForm">
            @csrf

            <div class="form-group">
                <label class="form-label">Your Answer</label>
                <textarea 
                    class="form-textarea"
                    name="answer"
                    placeholder="Type your answer here... (minimum 5 characters)"
                    required
                ></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeAnswerModal()">Cancel</button>
                <button type="submit" class="btn-submit">Post Answer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAnswerModal(questionId, questionText) {
        document.getElementById('questionText').textContent = questionText;
        document.getElementById('answerForm').action = '{{ url("/admin/questions") }}/' + questionId + '/answer';
        document.getElementById('answerForm').reset();
        document.getElementById('answerModal').classList.add('active');
    }

    function closeAnswerModal() {
        document.getElementById('answerModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.getElementById('answerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAnswerModal();
        }
    });
</script>

@endsection
