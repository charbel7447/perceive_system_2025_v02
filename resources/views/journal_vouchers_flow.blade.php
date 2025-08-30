@extends('layout.masterCustom2')

@section('content')
<div class="container">
    <h2 class="mb-4">Universal Journal Flow Mapping</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('journal-flow.store') }}">
        @csrf

        {{-- Save button at the top --}}
        <div class="text-end mb-3">
            <button type="submit" class="btn btn-success">Save Mapping</button>
        </div>

        @foreach($sections as $index => $section)
            @php
                $saved = $savedMappings[$section]->mappings ?? [];
                $isFirst = $index === 0;
            @endphp

            <div class="card mb-3">
                <div style="padding: 5px 10px;font-size: 18px;font-weight: bold;" class="card-header d-flex justify-content-between align-items-center bg-secondary  text-white">
                    <span>{{ ucfirst(str_replace('_', ' ', $section)) }} Flow</span>
                    <button
                        class="btn btn-sm btn-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{ $section }}"
                        aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                        aria-controls="collapse-{{ $section }}">
                        Expand
                    </button>
                </div>

                <div id="collapse-{{ $section }}" class="collapse {{ $isFirst ? 'show' : '' }}">
                    <div class="card-body p-0">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Field</th>
                                    <th>Ledger Account</th>
                                    <th style="width: 15%">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fields as $key => $fieldLabel)
                                    @php
                                        $config = $saved[$key] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $fieldLabel }}</td>
                                        <td>
                                            <select name="flows[{{ $section }}][{{ $key }}][account_id]" class="form-control">
                                                <option value="">-- Select Account --</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}" @if($config && $config['account_id'] == $account->id) selected @endif>
                                                        {{ $account->code }} - {{ $account->name_en }} / {{ $account->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="flows[{{ $section }}][{{ $key }}][type]" class="form-control">
                                                <option value="">--</option>
                                                <option value="debit" @if($config && $config['type'] === 'debit') selected @endif>Debit</option>
                                                <option value="credit" @if($config && $config['type'] === 'credit') selected @endif>Credit</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Save button at the bottom --}}
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-success">Save Mapping</button>
        </div>
    </form>
</div>
@endsection
<style>
    .form-control {font-size:16px !important;}
</style>
<!-- Load Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
