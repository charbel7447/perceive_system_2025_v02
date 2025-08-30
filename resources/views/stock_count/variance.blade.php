<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Variance Report – Stock Count #{{ $stockCount->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background: #f9fafb;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .btn {
            border-radius: 8px;
        }
        table th {
            font-weight: 600;
            white-space: nowrap;
        }
        table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            Variance Report – Stock Count #{{ $stockCount->id }}
            <small class="text-muted fs-6">
                ({{ \Illuminate\Support\Carbon::parse($stockCount->count_date)->toDateString() }})
            </small>
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('stock_count.show', $stockCount->id) }}" class="btn btn-outline-secondary btn-sm">Back to Scan</a>
            <a href="{{ route('stock_count.variance.export', $stockCount->id) }}" class="btn btn-outline-primary btn-sm">Export CSV</a>
        </div>
    </div>

    {{-- Quick Filters --}}
    <form method="GET" action="{{ route('stock_count.variance', $stockCount->id) }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="code" value="{{ request('code') }}" class="form-control" placeholder="Filter by code">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Status: All</option>
                    <option value="match" {{ request('status')=='match'?'selected':'' }}>Match</option>
                    <option value="over"  {{ request('status')=='over' ?'selected':'' }}>Over</option>
                    <option value="short" {{ request('status')=='short'?'selected':'' }}>Short</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Apply</button>
            </div>
        </div>
    </form>

    {{-- Totals --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="fw-semibold text-muted">Total Current</div>
                    <div class="h4 mb-0">{{ (int)($totals->sum_current ?? 0) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold text-muted">Total Inventoried</div>
                    <div class="h4 mb-0">{{ (int)($totals->sum_inventoried ?? 0) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold text-muted">Total Variance</div>
                    @php
                        $sumVar = (int)($totals->sum_variance ?? 0);
                        $badge = $sumVar === 0 ? 'bg-secondary' : ($sumVar > 0 ? 'bg-success' : 'bg-danger');
                    @endphp
                    <span class="badge {{ $badge }} fs-5 px-3 py-2">{{ $sumVar }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Variance Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Category</th>
                        <th>UOM</th>
                        <th class="text-end">Current</th>
                        <th class="text-end">Inventoried</th>
                        <th class="text-end">Variance</th>
                        <th>Status</th>
                        <th>Last Scan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lots as $p)
                        @php
                            $variance = (int)$p->inventoried_stock - (int)$p->balance;
                            $status = $variance === 0 ? 'Match' : ($variance > 0 ? 'Over' : 'Short');
                            $cls = $variance === 0 ? 'text-muted' : ($variance > 0 ? 'text-success fw-semibold' : 'text-danger fw-semibold');
                            $badge = $variance === 0 ? 'bg-secondary' : ($variance > 0 ? 'bg-success' : 'bg-danger');
                        @endphp
                        <tr>
                            <td>{{ $p->code }}</td>
                            <td>{{ $p->category_id }}</td>
                            <td>{{ $p->uom_id }}</td>
                            <td class="text-end">{{ $p->balance }}</td>
                            <td class="text-end">{{ $p->inventoried_stock }}</td>
                            <td class="text-end {{ $cls }}">{{ $variance }}</td>
                            <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
                            <td>{{ $p->scanned_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $lots->links() }}
        </div>
    </div>

</div>
</body>
</html>
