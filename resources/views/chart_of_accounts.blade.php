@extends('layout.masterCustom')

<style>
    .chart-container {
        max-width: 1200px;
        margin: 0 auto;
        font-family: "Segoe UI", sans-serif;
        padding: 30px;
    }

    .classes-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px 60px;
    }

    .account-class {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 1px solid #ccc;
        padding-bottom: 8px;
    }

    .accounts-grid {
        margin-top: 10px;
        margin-left: 50px;
    }

    .account-item {
        display: flex;
        align-items: center;
        font-size: 16px;
        padding: 4px 0;
    }

    .account-item::before {
        content: "→ 📂";
        margin-right: 10px;
        color: #444;
        font-size: 15px;
    }

    .account-code {
        font-weight: 600;
        color: #34495e;
        margin-right: 6px;
    }

    .account-name {
        color: #666;
    }

    .numbered-title {
        font-weight: bold;
        color: #2c3e50;
    }

    @media (max-width: 992px) {
        .classes-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@section('content')
<div class="chart-container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="m-0">📘 Chart of Accounts / دليل الحسابات</h2>
        <a href="{{ url('/chart_of_accounts') }}" target="_top" style="color: #000;font-size: medium;font-weight: bold;" 
        class="btn btn-outline-primary">
            ⬅️ Return
        </a>
    </div>
    <div class="classes-grid">
        @foreach($classes as $index => $class)
            <div>
                <div class="account-class">
                    <div class="numbered-title">
                        {{ $index + 1 }} - {{ $class->name_en }} / {{ $class->name_ar }}
                    </div>
                </div>

                @if($class->accounts->count())
                    <div class="accounts-grid">
                        @foreach($class->accounts as $account)
                            <div class="account-item">
                                <span class="account-code">{{ $account->code }}</span> – &nbsp;
                                <span class="account-name">{{ $account->name_en }} / {{ $account->name_ar }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ps-5 text-muted">No accounts defined under this class.</div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
