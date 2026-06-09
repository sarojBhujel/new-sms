<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student ID Card</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        body { margin: 0; padding: 0; font-family: 'Arial', sans-serif; color: #1b1b1b; background: #fff; }
        .page { width: 100%; min-height: 297mm; padding: 20mm; box-sizing: border-box; }
        .id-card { width: 360px; min-height: 220px; border: 1px solid #2b2b2b; border-radius: 14px; padding: 18px; box-sizing: border-box; position: relative; background: #fff; margin: 0 auto; }
        .id-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
        .logo-box { width: 62px; height: 62px; background: #f2f2f2; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .logo-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .school-info { flex: 1; }
        .school-name { font-size: 15px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; line-height: 1.1; }
        .school-subtitle { font-size: 11px; color: #555; line-height: 1.3; margin-top: 4px; }
        .photo-box { position: absolute; top: 20px; right: 20px; width: 92px; height: 112px; background: #eef0f2; border: 2px solid #2b2b2b; border-radius: 12px; overflow: hidden; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .id-details { margin-top: 28px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .detail-label { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 0.08em; width: 45%; }
        .detail-value { width: 52%; font-size: 13px; font-weight: 600; color: #212121; text-align: right; }
        .detail-value.secondary { font-weight: 500; color: #4b4b4b; }
        .divider { height: 1px; background: #d4d4d4; margin: 16px 0; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 12px; }
        .signature { width: 48%; text-align: center; font-size: 11px; color: #555; }
        .signature-line { border-top: 1px dashed #999; padding-top: 6px; margin-top: 22px; }
        .card-meta { font-size: 10px; color: #777; text-align: right; line-height: 1.4; }
        @media print { body { margin: 0; } .page { padding: 10mm; } .id-card { box-shadow: none; }}
    </style>
</head>
<body>
    <div class="page">
        @php
            $studentModel = $student ?? null;
            if (empty($studentModel) && isset($receipt)) {
                $studentModel = optional($receipt)->student ?? null;
            }
        @endphp
        <div class="id-card">
            <div class="id-header">
                <div class="logo-box">
                    @if(!empty($schoolLogo))
                        <img src="{{ $schoolLogo }}" alt="Logo">
                    @else
                        <span style="font-size: 18px; color: #333; font-weight: 700;">{{ strtoupper(substr(config('app.name', 'SC'), 0, 2)) }}</span>
                    @endif
                </div>
                <div class="school-info">
                    <div class="school-name">{{ $schoolName ?? config('app.name', 'School Name') }}</div>
                    <div class="school-subtitle">{{ $schoolAddress ?? 'Address Line 1, City, Country' }}</div>
                </div>
            </div>
            <div class="photo-box">
                @if(!empty(optional($studentModel)->photo))
                    <img src="{{ optional($studentModel)->photo }}" alt="Student Photo">
                @else
                    <img src="{{ asset('assets/img/default-profile.png') }}" alt="Photo">
                @endif
            </div>
            <div class="id-details">
                <div class="detail-row">
                    <div class="detail-label">Name</div>
                    <div class="detail-value">{{ optional($studentModel)->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Student ID</div>
                    <div class="detail-value">{{ optional($studentModel)->student_id ?? optional($studentModel)->id ?? '' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Grade / Class</div>
                    <div class="detail-value">{{ optional(optional($studentModel)->grade)->Name ?? optional(optional($studentModel)->classroom)->Name ?? '' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Section</div>
                    <div class="detail-value">{{ optional(optional($studentModel)->section)->Name ?? '' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Gender</div>
                    <div class="detail-value secondary">{{ optional(optional($studentModel)->gender)->Name ?? optional($studentModel)->gender ?? '' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">DOB</div>
                    <div class="detail-value secondary">{{ optional($studentModel)->Date_Birth ?? optional($studentModel)->dob ?? '' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Guardian</div>
                    <div class="detail-value secondary">{{ optional(optional($studentModel)->myparent)->Name_Father ?? optional($studentModel)->parent_name ?? '' }}</div>
                </div>
            </div>
            <div class="divider"></div>
            <div class="footer">
                <div class="signature">
                    <div class="signature-line">Principal Signature</div>
                </div>
                <div class="card-meta">
                    Card No: {{ optional($studentModel)->student_id ?? optional($studentModel)->id ?? '' }}<br>
                    Valid Till: {{ $valid_until ?? date('Y') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
