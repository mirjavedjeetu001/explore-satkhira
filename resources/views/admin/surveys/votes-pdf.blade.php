<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'bangla';
            src: url("{{ storage_path('fonts/NotoSansBengali-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'bangla', 'Helvetica', 'Arial', sans-serif;
        }
        body {
            font-size: 11px;
            color: #1a1a2e;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            padding: 20px 0 15px;
            border-bottom: 3px solid #1a237e;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 22px;
            color: #1a237e;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .header .subtitle {
            font-size: 13px;
            color: #555;
        }
        .header .date-info {
            font-size: 10px;
            color: #888;
            margin-top: 5px;
        }
        .survey-info {
            background: #f0f4ff;
            border: 1px solid #c5cae9;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 18px;
        }
        .survey-info h2 {
            font-size: 15px;
            color: #1a237e;
            margin-bottom: 8px;
        }
        .info-grid {
            width: 100%;
        }
        .info-grid td {
            padding: 3px 10px 3px 0;
            font-size: 11px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            width: 120px;
        }
        .info-value {
            color: #555;
        }
        .results-summary {
            margin-bottom: 18px;
        }
        .results-summary h3 {
            font-size: 13px;
            color: #1a237e;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e8eaf6;
        }
        .result-row {
            margin-bottom: 6px;
        }
        .result-bar-wrap {
            background: #e8eaf6;
            border-radius: 4px;
            height: 22px;
            overflow: hidden;
        }
        .result-bar {
            height: 100%;
            border-radius: 4px;
            min-width: 2px;
        }
        .bar-green { background: #43a047; }
        .bar-red { background: #e53935; }
        .bar-yellow { background: #fb8c00; }
        .bar-blue { background: #1e88e5; }
        .result-label {
            font-size: 11px;
            margin-bottom: 2px;
        }
        .result-label span {
            float: right;
            color: #666;
        }
        .votes-section h3 {
            font-size: 13px;
            color: #1a237e;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e8eaf6;
        }
        table.votes-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.votes-table thead th {
            background: #1a237e;
            color: white;
            padding: 7px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        table.votes-table tbody td {
            padding: 6px;
            border-bottom: 1px solid #e0e0e0;
        }
        table.votes-table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .badge-yes { background: #43a047; }
        .badge-no { background: #e53935; }
        .badge-neutral { background: #fb8c00; }
        .badge-other { background: #1e88e5; }
        .badge-inter { background: #00acc1; }
        .badge-honours { background: #5e35b1; }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e8eaf6;
            font-size: 9px;
            color: #999;
        }
        .total-badge {
            display: inline-block;
            background: #1a237e;
            color: white;
            padding: 4px 16px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>সার্ভে ভোটার রিপোর্ট</h1>
        <div class="subtitle">এক্সপ্লোর সাতক্ষীরা</div>
        <div class="date-info">তারিখ: {{ now()->format('d/m/Y h:i A') }}</div>
    </div>

    <div class="survey-info">
        <h2>{{ $survey->title }}</h2>
        <table class="info-grid">
            <tr>
                <td class="info-label">প্রশ্ন:</td>
                <td class="info-value">{{ $survey->question }}</td>
            </tr>
            <tr>
                <td class="info-label">সময়কাল:</td>
                <td class="info-value">
                    {{ $survey->start_time ? \Carbon\Carbon::parse($survey->start_time)->format('d/m/Y h:i A') : '-' }}
                    —
                    {{ $survey->end_time ? \Carbon\Carbon::parse($survey->end_time)->format('d/m/Y h:i A') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="info-label">অবস্থা:</td>
                <td class="info-value">
                    @if($survey->is_live) চলমান (LIVE) @elseif($survey->is_ended) শেষ হয়েছে @else আসন্ন @endif
                </td>
            </tr>
            <tr>
                <td class="info-label">মোট ভোট:</td>
                <td class="info-value"><span class="total-badge">{{ $totalVotes }} জন</span></td>
            </tr>
        </table>
    </div>

    <div class="results-summary">
        <h3>ফলাফল সারাংশ</h3>
        @php $barColors = ['bar-green', 'bar-red', 'bar-yellow', 'bar-blue']; @endphp
        @foreach($results as $i => $result)
            <div class="result-row">
                <div class="result-label">
                    <strong>{{ $result['option'] }}</strong>
                    <span>{{ $result['count'] }} ভোট ({{ $result['percentage'] }}%)</span>
                </div>
                <div class="result-bar-wrap">
                    <div class="result-bar {{ $barColors[$i % 4] }}" style="width: {{ max($result['percentage'], 1) }}%;"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="votes-section">
        <h3>সম্পূর্ণ ভোটার তালিকা ({{ $totalVotes }} জন)</h3>
        <table class="votes-table">
            <thead>
                <tr>
                    <th style="width:30px">#</th>
                    <th>নাম</th>
                    <th>ফোন</th>
                    <th>ক্লাস</th>
                    <th>বিভাগ/ডিপার্টমেন্ট</th>
                    <th>বর্ষ</th>
                    <th>সেশন</th>
                    <th>ভোট</th>
                    <th>মতামত</th>
                    <th>সময়</th>
                </tr>
            </thead>
            <tbody>
                @forelse($votes as $vote)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vote->name }}</td>
                        <td>{{ $vote->phone }}</td>
                        <td>
                            @if($vote->class_type == 'intermediate')
                                <span class="badge badge-inter">ইন্টারমিডিয়েট</span>
                            @elseif($vote->class_type == 'honours')
                                <span class="badge badge-honours">অনার্স</span>
                            @else
                                {{ $vote->class_type ?? '-' }}
                            @endif
                        </td>
                        <td>{{ $vote->department ?? '-' }}</td>
                        <td>{{ $vote->year ?? '-' }}</td>
                        <td>{{ $vote->session ?? '-' }}</td>
                        <td>
                            @php $optIndex = array_search($vote->selected_option, $survey->options); @endphp
                            @if($optIndex === 0)
                                <span class="badge badge-yes">{{ $vote->selected_option }}</span>
                            @elseif($optIndex === 1)
                                <span class="badge badge-no">{{ $vote->selected_option }}</span>
                            @elseif($optIndex === 2)
                                <span class="badge badge-neutral">{{ $vote->selected_option }}</span>
                            @else
                                <span class="badge badge-other">{{ $vote->selected_option }}</span>
                            @endif
                        </td>
                        <td>{{ $vote->comment ? \Illuminate\Support\Str::limit($vote->comment, 40) : '-' }}</td>
                        <td>{{ $vote->created_at->format('d/m/y h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="10" style="text-align:center; padding:20px; color:#999;">কোনো ভোট পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        এক্সপ্লোর সাতক্ষীরা | exploresatkhira.com | রিপোর্ট তৈরি: {{ now()->format('d/m/Y h:i A') }}
    </div>
</body>
</html>
