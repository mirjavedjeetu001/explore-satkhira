<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        td, th {
            font-family: 'Noto Sans Bengali', 'Hind Siliguri', Arial, sans-serif;
            font-size: 11pt;
            mso-number-format:\@;
        }
        .header { font-size: 16pt; font-weight: bold; color: #1a237e; }
        .sub-header { font-size: 12pt; color: #555; }
        .section-title { font-size: 13pt; font-weight: bold; color: #1a237e; background: #e8eaf6; }
        .label { font-weight: bold; background: #e8eaf6; }
        .th { font-weight: bold; background: #1a237e; color: #ffffff; text-align: center; padding: 6px; }
        .data { border: 1px solid #ccc; padding: 4px; }
        .even { background: #f5f7ff; }
        .total { font-weight: bold; font-size: 12pt; background: #1a237e; color: #ffffff; }
        .bar-green { background: #43a047; color: #fff; font-weight: bold; text-align: center; }
        .bar-red { background: #e53935; color: #fff; font-weight: bold; text-align: center; }
        .bar-yellow { background: #fb8c00; color: #fff; font-weight: bold; text-align: center; }
        .bar-blue { background: #1e88e5; color: #fff; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

{{-- HEADER --}}
<table>
    <tr><td class="header" colspan="10">সার্ভে ভোটার রিপোর্ট</td></tr>
    <tr><td class="sub-header" colspan="10">এক্সপ্লোর সাতক্ষীরা — {{ now()->format('d/m/Y h:i A') }}</td></tr>
    <tr><td colspan="10"></td></tr>
</table>

{{-- SURVEY INFO --}}
<table>
    <tr><td class="section-title" colspan="10">সার্ভে তথ্য</td></tr>
    <tr><td class="label">শিরোনাম:</td><td colspan="9">{{ $survey->title }}</td></tr>
    <tr><td class="label">প্রশ্ন:</td><td colspan="9">{{ $survey->question }}</td></tr>
    <tr>
        <td class="label">সময়কাল:</td>
        <td colspan="9">{{ $survey->start_time ? \Carbon\Carbon::parse($survey->start_time)->format('d/m/Y h:i A') : '-' }} — {{ $survey->end_time ? \Carbon\Carbon::parse($survey->end_time)->format('d/m/Y h:i A') : '-' }}</td>
    </tr>
    <tr>
        <td class="label">অবস্থা:</td>
        <td colspan="9">@if($survey->is_live) চলমান (LIVE) @elseif($survey->is_ended) শেষ হয়েছে @else আসন্ন @endif</td>
    </tr>
    <tr><td class="total" colspan="2">মোট ভোট: {{ $totalVotes }} জন</td><td colspan="8"></td></tr>
    <tr><td colspan="10"></td></tr>
</table>

{{-- RESULTS --}}
@php $barColors = ['bar-green', 'bar-red', 'bar-yellow', 'bar-blue']; @endphp
<table>
    <tr><td class="section-title" colspan="4">ফলাফল সারাংশ</td></tr>
    <tr>
        <td class="th">অপশন</td>
        <td class="th">ভোট সংখ্যা</td>
        <td class="th">শতাংশ (%)</td>
        <td class="th">গ্রাফ</td>
    </tr>
    @foreach($results as $i => $result)
    <tr>
        <td class="data" style="font-weight:bold">{{ $result['option'] }}</td>
        <td class="data" style="text-align:center">{{ $result['count'] }} জন</td>
        <td class="data" style="text-align:center">{{ $result['percentage'] }}%</td>
        <td class="{{ $barColors[$i % 4] }}" style="width:200px">{{ str_repeat('█', intval($result['percentage'] / 5)) }} {{ $result['percentage'] }}%</td>
    </tr>
    @endforeach
    <tr><td colspan="4"></td></tr>
</table>

{{-- VOTER LIST --}}
<table>
    <tr><td class="section-title" colspan="10">সম্পূর্ণ ভোটার তালিকা ({{ $totalVotes }} জন)</td></tr>
    <tr>
        <td class="th">ক্রমিক</td>
        <td class="th">নাম</td>
        <td class="th">ফোন</td>
        <td class="th">ক্লাস</td>
        <td class="th">বিভাগ/ডিপার্টমেন্ট</td>
        <td class="th">বর্ষ</td>
        <td class="th">সেশন</td>
        <td class="th">ভোট</td>
        <td class="th">মতামত</td>
        <td class="th">ভোটের সময়</td>
    </tr>
    @forelse($votes as $vote)
    <tr>
        <td class="data {{ $loop->even ? 'even' : '' }}" style="text-align:center">{{ $loop->iteration }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}">{{ $vote->name }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}">{{ $vote->phone }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}">
            @if($vote->class_type == 'intermediate')
                ইন্টারমিডিয়েট
            @elseif($vote->class_type == 'honours')
                অনার্স
            @else
                {{ $vote->class_type ?? '-' }}
            @endif
        </td>
        <td class="data {{ $loop->even ? 'even' : '' }}">{{ $vote->department ?? '-' }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}">{{ $vote->year ?? '-' }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}">{{ $vote->session ?? '-' }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}" style="font-weight:bold">{{ $vote->selected_option }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}">{{ $vote->comment ?? '-' }}</td>
        <td class="data {{ $loop->even ? 'even' : '' }}">{{ $vote->created_at->format('d/m/Y h:i A') }}</td>
    </tr>
    @empty
    <tr><td class="data" colspan="10" style="text-align:center; color:#999;">কোনো ভোট পাওয়া যায়নি।</td></tr>
    @endforelse
</table>

</body>
</html>
