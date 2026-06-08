@extends('layouts.master')
@section('css')
    <style>
        .id-card-page {
            display: flex;
            justify-content: center;
            padding: 30px 0;
        }

        .id-card {
            width: 360px;
            max-width: 100%;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            background-color: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .id-card-header {
            text-align: center;
            margin-bottom: 18px;
        }

        .id-card-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #007bff;
            margin: 0 auto 12px;
        }

        .id-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .id-card-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .id-card-subtitle {
            margin: 4px 0 0;
            color: #555;
            font-size: 0.95rem;
        }

        .id-card-section {
            margin-top: 18px;
        }

        .id-card-section h6 {
            margin-bottom: 12px;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #333;
        }

        .id-card-table {
            width: 100%;
            border-collapse: collapse;
        }

        .id-card-table td {
            padding: 6px 0;
            font-size: 0.92rem;
            color: #333;
        }

        .id-card-table td.label {
            width: 42%;
            color: #6c757d;
        }

        .id-card-signatures {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .id-card-signature {
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #999;
            margin: 50px 0 8px;
        }

        .print-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                background: #fff;
            }

            .id-card {
                box-shadow: none;
                border: 1px solid #000;
            }
        }
    </style>
@endsection
@section('title')
    Student ID Card
@stop
@section('page-header')
@section('PageTitle')
    Student ID Card
@stop
@endsection
@section('content')
    <div class="row id-card-page">
        <div class="col-md-6">
            <div class="id-card">
                <div class="d-flex justify-content-between align-items-center no-print mb-3">
                    <div>
                        <strong>Student ID Card</strong>
                    </div>
                    <button class="btn btn-primary btn-sm print-button" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>

                <div class="id-card-header">
                    <div class="id-card-photo">
                        @php
                            $photo = optional($student->images->first())->filename;
                            $photoUrl = $photo ? asset('attachments/students/' . $student->name . '/' . $photo) : 'https://via.placeholder.com/120x120?text=Photo';
                        @endphp
                        <img src="{{ $photoUrl }}" alt="Student Photo">
                    </div>
                    <h3 class="id-card-title">{{ $student->name }}</h3>
                    <p class="id-card-subtitle">{{ optional($student->grade)->Name ?: 'N/A' }}</p>
                </div>

                <div class="id-card-section">
                    <h6>Main Details</h6>
                    <table class="id-card-table">
                        <tr>
                            <td class="label">Class</td>
                            <td>{{ optional($student->currentFiscalDetail->classroom)->Name_Class ?: optional($student->classroom)->Name_Class ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Faculty</td>
                            <td>{{ optional($student->currentFiscalDetail->faculty)->faculty_name ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Roll No</td>
                            <td>{{ optional($student->currentFiscalDetail)->roll_no ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Date of Birth</td>
                            <td>{{ $student->Date_Birth ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Blood Group</td>
                            <td>{{ $bloodGroup ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="id-card-section">
                    <h6>Parent Information</h6>
                    <table class="id-card-table">
                        <tr>
                            <td class="label">Father's Name</td>
                            <td>{{ optional($student->myparent)->Name_Father ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Address</td>
                            <td>{{ optional($student->myparent)->Address_Father ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="id-card-signatures">
                    <div class="id-card-signature">
                        <div class="signature-line"></div>
                        <div>Principal Signature</div>
                    </div>
                    <div class="id-card-signature">
                        <div class="signature-line"></div>
                        <div>Guardian Signature</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
