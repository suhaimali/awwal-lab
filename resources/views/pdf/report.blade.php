<style>
        @@page { margin: 0; }

        body {
            margin: 0;
            padding: 0;
        }

        .report-container {
            margin: 0;
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            color: #000;
        }

        .page-header {
            height: 95px;
        }

        .page-header img,
        .page-footer img {
            width: 100%;
            height: 100%;
        }

        .letterhead-fallback {
            padding: 13px 44px 0 44px;
            border-bottom: 1px solid #35a777;
            height: 81px;
            box-sizing: border-box;
        }

        .fallback-brand {
            float: left;
            width: 52%;
            text-align: center;
        }

        .fallback-tagline {
            font-size: 10px;
            font-style: italic;
            color: #555;
        }

        .fallback-name {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 34px;
            line-height: 32px;
            color: #8a277d;
            letter-spacing: 4px;
        }

        .fallback-sub {
            font-family: Helvetica, Arial, sans-serif;
            color: #149447;
            font-size: 12px;
            font-weight: 700;
        }

        .fallback-site {
            color: #8a277d;
            font-size: 12px;
            letter-spacing: 3px;
        }

        .fallback-address {
            float: right;
            width: 38%;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.2;
            color: #444;
        }

        .footer-fallback {
            width: 100%;
            height: 82px;
            color: #fff;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            line-height: 82px;
            text-align: center;
        }

        .footer-fallback-left,
        .footer-fallback-right {
            float: left;
            width: 50%;
            height: 82px;
        }

        .footer-fallback-left { background: #07984f; }
        .footer-fallback-right { background: #932486; }

        .page-footer {
            height: 82px;
        }

        .report-body {
            margin: 32px 60px 20px 60px;
        }

        .patient-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 15px;
        }

        .patient-info td {
            padding: 2px 4px;
            vertical-align: top;
            line-height: 1.25;
        }

        .patient-info .label {
            width: 130px;
        }

        .patient-info .sep {
            width: 10px;
            font-weight: bold;
        }

        .patient-info .right-label {
            width: 105px;
        }

        .patient-info strong {
            font-weight: 700;
        }

        .patient-rule {
            border: 0;
            border-top: 1px solid #000;
            margin: 4px -60px 28px -60px;
        }

        .category-title {
            text-align: center;
            font-weight: 700;
            font-size: 16px;
            margin: 14px 0 13px;
            text-transform: uppercase;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .results-table th,
        .results-table td {
            border: 1px solid #000;
            padding: 4px 7px;
            vertical-align: top;
            line-height: 1.18;
        }

        .results-table th {
            text-align: center;
            font-weight: 700;
            font-size: 15px;
        }

        .sl-col { width: 6%; text-align: center; }
        .param-col { width: 29%; }
        .value-col { width: 16%; text-align: center; }
        .unit-col { width: 14%; text-align: center; }
        .ref-col { width: 26%; }
        .flag-col { width: 9%; text-align: center; }

        .observed-number {
            font-weight: 700;
        }

        .section-row td {
            font-weight: 700;
        }

        .flag-critical {
            color: #d00000;
            font-weight: 700;
            text-align: center;
        }

        .flag-cell {
            text-align: center;
            font-weight: 700;
        }

        .report-closing {
            width: 100%;
            margin-top: 34px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .report-note {
            width: 62%;
            vertical-align: top;
            font-size: 14px;
            line-height: 1.35;
        }

        .report-note-label {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .signature {
            width: 38%;
            text-align: center;
            vertical-align: bottom;
            padding-right: 6px;
            font-size: 14px;
        }

        .signature img {
            max-width: 150px;
            max-height: 58px;
            object-fit: contain;
            display: inline-block;
            margin-bottom: 4px;
        }

        .signature-name {
            font-weight: 700;
        }
    </style>

<div class="report-container">
    <div class="page-header">
        @if (extension_loaded('gd'))
            <img src="{{ public_path('images/report-header-awwal.png') }}" alt="SUHAIM SOFT LAB">
        @else
            <div class="letterhead-fallback">
                <div class="fallback-brand">
                    <div class="fallback-tagline">"Accurate Diagnosis for Effective Treatment"</div>
                    <div class="fallback-name">awwal LABS</div>
                    <div class="fallback-sub">QUALITY DIAGNOSTIC LABS</div>
                    <div class="fallback-site">www.awwallabs.in</div>
                </div>
                <div class="fallback-address">
                    A Muhammed's Complex<br>
                    Chenaykunnu Road Jn.<br>
                    <strong>PATHAPPIRIYAM</strong>, Vayanasala<br>
                    Ph : 7034 250 209, 7559 049 948<br>
                    Email : awwallabppm@gmail.com
                </div>
            </div>
        @endif
    </div>

    <div class="report-body">
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
            
            $reportDate = optional($report->sample_received_on)->format('d-M-Y - h:i:s A');
            $printedDate = now()->format('d-M-Y - h:i:s A');
        @endphp

        <table class="patient-info">
            <tr>
                <td class="label">Patient Name</td>
                <td class="sep">:</td>
                <td style="width: 35%;"><strong>{{ $patientName }}</strong></td>
                <td class="right-label">Age</td>
                <td class="sep">:</td>
                <td>{{ $ageDisplay }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sex : {{ $sex }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="right-label">Specimen</td>
                <td class="sep">:</td>
                <td></td>
            </tr>
            <tr>
                <td>Reference No</td>
                <td class="sep">:</td>
                <td>{{ $referenceNo }}</td>
                <td class="right-label">Date</td>
                <td class="sep">:</td>
                <td>{{ $reportDate }}</td>
            </tr>
            <tr>
                <td>Referred By</td>
                <td class="sep">:</td>
                <td><strong>{{ $report->doctor_name }}</strong></td>
                <td class="right-label">Printed Date</td>
                <td class="sep">:</td>
                <td>{{ $printedDate }}</td>
            </tr>
        </table>

        <hr class="patient-rule">

        @foreach ($groupedResults as $category => $results)
            <div class="category-title">{{ $category }}</div>
            <table class="results-table">
                <thead>
                    <tr>
                                <th class="sl-col">SL No</th>
	                            <th class="param-col">Parameter</th>
	                            <th class="value-col">Observed Value</th>
	                            <th class="unit-col">Unit</th>
	                            <th class="ref-col">Reference Value</th>
	                            <th class="flag-col">Flag</th>
	                        </tr>
	                    </thead>
                <tbody>
                    @php $lastSubheading = null; $slNo = 1; @endphp
                    @foreach ($results as $r)
                        @php
                            $subheading = trim($r['subcategory'] ?? '');
                            $value = trim((string)($r['observed_value'] ?? ''));
                            $unit = trim((string)($r['unit'] ?? ''));
                        @endphp

                        @if ($subheading !== '' && $subheading !== $lastSubheading)
                            <tr class="section-row">
                                    <td></td>
	                                <td>{{ strtoupper($subheading) }}</td>
	                                <td></td>
	                                <td></td>
	                                <td></td>
	                                <td></td>
	                            </tr>
                            @php $lastSubheading = $subheading; @endphp
                        @endif

                        <tr>
                            <td class="sl-col">{{ $slNo++ }}</td>
                            <td>{{ $r['name'] ?? '' }}</td>
                            <td style="text-align: center;">
                                <span class="observed-number">{{ $value }}</span>
                            </td>
                            <td style="text-align: center;">{{ $unit }}</td>
                            <td>{!! nl2br(e($r['normal_value'] ?: ($r['biological_reference'] ?? ''))) !!}</td>
                            <td class="{{ ($r['flag'] ?? '') === 'C' ? 'flag-critical' : 'flag-cell' }}">{{ $r['flag'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <table class="report-closing">
            <tr>
                <td class="report-note">
                    <div class="report-note-label">Note :</div>
                    @if($report->notes)
                        <div>{!! nl2br(e($report->notes)) !!}</div>
                    @endif
                </td>
                <td class="signature">
                    @if($report->signature && is_file($report->signature->imageAbsolutePath()))
                        <img src="{{ $report->signature->imageAbsolutePath() }}" alt="{{ $report->signature->name }}">
                        <div class="signature-name">{{ $report->signature->name }}</div>
                    @else
                        <div class="signature-name">Medi Technician</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page-footer">
        @if (extension_loaded('gd'))
            <img src="{{ public_path('images/report-footer-awwal.png') }}" alt="Working Hours">
        @else
            <div class="footer-fallback">
                <div class="footer-fallback-left">QUALITY OF OUR LABORATORY IS CONTROLLED BY CMC VELLORE</div>
                <div class="footer-fallback-right">Working Hours : 6.30 am To 9.00 pm Sunday 7.00 am To 12.00 pm</div>
            </div>
        @endif
    </div>
</div>
