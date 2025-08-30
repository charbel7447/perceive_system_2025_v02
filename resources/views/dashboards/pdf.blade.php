<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard PDF</title>
<style>
    body { font-family: sans-serif; }
    .widget-container { 
        break-inside: avoid; 
        page-break-inside: avoid; 
        page-break-before: auto; 
        margin-bottom: 10px; 
    }
    .widget { 
        background: #fff; 
        border: 1px solid #ccc; 
        border-radius: 8px; 
        padding: 8px; 
        box-sizing: border-box; 
        width: 100%;
    }
    table { border-collapse: collapse; width: 100%; font-size: 12px; }
    th, td { border: 1px solid #ccc; padding: 4px; text-align: center; }
    h1 { text-align: center; }
</style>
</head>
<body>
<h1>Dashboard: {{ $dashboard->name }}</h1>

@foreach($widgets as $w)
    <div class="widget-container">
        <div class="widget">
            <h4>{{ $w['data']['type'] === 'chart' ? 'Chart Widget' : 'Table Widget' }}</h4>

            @if($w['data']['type'] === 'chart')
                <img src="{{ $w['data']['chart_base64'] }}" style="width:100%; height:auto;">
            @elseif($w['data']['type'] === 'table')
                <table>
                    <thead>
                        <tr>
                            @foreach($w['data']['columns'] as $col)
                                <th>{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($w['data']['rows'] as $row)
                            <tr>
                                @foreach($w['data']['columns'] as $col)
                                    @php
                                        $key = strtolower(str_replace(' ', '_', $col));
                                        $val = $row[$key] ?? '';
                                        if(is_array($val) && isset($val['text'])) $val = $val['text'];
                                    @endphp
                                    <td>{!! $val !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>{{ $w['data']['html'] ?? 'No data' }}</p>
            @endif
        </div>
    </div>
@endforeach

</body>
</html>
