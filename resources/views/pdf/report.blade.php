<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lab Report - {{ $patient->first_name }}</title>
    <style>
        @page { margin: 120px 40px 80px 40px; }
        body { margin: 0; padding: 0; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 13px; color: #222; }
        
        header { position: fixed; top: -100px; left: 0px; right: 0px; height: 90px; }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; }
        
        .header-img { width: 100%; height: 100%; object-fit: contain; }
        .footer-img { width: 100%; height: 100%; object-fit: contain; }
        
        /* Fallbacks */
        .fallback-header { border-bottom: 2px solid #0e609e; padding-bottom: 10px; }
        .fallback-brand { float: left; width: 60%; }
        .fallback-name { font-size: 28px; font-weight: bold; color: #0e609e; letter-spacing: 2px; }
        .fallback-sub { font-size: 11px; font-weight: bold; color: #555; }
        .fallback-address { float: right; width: 40%; text-align: right; font-size: 11px; color: #444; line-height: 1.4; }
        .clear { clear: both; }

        .patient-box { width: 100%; border: 1px solid #0e609e; border-radius: 6px; margin-top: 10px; margin-bottom: 20px; border-collapse: separate; border-spacing: 0; overflow: hidden; }
        .patient-box td { padding: 8px 12px; border-bottom: 1px solid #eee; border-right: 1px solid #eee; vertical-align: middle; }
        .patient-box tr:last-child td { border-bottom: none; }
        .patient-box td:last-child { border-right: none; }
        
        .pb-label { font-size: 10px; color: #777; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 3px; }
        .pb-val { font-size: 13px; font-weight: bold; color: #111; }
        .pb-val-large { font-size: 16px; font-weight: bold; color: #0e609e; }
        
        .report-title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #333; text-transform: uppercase; letter-spacing: 2px; background: #f4f4f4; padding: 6px; border: 1px solid #ddd; }
        
        .category-title { background-color: #f0f4f8; border-left: 4px solid #0e609e; padding: 6px 10px; font-weight: bold; font-size: 14px; margin: 15px 0 8px; color: #0e609e; text-transform: uppercase; }
        
        .results-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .results-table th { background-color: #0e609e; color: #fff; text-align: left; padding: 8px; font-size: 12px; border: 1px solid #0e609e; }
        .results-table td { padding: 7px 8px; border-bottom: 1px solid #e0e0e0; border-left: 1px solid #eee; border-right: 1px solid #eee; font-size: 12px; vertical-align: middle; word-wrap: break-word; word-break: break-word; }
        .results-table tr:nth-child(even) td { background-color: #fdfdfd; }
        
        .results-table th.center, .results-table td.center { text-align: center; }
        .results-table .sl-col { width: 5%; text-align: center; }
        
        .section-row td { font-weight: bold; background-color: #eef5fa !important; color: #0e609e; padding: 5px 8px; font-size: 11px; text-transform: uppercase; }
        
        .flag-high { color: #d00000; font-weight: bold; font-size: 14px; }
        .flag-low { color: #0055d0; font-weight: bold; font-size: 14px; }
        .val-abnormal { font-weight: bold; font-size: 13px; }
        
        .end-of-report { text-align: center; font-weight: bold; margin-top: 30px; font-size: 12px; border-top: 1px dashed #aaa; padding-top: 10px; color: #444; text-transform: uppercase; letter-spacing: 1px; }
        
        .report-note { margin-top: 20px; font-size: 11px; line-height: 1.5; color: #444; }
        .report-note strong { color: #000; }
        
        .signature-area { width: 100%; margin-top: 40px; border-collapse: collapse; page-break-inside: avoid; }
        .signature-area td { vertical-align: bottom; }
        .sig-box { text-align: center; width: 220px; float: right; }
        .sig-box img { max-width: 160px; max-height: 60px; display: block; margin: 0 auto; }
        .sig-name { font-weight: bold; border-top: 1px solid #333; padding-top: 5px; margin-top: 5px; font-size: 12px; }
        
        .pagenum:before { content: counter(page); }
        .page-footer-text { text-align: center; font-size: 10px; padding-top: 10px; color: #777; border-top: 1px solid #eee; margin-top: 10px; }
    </style>
</head>
<body>
    <header>
        @if (extension_loaded('gd') && file_exists(public_path('images/report-header-awwal.png')))
            <img src="{{ public_path('images/report-header-awwal.png') }}" class="header-img" alt="Header">
        @else
            <div class="fallback-header">
                <div class="fallback-brand">
                    <div class="fallback-name">AWWAL LABS</div>
                    <div class="fallback-sub">QUALITY DIAGNOSTIC LABS | Accurate Diagnosis for Effective Treatment</div>
                </div>
                <div class="fallback-address">
                    A Muhammed's Complex, Chenaykunnu Road Jn.<br>
                    <strong>PATHAPPIRIYAM</strong>, Vayanasala<br>
                    Ph: 7034 250 209, 7559 049 948 | www.awwallabs.in
                </div>
                <div class="clear"></div>
            </div>
        @endif
    </header>

    <footer>
        @if (extension_loaded('gd') && file_exists(public_path('images/report-footer-awwal.png')))
            <img src="{{ public_path('images/report-footer-awwal.png') }}" class="footer-img" alt="Footer">
        @else
            <div class="page-footer-text">
                QUALITY OF OUR LABORATORY IS CONTROLLED BY CMC VELLORE | Working Hours: 6.30 am To 9.00 pm (Sunday 7.00 am To 12.00 pm)<br>
                Page <span class="pagenum"></span> - Printed: {{ date('d-M-Y h:i A') }}
            </div>
        @endif
    </footer>

    <main>
        @php
            $title = '';
            $ageVal = (int) $patient->age;
            $ageType = $patient->age_type ?: 'Years';
            if (strtolower($patient->gender ?? '') === 'male') {
                $title = ($ageVal < 13 && $ageType === 'Years') || $ageType !== 'Years' ? 'MASTER. ' : 'MR. ';
            } elseif (strtolower($patient->gender ?? '') === 'female') {
                $title = ($ageVal < 13 && $ageType === 'Years') || $ageType !== 'Years' ? 'BABY. ' : 'MRS. ';
            }
            $patientName = trim(strtoupper($title . $patient->first_name . ' ' . $patient->last_name));
            $referenceNo = str_replace(['#P-', '#'], '', $patient->patient_id);
            $sex = strtoupper($patient->gender ?? '');
            $ageDisplay = $patient->age . ' ' . ($ageType === 'Years' ? 'Yrs' : $ageType);
            $reportDate = optional($report->sample_received_on)->format('d-M-Y h:i A');
        @endphp

        <table class="patient-box">
            <tr>
                <td style="width: 50%;">
                    <span class="pb-label">Patient Name</span>
                    <span class="pb-val-large">{{ $patientName }}</span>
                </td>
                <td style="width: 25%;">
                    <span class="pb-label">Age / Gender</span>
                    <span class="pb-val">{{ $ageDisplay }} / {{ $sex }}</span>
                </td>
                <td style="width: 25%;">
                    <span class="pb-label">Patient ID</span>
                    <span class="pb-val">{{ $referenceNo }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="pb-label">Referred By</span>
                    <span class="pb-val">{{ $report->doctor_name ?: 'Self' }}</span>
                </td>
                <td>
                    <span class="pb-label">Report Date</span>
                    <span class="pb-val">{{ $reportDate }}</span>
                </td>
                <td>
                    <span class="pb-label">Barcode / SID</span>
                    <span class="pb-val" style="font-family: monospace;">{{ $report->id }}-{{ date('ymd', strtotime($report->created_at)) }}</span>
                </td>
            </tr>
        </table>

        <div class="report-title">LABORATORY INVESTIGATION REPORT</div>

        @foreach ($groupedResults as $category => $results)
            <div class="category-title">{{ $category }}</div>
            <table class="results-table">
                <thead>
                    <tr>
                        <th class="sl-col">#</th>
                        <th style="width: 35%;">Investigation</th>
                        <th class="center" style="width: 15%;">Observed Value</th>
                        <th class="center" style="width: 10%;">Unit</th>
                        <th style="width: 25%;">Biological Reference</th>
                        <th class="center" style="width: 10%;">Flag</th>
                    </tr>
                </thead>
                <tbody>
                    @php $lastSubheading = null; $slNo = 1; @endphp
                    @foreach ($results as $r)
                        @php
                            $subheading = trim($r['subcategory'] ?? '');
                            $value = trim((string)($r['observed_value'] ?? ''));
                            $unit = trim((string)($r['unit'] ?? ''));
                            $flag = $r['flag'] ?? '';
                            $isAbnormal = in_array($flag, ['↑', '↓', '↑↑', '↓↓', 'H', 'L', 'C']);
                            $flagClass = ($flag === '↑' || $flag === '↑↑' || $flag === 'H') ? 'flag-high' : (($flag === '↓' || $flag === '↓↓' || $flag === 'L') ? 'flag-low' : '');
                        @endphp

                        @if ($subheading !== '' && $subheading !== $lastSubheading)
                            <tr class="section-row">
                                <td></td>
                                <td colspan="5">{{ strtoupper($subheading) }}</td>
                            </tr>
                            @php $lastSubheading = $subheading; @endphp
                        @endif

                        <tr>
                            <td class="sl-col">{{ $slNo++ }}</td>
                            <td>{{ $r['name'] ?? '' }}</td>
                            <td class="center {{ $isAbnormal ? 'val-abnormal' : '' }}">{!! $isAbnormal ? '<span style="color:#0e609e;">'.$value.'</span>' : $value !!}</td>
                            <td class="center">{{ $unit }}</td>
                            <td>{!! nl2br(e($r['normal_value'] ?: ($r['biological_reference'] ?? ''))) !!}</td>
                            <td class="center {{ $flagClass }}">{{ $flag }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <div class="end-of-report">*** End of Report ***</div>

        <table class="signature-area">
            <tr>
                <td style="width: 60%;">
                    @if($report->notes)
                        <div class="report-note">
                            <strong>Note:</strong><br>
                            {!! nl2br(e($report->notes)) !!}
                        </div>
                    @endif
                </td>
                <td style="width: 40%;">
                    <div class="sig-box">
                        @if($report->signature && is_file($report->signature->imageAbsolutePath()))
                            <img src="{{ $report->signature->imageAbsolutePath() }}" alt="Signature">
                            <div class="sig-name">{{ strtoupper($report->signature->name) }}</div>
                        @else
                            <br><br><br>
                            <div class="sig-name">AUTHORIZED SIGNATORY</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </main>
</body>
</html>
