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

    $hasTemplateBackground = $templateBackgroundPath && file_exists($templateBackgroundPath);

    $logoPath = public_path('images/logo-queens-english.png');
    $hasLogo = file_exists($logoPath);
    @endphp

    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 277mm;
            height: 190mm;
            overflow: hidden;
            background: #ffffff;
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
        }

        .certificate {
            position: relative;
            width: 277mm;
            height: 190mm;
            overflow: hidden;
            border: 3mm solid #071738;
            background: #fffdf6;
            text-align: center;
        }

        .template-background {
            position: absolute;
            inset: 0;
            width: 277mm;
            height: 190mm;
            object-fit: cover;
            z-index: 0;
        }

        .content-layer {
            position: relative;
            z-index: 3;
        }

        .inner-border {
            position: absolute;
            top: 5mm;
            left: 5mm;
            right: 5mm;
            bottom: 5mm;
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
            top: 6mm;
            left: 6mm;
            border-left: 0.9mm solid #d4a017;
            border-top: 0.9mm solid #d4a017;
        }

        .corner-top-right {
            top: 6mm;
            right: 6mm;
            border-right: 0.9mm solid #d4a017;
            border-top: 0.9mm solid #d4a017;
        }

        .corner-bottom-left {
            bottom: 6mm;
            left: 6mm;
            border-left: 0.9mm solid #d4a017;
            border-bottom: 0.9mm solid #d4a017;
        }

        .corner-bottom-right {
            bottom: 6mm;
            right: 6mm;
            border-right: 0.9mm solid #d4a017;
            border-bottom: 0.9mm solid #d4a017;
        }

        .watermark-left {
            position: absolute;
            left: -35mm;
            top: 35mm;
            width: 90mm;
            height: 90mm;
            border: 8mm solid rgba(212, 160, 23, 0.08);
            border-radius: 50%;
            z-index: 1;
        }

        .watermark-right {
            position: absolute;
            right: -40mm;
            bottom: 18mm;
            width: 105mm;
            height: 105mm;
            border: 9mm solid rgba(7, 23, 56, 0.06);
            border-radius: 50%;
            z-index: 1;
        }

        .header {
            position: absolute;
            top: 15mm;
            left: 20mm;
            right: 20mm;
            text-align: center;
        }

        .brand-mark {
            width: 16mm;
            height: 16mm;
            margin: 0 auto;
            border-radius: 50%;
            border: 0.3mm solid #dbe3ef;
            background: #ffffff;
            color: #071738;
            font-size: 8.5pt;
            font-weight: 900;
            line-height: 16mm;
            text-align: center;
            overflow: hidden;
        }

        .brand-logo {
            width: 13mm;
            height: 13mm;
            margin-top: 1.4mm;
            object-fit: contain;
        }

        .brand {
            margin-top: 3mm;
            color: #d4a017;
            font-size: 5.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3.2px;
        }

        .title {
            margin: 5mm 0 0;
            color: #071738;
            font-size: 36pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 8px;
            line-height: 1;
        }

        .subtitle {
            margin-top: 3mm;
            color: #64748b;
            font-size: 7.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .gold-line {
            width: 92mm;
            height: 0.55mm;
            margin: 5mm auto 0;
            background: #d4a017;
        }

        .recipient {
            position: absolute;
            top: 78mm;
            left: 20mm;
            right: 20mm;
            text-align: center;
        }

        .presented {
            color: #475569;
            font-size: 8.5pt;
        }

        .student-name {
            margin-top: 3mm;
            color: #020617;
            font-size: 25pt;
            font-weight: 900;
            line-height: 1.08;
        }

        .thin-line {
            width: 130mm;
            height: 0.28mm;
            margin: 4mm auto 0;
            background: #cbd5e1;
        }

        .completion-text {
            margin-top: 5mm;
            color: #475569;
            font-size: 8.5pt;
        }

        .course-name {
            margin-top: 2mm;
            color: #071738;
            font-size: 18pt;
            font-weight: 900;
            line-height: 1.1;
        }

        .program-name {
            margin-top: 2mm;
            color: #d4a017;
            font-size: 6.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.7px;
        }

        .meta-table {
            position: absolute;
            left: 20mm;
            right: 20mm;
            bottom: 54mm;
            width: 237mm;
            border-collapse: separate;
            border-spacing: 3mm 0;
        }

        .meta-box {
            width: 33.333%;
            padding: 3mm 3.5mm;
            border: 0.3mm solid #dbe3ef;
            background: rgba(255, 255, 255, 0.82);
            text-align: left;
        }

        .meta-label {
            color: #94a3b8;
            font-size: 5.2pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.1px;
        }

        .meta-value {
            margin-top: 1.4mm;
            color: #0f172a;
            font-size: 7pt;
            font-weight: 900;
        }

        .signature-table {
            position: absolute;
            left: 28mm;
            right: 28mm;
            bottom: 28mm;
            width: 221mm;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 50%;
            text-align: center;
            padding: 0 18mm;
        }

        .signature-line {
            height: 0.3mm;
            background: #334155;
            margin-bottom: 3mm;
        }

        .signature-name {
            color: #0f172a;
            font-size: 7.2pt;
            font-weight: 900;
        }

        .signature-title {
            margin-top: 1mm;
            color: #94a3b8;
            font-size: 5.2pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .note {
            position: absolute;
            left: 42mm;
            right: 42mm;
            bottom: 16mm;
            color: #64748b;
            font-size: 5.5pt;
            line-height: 1.45;
            text-align: center;
        }

        .seal {
            position: absolute;
            right: 22mm;
            top: 70mm;
            width: 28mm;
            height: 28mm;
            border: 1mm solid rgba(212, 160, 23, 0.28);
            border-radius: 50%;
            color: rgba(212, 160, 23, 0.38);
            font-size: 7pt;
            font-weight: 900;
            line-height: 28mm;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
        }
    </style>
</head>


<body>
    <div class="certificate">
        @if ($hasTemplateBackground)
        <img
            src="{{ $templateBackgroundPath }}"
            alt="Certificate Template Background"
            class="template-background">
        @endif

        <div class="inner-border"></div>

        <div class="corner corner-top-left"></div>
        <div class="corner corner-top-right"></div>
        <div class="corner corner-bottom-left"></div>
        <div class="corner corner-bottom-right"></div>

        @unless ($hasTemplateBackground)
        <div class="watermark-left"></div>
        <div class="watermark-right"></div>
        @endunless

        <div class="seal">QEP</div>

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

                    <td class="meta-box">
                        <div class="meta-label">Final Exam Score</div>
                        <div class="meta-value">
                            {{ $finalExamAttempt ? number_format((float) $finalExamAttempt->total_score, 2) . '%' : '-' }}
                        </div>
                    </td>
                </tr>
            </table>

            <table class="signature-table">
                <tr>
                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-name">Queens English Prestige</div>
                        <div class="signature-title">Authorized Signature</div>
                    </td>

                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $student?->name ?? 'Student' }}</div>
                        <div class="signature-title">Certificate Holder</div>
                    </td>
                </tr>
            </table>

            <div class="note">
                This certificate verifies that the student has completed the required learning activities and passed the final assessment according to Queens English Prestige standards.
            </div>
        </div>
    </div>
</body>

</html>