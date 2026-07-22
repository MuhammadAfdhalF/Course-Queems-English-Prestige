<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->certificate_number }}</title>

    @php
    $templateBackground = $certificate->certificateTemplate?->background_image;
    $templateBackgroundPath = $templateBackground
    ? public_path('storage/' . $templateBackground)
    : null;

    $hasTemplateBackground = extension_loaded('gd') && $templateBackgroundPath && file_exists($templateBackgroundPath);

    $logoPath = public_path('images/logo-queens-english.png');
    $hasLogo = extension_loaded('gd') && file_exists($logoPath);

    $signaturePath = $certificateSetting?->signature_image
    ? public_path('storage/' . $certificateSetting->signature_image)
    : null;

    $hasSignature = extension_loaded('gd') && $signaturePath && file_exists($signaturePath);

    $signerName = $certificateSetting?->signerName() ?? 'Queens English Prestige';
    $signerTitle = $certificateSetting?->signerTitle() ?? 'Authorized Signature';

    $verificationUrl = $certificate->verification_token
    ? route('certificates.verify', $certificate->verification_token)
    : null;

    $qrDataUri = null;
    if ($verificationUrl) {
        try {
            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(130)->margin(1)->generate($verificationUrl);
            if ($qrSvg) {
                $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
            }
        } catch (\Throwable $e) {
            $qrDataUri = null;
        }
    }

    $hasSectionScores = is_array($certificate->section_scores) && !empty($certificate->section_scores);
    $sectionScores = $hasSectionScores ? $certificate->section_scores : [];
    $finalScoreFormatted = $certificate->final_score !== null ? number_format((float) $certificate->final_score, 2, '.', '') : null;
    $sectionCount = count($sectionScores);
    $isMultiPage = $sectionCount > 5;
    @endphp

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 297mm;
            background: #ffffff;
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
        }

        .certificate {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            background: #fffdf6;
            text-align: center;
        }

        .template-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            z-index: 0;
        }

        .default-border {
            position: absolute;
            top: 6mm;
            left: 6mm;
            right: 6mm;
            bottom: 6mm;
            border: 3mm solid #071738;
            z-index: 1;
        }

        .inner-border {
            position: absolute;
            top: 13mm;
            left: 13mm;
            right: 13mm;
            bottom: 13mm;
            border: 0.35mm solid rgba(212, 160, 23, 0.35);
            z-index: 2;
        }

        .corner {
            position: absolute;
            width: 24mm;
            height: 24mm;
            z-index: 3;
        }

        .corner-top-left {
            top: 15mm;
            left: 15mm;
            border-left: 0.9mm solid #d4a017;
            border-top: 0.9mm solid #d4a017;
        }

        .corner-top-right {
            top: 15mm;
            right: 15mm;
            border-right: 0.9mm solid #d4a017;
            border-top: 0.9mm solid #d4a017;
        }

        .corner-bottom-left {
            bottom: 15mm;
            left: 15mm;
            border-left: 0.9mm solid #d4a017;
            border-bottom: 0.9mm solid #d4a017;
        }

        .corner-bottom-right {
            bottom: 15mm;
            right: 15mm;
            border-right: 0.9mm solid #d4a017;
            border-bottom: 0.9mm solid #d4a017;
        }

        .watermark-left {
            position: absolute;
            left: -35mm;
            top: 42mm;
            width: 90mm;
            height: 90mm;
            border: 8mm solid rgba(212, 160, 23, 0.08);
            border-radius: 50%;
            z-index: 1;
        }

        .watermark-right {
            position: absolute;
            right: -40mm;
            bottom: 25mm;
            width: 105mm;
            height: 105mm;
            border: 9mm solid rgba(7, 23, 56, 0.06);
            border-radius: 50%;
            z-index: 1;
        }

        .content-layer {
            position: relative;
            z-index: 5;
            width: 297mm;
            height: 210mm;
        }

        .header {
            position: absolute;
            top: 14mm;
            left: 30mm;
            right: 30mm;
            text-align: center;
        }

        .brand-mark {
            width: 14mm;
            height: 14mm;
            margin: 0 auto;
            border-radius: 50%;
            border: 0.3mm solid #dbe3ef;
            background: #ffffff;
            color: #071738;
            font-size: 8pt;
            font-weight: 900;
            line-height: 14mm;
            text-align: center;
            overflow: hidden;
        }

        .brand-logo {
            width: 11.5mm;
            height: 11.5mm;
            margin-top: 1.2mm;
        }

        .brand {
            margin-top: 2mm;
            color: #d4a017;
            font-size: 5.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3.2px;
        }

        .title {
            margin: 3mm 0 0;
            color: #071738;
            font-size: 32pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 7px;
            line-height: 1;
        }

        .subtitle {
            margin-top: 2.5mm;
            color: #64748b;
            font-size: 7pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .gold-line {
            width: 90mm;
            height: 0.55mm;
            margin: 3.5mm auto 0;
            background: #d4a017;
        }

        .recipient {
            position: absolute;
            top: 68mm;
            left: 30mm;
            right: 30mm;
            text-align: center;
        }

        .presented {
            color: #475569;
            font-size: 8pt;
        }

        .student-name {
            margin-top: 2mm;
            color: #020617;
            font-size: 22pt;
            font-weight: 900;
            line-height: 1.08;
        }

        .thin-line {
            width: 120mm;
            height: 0.28mm;
            margin: 3mm auto 0;
            background: #cbd5e1;
        }

        .completion-text {
            margin-top: 3.5mm;
            color: #475569;
            font-size: 8pt;
        }

        .course-name {
            margin-top: 1.5mm;
            color: #071738;
            font-size: 15pt;
            font-weight: 900;
            line-height: 1.1;
        }

        .program-name {
            margin-top: 1.5mm;
            color: #d4a017;
            font-size: 6pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Score Table styling for 1 to 5 sections on Page 1 */
        .score-container-page1 {
            position: absolute;
            top: 114mm;
            left: 45mm;
            right: 45mm;
            text-align: center;
        }

        .score-table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            table-layout: fixed;
            background: rgba(255, 255, 255, 0.95);
            border: 0.3mm solid #cbd5e1;
        }

        .score-table th {
            background: #071738;
            color: #ffffff;
            font-size: 6pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1.8mm 3mm;
            border-bottom: 0.4mm solid #d4a017;
        }

        .score-table td {
            font-size: 6.8pt;
            color: #0f172a;
            padding: 1.6mm 3mm;
            border-bottom: 0.2mm solid #e2e8f0;
            word-wrap: break-word;
        }

        .score-th-no,
        .score-td-no {
            width: 12mm;
            text-align: center;
        }

        .score-th-title,
        .score-td-title {
            text-align: left;
        }

        .score-th-score,
        .score-td-score {
            width: 25mm;
            text-align: right;
            font-weight: 900;
        }

        .score-tr-final td {
            background: #f8fafc;
            border-top: 0.4mm solid #071738;
            font-weight: 900;
        }

        .score-td-final-label {
            text-align: right;
            font-size: 6.5pt;
            letter-spacing: 1px;
            color: #071738;
            text-transform: uppercase;
        }

        .score-td-final-value {
            text-align: right;
            font-size: 7.5pt;
            color: #071738;
        }

        /* Meta Table positioning */
        .meta-table {
            position: absolute;
            left: 55mm;
            right: 55mm;
            top: 145mm;
            border-collapse: separate;
            border-spacing: 3mm 0;
            width: 187mm;
            margin: 0 auto;
        }

        .meta-box {
            padding: 2.5mm 3.5mm;
            border: 0.3mm solid #dbe3ef;
            background: rgba(255, 255, 255, 0.90);
            text-align: center;
        }

        .meta-label {
            color: #94a3b8;
            font-size: 5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .meta-value {
            margin-top: 1mm;
            color: #0f172a;
            font-size: 6.8pt;
            font-weight: 900;
        }

        .bottom-table {
            position: absolute;
            left: 50mm;
            top: 164mm;
            width: 197mm;
            border-collapse: collapse;
        }

        .bottom-cell {
            width: 50%;
            vertical-align: middle;
            text-align: center;
            padding: 0 10mm;
        }

        .signature-image {
            width: 54mm;
            height: 12mm;
            object-fit: contain;
            margin: 0 auto 1.5mm;
            display: block;
        }

        .signature-placeholder {
            height: 12mm;
            margin-bottom: 1.5mm;
        }

        .signature-line {
            width: 68mm;
            height: 0.3mm;
            background: #334155;
            margin: 0 auto 2.5mm;
        }

        .signature-name {
            color: #0f172a;
            font-size: 7pt;
            font-weight: 900;
        }

        .signature-title {
            margin-top: 0.8mm;
            color: #94a3b8;
            font-size: 5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .qr-box {
            display: inline-block;
            width: 29mm;
            padding: 1.5mm;
            border: 0.3mm solid #dbe3ef;
            background: rgba(255, 255, 255, 0.96);
            text-align: center;
        }

        .qr-box img {
            width: 19mm;
            height: 19mm;
            display: block;
            margin: 0 auto;
        }

        .qr-title {
            margin-top: 0.8mm;
            color: #0f172a;
            font-size: 5pt;
            font-weight: 900;
        }

        .qr-subtitle {
            margin-top: 0.4mm;
            color: #94a3b8;
            font-size: 4pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Page 2 styling for > 5 sections */
        .page-break {
            page-break-before: always;
        }

        .page2-container {
            padding: 18mm 22mm;
            background: #ffffff;
            min-height: 210mm;
        }

        .page2-header {
            border-bottom: 0.5mm solid #071738;
            padding-bottom: 4mm;
            margin-bottom: 6mm;
            text-align: left;
        }

        .page2-title {
            font-size: 16pt;
            font-weight: 900;
            color: #071738;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .page2-meta {
            margin-top: 2mm;
            font-size: 8pt;
            color: #475569;
        }

        .page2-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4mm;
            table-layout: fixed;
        }

        .page2-table th {
            background: #071738;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: 900;
            text-transform: uppercase;
            padding: 2.5mm 4mm;
            border: 0.3mm solid #071738;
            letter-spacing: 1px;
        }

        .page2-table td {
            font-size: 8pt;
            color: #0f172a;
            padding: 2.5mm 4mm;
            border: 0.3mm solid #cbd5e1;
            word-wrap: break-word;
        }

        .page2-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .page2-final-tr td {
            background: #f1f5f9 !important;
            font-weight: 900;
            font-size: 9pt;
            border-top: 0.6mm solid #071738;
        }
    </style>
</head>

<body>
    {{-- PAGE 1 --}}
    <div class="certificate">
        @if ($hasTemplateBackground)
        <img
            src="{{ $templateBackgroundPath }}"
            alt="Certificate Template Background"
            class="template-background">
        @else
        <div class="default-border"></div>
        <div class="inner-border"></div>

        <div class="corner corner-top-left"></div>
        <div class="corner corner-top-right"></div>
        <div class="corner corner-bottom-left"></div>
        <div class="corner corner-bottom-right"></div>

        <div class="watermark-left"></div>
        <div class="watermark-right"></div>
        @endif

        <div class="content-layer">
            <div class="header">
                <div class="brand-mark">
                    @if ($hasLogo)
                    <img
                        src="{{ $logoPath }}"
                        alt="Queens English Prestige"
                        class="brand-logo">
                    @else
                    QEP
                    @endif
                </div>

                <div class="brand">
                    Queens English Prestige
                </div>

                <div class="title">
                    Certificate
                </div>

                <div class="subtitle">
                    Of Completion
                </div>

                <div class="gold-line"></div>
            </div>

            <div class="recipient">
                <div class="presented">
                    This certificate is proudly presented to
                </div>

                <div class="student-name">
                    {{ $student?->name ?? 'Student Name' }}
                </div>

                <div class="thin-line"></div>

                <div class="completion-text">
                    for successfully completing the course
                </div>

                <div class="course-name">
                    {{ $courseLevel?->name ?? 'Course Name' }}
                </div>

                <div class="program-name">
                    {{ $courseProgram?->name ?? 'Queens English Prestige Program' }}
                </div>
            </div>

            {{-- Compact score table on Page 1 if 1 to 5 sections --}}
            @if ($hasSectionScores && $sectionCount <= 5)
            <div class="score-container-page1">
                <table class="score-table">
                    <thead>
                        <tr>
                            <th class="score-th-no">No.</th>
                            <th class="score-th-title">Final Exam Section</th>
                            <th class="score-th-score">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sectionScores as $idx => $sec)
                        <tr>
                            <td class="score-td-no">{{ $idx + 1 }}</td>
                            <td class="score-td-title">{{ $sec['title'] ?? 'Section ' . ($idx + 1) }}</td>
                            <td class="score-td-score">{{ isset($sec['score']) ? number_format((float)$sec['score'], 2, '.', '') : '-' }}</td>
                        </tr>
                        @endforeach
                        @if ($finalScoreFormatted !== null)
                        <tr class="score-tr-final">
                            <td colspan="2" class="score-td-final-label">FINAL SCORE</td>
                            <td class="score-td-final-value">{{ $finalScoreFormatted }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif

            <table class="meta-table">
                <tr>
                    <td class="meta-box">
                        <div class="meta-label">Certificate No.</div>
                        <div class="meta-value">{{ $certificate->certificate_number }}</div>
                    </td>

                    <td class="meta-box">
                        <div class="meta-label">Issued Date</div>
                        <div class="meta-value">{{ $certificate->issued_at?->format('d F Y') ?? '-' }}</div>
                    </td>

                    @if ($hasSectionScores && ($isMultiPage || $finalScoreFormatted !== null))
                    <td class="meta-box">
                        <div class="meta-label">Final Score</div>
                        <div class="meta-value">{{ $finalScoreFormatted ?? '-' }}</div>
                    </td>
                    @endif
                </tr>
            </table>

            <table class="bottom-table">
                <tr>
                    <td class="bottom-cell">
                        @if ($hasSignature)
                        <img
                            src="{{ $signaturePath }}"
                            alt="{{ $signerName }}"
                            class="signature-image">
                        @else
                        <div class="signature-placeholder"></div>
                        @endif

                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $signerName }}</div>
                        <div class="signature-title">{{ $signerTitle }}</div>
                    </td>

                    <td class="bottom-cell">
                        @if ($qrDataUri)
                        <div class="qr-box">
                            <img src="{{ $qrDataUri }}" alt="Certificate Verification QR">
                            <div class="qr-title">Verify Certificate</div>
                            <div class="qr-subtitle">Scan to Verify</div>
                        </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- PAGE 2 for > 5 sections --}}
    @if ($hasSectionScores && $isMultiPage)
    <div class="page-break"></div>
    <div class="page2-container">
        <div class="page2-header">
            <div class="page2-title">Final Exam Score Breakdown</div>
            <div class="page2-meta">
                <strong>Student:</strong> {{ $student?->name ?? 'Student Name' }} &nbsp;|&nbsp;
                <strong>Course:</strong> {{ $courseLevel?->name ?? 'Course Name' }} &nbsp;|&nbsp;
                <strong>Certificate No:</strong> {{ $certificate->certificate_number }}
            </div>
        </div>

        <table class="page2-table">
            <thead>
                <tr>
                    <th style="width: 12mm; text-align: center;">No.</th>
                    <th style="text-align: left;">Section Name</th>
                    <th style="width: 35mm; text-align: right;">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sectionScores as $idx => $sec)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="text-align: left;">{{ $sec['title'] ?? 'Section ' . ($idx + 1) }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ isset($sec['score']) ? number_format((float)$sec['score'], 2, '.', '') : '-' }}</td>
                </tr>
                @endforeach
                @if ($finalScoreFormatted !== null)
                <tr class="page2-final-tr">
                    <td colspan="2" style="text-align: right; text-transform: uppercase; letter-spacing: 1px; color: #071738;">FINAL SCORE</td>
                    <td style="text-align: right; color: #071738;">{{ $finalScoreFormatted }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif
</body>

</html>