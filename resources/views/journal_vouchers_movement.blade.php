<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Journal Vouchers Movement</title>

    {{-- Lato + Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f8f9fa;
        }
        h5 {
            font-weight: 700;
            margin: 0;
        }
        .card {
            border-radius: 12px;
            border: none;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.25rem;
        }
        .card-header h5 {
            font-weight: 600;
            font-size: 1.2rem;
        }
        .table th {
            background: #212529 !important;
            color: #fff;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        .btn-sm {
            border-radius: 8px;
            font-weight: 500;
        }
        .collapse td {
            padding: 0.75rem;
            background: #fdfdfd;
        }
        .sub-table th {
            background: #f1f3f5 !important;
            color: #333;
        }
        .sub-table td {
            font-size: 0.9rem;
        }
        .badge-status {
            padding: 0.35em 0.65em;
            font-size: 0.75rem;
            border-radius: 8px;
        }
        .badge-added {
            background: #d1e7dd;
            color: #0f5132;
        }
        .badge-deleted {
            background: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>
<div class="containerx py-4">
    <div class="card shadow-sm">
        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>📊 Journal Vouchers Movement</h5>
            <div class="d-flex gap-2">
                <a target="_top" href="{{ url('/') }}/trial_balance_report" class="btn btn-success btn-sm">
                    📥 Trial Balance
                </a>
                <a href="{{ url('/') }}/system/journal_vouchers_movement/export" class="btn btn-outline-success btn-sm">
                    📤 Export Excel
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="card-body table-responsive">
            <table id="movementTable" class="table table-bordered table-hover align-middle">
                <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>JV Number</th>
                    <th>Document</th>
                    <th>Doc Date</th>
                    <th>Currency</th>
                    <th>Exchange Rate</th>
                    <th>Total Debit</th>
                    <th>Total Credit</th>
                    <th>Created By</th>
                    <th>Movement Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($movements as $move)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($move->type === 'Added')
                                <span class="badge-status badge-added">⬆️ {{ $move->type }}</span>
                            @elseif($move->type === 'Deleted')
                                <span class="badge-status badge-deleted">⬇️ {{ $move->type }}</span>
                            @else
                                <span class="badge-status bg-secondary text-white">{{ $move->type }}</span>
                            @endif
                        </td>
                        <td>{{ $move->number }}</td>
                        <td>
                            <strong>{{ $move->document_number }}</strong><br>
                            <small class="text-muted">{{ $move->document_name }}</small>
                        </td>
                        <td>{{ $move->document_date ?? '-' }}</td>
                        <td>
                            <?php $currency_name = \App\Currency::where('id','=',$move->currency_id)->value('code'); ?>
                            {{ $currency_name ?? '-' }}
                        </td>
                        <td>{{ number_format($move->exchange_rate, 3) }}</td>
                        <td class="text-success fw-bold">{{ number_format($move->total_debit, 3) }}</td>
                        <td class="text-danger fw-bold">{{ number_format($move->total_credit, 3) }}</td>
                        <td>{{ $move->created_by }}</td>
                        <td>{{ $move->movement_date }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="collapse"
                                    data-bs-target="#expandRow{{ $loop->index }}" aria-expanded="false"
                                    aria-controls="expandRow{{ $loop->index }}">
                                ➕ Details
                            </button>
                        </td>
                    </tr>

                    {{-- Expandable Row --}}
                    <tr id="expandRow{{ $loop->index }}" class="collapse">
                        <td colspan="12">
                            @php $items = json_decode($move->items, true); @endphp

                            @if(is_array($items) && count($items) > 0)
                                <table class="table sub-table table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Account Name AR</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Cost Center</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ $item['account_code'] }}</td>
                                            <td>{{ $item['account_name_en'] }}</td>
                                            <td>{{ $item['account_name_ar'] }}</td>
                                            <td class="text-success">{{ $item['debit'] }}</td>
                                            <td class="text-danger">{{ $item['credit'] }}</td>
                                            <td>
                                                <?php 
                                                    $cost_center_code = \App\ChartOfAccount::where('code','=',$item['account_code'])->value('class_code');
                                                    $cost_center_name_en = \App\ClassesCode::where('code','=',$cost_center_code)->value('name_en');
                                                    $cost_center_name_ar = \App\ClassesCode::where('code','=',$cost_center_code)->value('name_ar');
                                                ?>
                                                <span><b>({{ $cost_center_code }})</b></span> - {{ $cost_center_name_en }}/{{ $cost_center_name_ar }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted mb-0">No journal items found.</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
