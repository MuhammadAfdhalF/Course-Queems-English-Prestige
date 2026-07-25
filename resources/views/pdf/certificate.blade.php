<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->certificate_number }}</title>

    @php
    // Background Image Resolution
    $templateBackground = $certificate->certificateTemplate?->background_image;
    $templateBackgroundPath = ($templateBackground && \Illuminate\Support\Facades\Storage::disk('public')->exists($templateBackground))
        ? public_path('storage/' . $templateBackground)
        : null;

    $defaultBackgroundPath = public_path('images/certificates/certificate-default-background.jpg');

    $bgPathToUse = null;
    if (extension_loaded('gd') && $templateBackgroundPath && file_exists($templateBackgroundPath)) {
        $bgPathToUse = $templateBackgroundPath;
    } elseif (extension_loaded('gd') && file_exists($defaultBackgroundPath)) {
        $bgPathToUse = $defaultBackgroundPath;
    }

    $hasBackground = !empty($bgPathToUse);

    // Signature Settings
    $signaturePath = $certificateSetting?->signature_image
        ? public_path('storage/' . $certificateSetting->signature_image)
        : null;
    $hasSignature = extension_loaded('gd') && $signaturePath && file_exists($signaturePath);

    $signerName = $certificateSetting?->signerName() ?? 'Queens English Prestige';
    $signerTitle = $certificateSetting?->signerTitle() ?? 'Authorized Signature';

    // Verification QR Code
    $verificationUrl = $certificate->verification_token
        ? route('certificates.verify', $certificate->verification_token)
        : null;

    $qrDataUri = null;
    if ($verificationUrl) {
        try {
            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->margin(0)->generate($verificationUrl);
            if ($qrSvg) {
                $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
            }
        } catch (\Throwable $e) {
            $qrDataUri = null;
        }
    }

    // Student & Birth Info
    $studentName = $student?->name ?? 'Student Name';
    $studentNameLength = mb_strlen($studentName);

    $studentProfile = $student?->studentProfile;
    $birthPlace = trim($studentProfile?->birth_place ?? '');
    $birthDateRaw = $studentProfile?->birth_date;
    $birthDateFormatted = $birthDateRaw ? $birthDateRaw->format('F jS, Y') : null;

    $hasBirthInfo = !empty($birthPlace) && !empty($birthDateFormatted);

    // Course & Date Info
    $courseName = $courseLevel?->name ?? 'English Language Program';
    $issuedDateRaw = $certificate->issued_at ?? $certificate->created_at;
    $completionDateFormatted = $issuedDateRaw ? $issuedDateRaw->format('l, F jS, Y') : date('l, F jS, Y');
    $signingDateFormatted = $issuedDateRaw ? $issuedDateRaw->format('F jS, Y') : date('F jS, Y');

    // Section Scores & Multi-page
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
            height: 210mm;
            background: #ffffff;
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
        }

        .certificate {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            background: #ffffff;
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

        /* Fallback styling if background image is not present */
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

        .content-layer {
            position: relative;
            z-index: 5;
            width: 297mm;
            height: 210mm;
        }

        /* Title Area (Centered between left ribbon and right margin) */
        .title-area {
            position: absolute;
            top: 43mm;
            left: 55mm;
            right: 25mm;
            text-align: center;
        }

        .cert-title {
            color: #0c1e38;
            font-size: 19.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
            line-height: 1.1;
        }

        .cert-number {
            color: #0f172a;
            font-size: 10.5pt;
            font-weight: bold;
            margin-top: 1.5mm;
        }

        .recipient-intro {
            color: #1e293b;
            font-size: 10.5pt;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 3.5mm;
        }

        /* Dynamic Student Name Styling */
        .student-name {
            color: #c68b29;
            font-weight: bold;
            margin-top: 2mm;
            line-height: 1.1;
            padding: 0 5mm;
        }

        .student-name-large {
            font-size: 25pt;
        }

        .student-name-medium {
            font-size: 19pt;
        }

        .student-name-small {
            font-size: 15pt;
        }

        /* Student Description */
        .description-text {
            color: #1e293b;
            font-size: 9.2pt;
            line-height: 1.45;
            margin-top: 3mm;
            padding: 0 10mm;
        }

        .description-text strong {
            color: #0f172a;
            font-weight: bold;
        }

        /* Score Table Container */
        .score-container-page1 {
            margin-top: 2.5mm;
            text-align: center;
        }

        .score-header-label {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 1.5mm;
        }

        .score-table {
            width: 54%;
            margin: 0 auto;
            border-collapse: collapse;
            border: 1.5px solid #000000;
            background: #ffffff;
        }

        .score-table td {
            border: 1.5px solid #000000;
            padding: 2.2px 8px;
            font-size: 8.5pt;
            color: #000000;
        }

        .score-title-td {
            text-align: left;
            width: 72%;
        }

        .score-val-td {
            text-align: center;
            font-weight: bold;
            width: 28%;
        }

        .score-total-tr td {
            font-weight: bold;
            font-size: 9pt;
            background: #ffffff;
        }

        .validity-note {
            font-size: 7.5pt;
            font-style: italic;
            color: #000000;
            margin-top: 1.5mm;
        }

        /* Bottom-Left Dynamic Verification QR Code Block (To the right of the left ribbon) */
        .qr-verification-block {
            position: absolute;
            bottom: 12mm;
            left: 68mm;
            width: 44mm;
            text-align: center;
            z-index: 10;
        }

        .qr-verification-block img {
            width: 19mm;
            height: 19mm;
            display: block;
            margin: 0 auto;
        }

        .qr-label {
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            color: #0f172a;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        .qr-sublabel {
            font-size: 6.5pt;
            color: #475569;
            margin-top: 0.5mm;
        }

        /* Bottom-Right Signature Block */
        .signature-area {
            position: absolute;
            bottom: 12mm;
            right: 28mm;
            width: 78mm;
            text-align: center;
            z-index: 10;
        }

        .signing-date {
            font-size: 9.5pt;
            font-weight: bold;
            color: #000000;
            margin-bottom: 1mm;
        }

        .signature-img-container {
            height: 15mm;
            margin: 0.5mm auto;
        }

        .signature-img {
            max-height: 15mm;
            max-width: 58mm;
            object-fit: contain;
        }

        .signer-name {
            font-size: 11pt;
            font-weight: bold;
            color: #c68b29;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1mm;
        }

        .signer-title {
            font-size: 9.5pt;
            color: #1e293b;
            margin-top: 0.5mm;
        }

        /* Page 2 for > 5 sections */
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
            font-weight: bold;
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
            font-weight: bold;
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
            font-weight: bold;
            font-size: 9pt;
            border-top: 0.6mm solid #071738;
        }
    </style>
</head>

<body>
    {{-- PAGE 1 --}}
    <div class="certificate">
        @if ($hasBackground)
        <img src="{{ $bgPathToUse }}" alt="Certificate Background" class="template-background">
        @else
        <div class="default-border"></div>
        <div class="inner-border"></div>
        @endif

        <div class="content-layer">
            <div class="title-area">
                <div class="cert-title">CERTIFICATE OF ACHIEVEMENT</div>
                <div class="cert-number">No: {{ $certificate->certificate_number }}</div>
                <div class="recipient-intro">THIS CERTIFICATE IS GRANTED TO :</div>

                {{-- Dynamic Student Name with Length Scaling --}}
                @if ($studentNameLength <= 25)
                <div class="student-name student-name-large">{{ $studentName }}</div>
                @elseif ($studentNameLength <= 40)
                <div class="student-name student-name-medium">{{ $studentName }}</div>
                @else
                <div class="student-name student-name-small">{{ $studentName }}</div>
                @endif

                {{-- Student & Course Description --}}
                <div class="description-text">
                    @if ($hasBirthInfo)
                    born in {{ $birthPlace }}, on {{ $birthDateFormatted }} for the completion of
                    @else
                    for the completion of
                    @endif
                    <br>
                    <strong>{{ $courseName }}</strong> on {{ $completionDateFormatted }}.
                </div>

                {{-- Compact Score Table on Page 1 if 1 to 5 sections --}}
                @if ($hasSectionScores && $sectionCount <= 5)
                <div class="score-container-page1">
                    <div class="score-header-label">TOEFL Prediction Score:</div>
                    <table class="score-table">
                        <tbody>
                            @foreach ($sectionScores as $idx => $sec)
                            <tr>
                                <td class="score-title-td">{{ $sec['title'] ?? 'Section ' . ($idx + 1) }}</td>
                                <td class="score-val-td">{{ isset($sec['score']) ? number_format((float)$sec['score'], 0) : '-' }}</td>
                            </tr>
                            @endforeach
                            @if ($finalScoreFormatted !== null)
                            <tr class="score-total-tr">
                                <td class="score-title-td">Total Score:</td>
                                <td class="score-val-td">{{ $finalScoreFormatted }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="validity-note">This prediction score is valid for 6 months from the date of issuance</div>
                </div>
                @endif
            </div>

            {{-- Bottom-Left Dynamic Verification QR Code Block --}}
            @if ($qrDataUri)
            <div class="qr-verification-block">
                <img src="{{ $qrDataUri }}" alt="Verification QR Code">
                <div class="qr-label">SCAN TO VERIFY</div>
                <div class="qr-sublabel">Verify Certificate</div>
            </div>
            @endif

            {{-- Bottom-Right Signature Block --}}
            <div class="signature-area">
                <div class="signing-date">Pekanbaru, {{ $signingDateFormatted }}</div>

                <div class="signature-img-container">
                    @if ($hasSignature)
                    <img src="{{ $signaturePath }}" alt="{{ $signerName }}" class="signature-img">
                    @endif
                </div>

                <div class="signer-name">{{ $signerName }}</div>
                <div class="signer-title">{{ $signerTitle }}</div>
            </div>
        </div>
    </div>

    {{-- PAGE 2 for > 5 sections --}}
    @if ($hasSectionScores && $isMultiPage)
    <div class="page-break"></div>
    <div class="page2-container">
        <div class="page2-header">
            <div class="page2-title">Final Exam Score Breakdown</div>
            <div class="page2-meta">
                <strong>Student:</strong> {{ $studentName }} &nbsp;|&nbsp;
                <strong>Course:</strong> {{ $courseName }} &nbsp;|&nbsp;
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