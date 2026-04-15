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
            font-family: 'bangla', 'Helvetica', sans-serif;
        }
        body {
            font-size: 10px;
            color: #222;
            padding: 15px;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1a237e;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .header-table td {
            text-align: center;
        }
        .title {
            font-size: 20px;
            color: #1a237e;
            font-weight: bold;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
        }
        .date-text {
            font-size: 9px;
            color: #999;
        }

        /* Info section */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #c5cae9;
            background: #f5f7ff;
        }
        .info-table td {
            padding: 5px 10px;
            font-size: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-table .label {
            font-weight: bold;
            width: 80px;
            color: #333;
            background: #e8eaf6;
        }

        /* Results table */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .results-table th {
            background: #1a237e;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }
        .results-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        .results-table tr:nth-child(even) td {
            background: #f5f7ff;
        }

        /* Bar using table cell background trick */
        .bar-cell {
            padding: 0 !important;
            width: 50%;
        }
        .bar-inner {
            height: 16px;
        }
        .bg-green { background-color: #43a047; }
        .bg-red { background-color: #e53935; }
        .bg-yellow { background-color: #fb8c00; }
        .bg-blue { background-color: #1e88e5; }

        /* Votes table */
        .votes-table {
            width: 100%;
            border-collapse: collapse;
        }
        .votes-table th {
            background: #1a237e;
            color: white;
            padding: 5px 4px;
            text-align: left;
            font-size: 9px;
        }
        .votes-table td {
            padding: 4px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9px;
        }
        .votes-table tr:nth-child(even) td {
            background: #f5f7ff;
        }

        .section-title {
            font-size: 12px;
            color: #1a237e;
            font-weight: bold;
            padding: 8px 0 5px;
            border-bottom: 2px solid #c5cae9;
            margin-bottom: 8px;
        }

        .footer-table {
            width: 100%;
            margin-top: 15px;
            border-top: 2px solid #e8eaf6;
            padding-top: 8px;
        }
        .footer-table td {
            text-align: center;
            font-size: 8px;
            color: #999;
        }

        .badge-text {
            padding: 1px 6px;
            border-radius: 8px;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }
        .total-box {
            background: #1a237e;
            color: white;
            padding: 3px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr><td class="title">সার্ভে ভোটার রিপোর্ট</td></tr>
        <tr><td class="subtitle">এক্সপ্লোর সাতক্ষীরা</td></tr>
        <tr><td class="date-text">তারিখ: {{ now()->format('d/m/Y h:i A') }}</td></tr>
    </table>

    {{-- SURVEY INFO --}}
    <table class="info-table">
        <tr>
            <td class="label">শিরোনাম</td>
            <td>{{ $survey->title }}</td>
        </tr>
        <tr>
            <td class="label">প্রশ্ন</td>
            <td>{{ $survey->question }}</td>
        </tr>
        <tr>
            <td class="label">সময়কাল</td>
            <td>{{ $survey->start_time ? \Carbon\Carbon::parse($survey->start_time)->format('d/m/Y h:i A') : '-' }} — {{ $survey->end_time ? \Carbon\Carbon::parse($survey->end_time)->format('d/m/Y h:i A') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">অবস্থা</td>
            <td>@if($survey->is_live) চলমান (LIVE) @elseif($survey->is_ended) শেষ হয়েছে @else আসন্ন @endif</td>
        </tr>
        <tr>
            <td class="label">মোট ভোট</td>
            <td><span class="total-box">{{ $totalVotes }} জন</span></td>
        </tr>
    </table>

    {{-- RESULTS --}}
    <p class="section-title">ফলাফল সারাংশ</p>
    @php $bgColors = ['bg-green', 'bg-red', 'bg-yellow', 'bg-blue']; @endphp
    <table class="results-table">
        <thead>
            <tr>
                <th style="width:30%">অপশন</th>
                <th style="width:15%">ভোট</th>
                <th style="width:15%">শতাংশ</th>
                <th style="width:40%">গ্রাফ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $i => $result)
            <tr>
                <td><strong>{{ $result['option'] }}</strong></td>
                <td>{{ $result['count'] }} জন</td>
                <td>{{ $result['percentage'] }}%</td>
                <td class="bar-cell">
                    <table style="width:100%; border-collapse:collapse">
                        <tr>
                            @if($result['percentage'] > 0)
                            <td style="width:{{ $result['percentage'] }}%; padding:0"><div class="bar-inner {{ $bgColors[$i % 4] }}"></div></td>
                            @endif
                            <td style="padding:0"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- VOTER LIST --}}
    <p class="section-title">সম্পূর্ণ ভোটার তালিকা ({{ $totalVotes }} জন)</p>
    <table class="votes-table">
        <thead>
            <tr>
                <th style="width:20px">#</th>
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
                        ইন্টারমিডিয়েট
                    @elseif($vote->class_type == 'honours')
                        অনার্স
                    @else
                        {{ $vote->class_type ?? '-' }}
                    @endif
                </td>
                <td>{{ $vote->department ?? '-' }}</td>
                <td>{{ $vote->year ?? '-' }}</td>
                <td>{{ $vote->session ?? '-' }}</td>
                <td>{{ $vote->selected_option }}</td>
                <td>{{ $vote->comment ? \Illuminate\Support\Str::limit($vote->comment, 30) : '-' }}</td>
                <td>{{ $vote->created_at->format('d/m/y h:i A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align:center; padding:15px; color:#999;">কোনো ভোট পাওয়া যায়নি।</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <table class="footer-table">
        <tr><td>এক্সপ্লোর সাতক্ষীরা | exploresatkhira.com | রিপোর্ট তৈরি: {{ now()->format('d/m/Y h:i A') }}</td></tr>
    </table>

</body>
</html>
