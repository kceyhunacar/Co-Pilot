<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<style>
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        /* font-family: sans-serif; */
        width: 100%;


    }

    .styled-table thead tr {
        background-color: #29367a;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 0px 15px;
        text-align: center;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-top: 5px solid #29367a;
        border-bottom: 5px solid #29367a;
    }

    .styled-table tbody tr:first-of-type {
        border-top: 3px solid #29367a;
        border-bottom: 3px solid #29367a;
    }

    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #29367a;
    }

    .info {
        border-top: 1px solid #29367a !important;
        border-bottom: 1px solid #29367a !important;
    }

    .info-table {
        width: 50% !important;
    }
</style>


<table class="styled-table info-table">

    <tbody>
        <tr class="info">
            <td style="text-align: left"><b>Muavin Defter</b></td>
            <td style="text-align: left"></td>
        </tr>
        <tr class="info">
            <td style="text-align: left">Firma:</td>
            <td style="text-align: left">{{ $company->title }}</td>
        </tr>
        <tr class="info">
            <td style="text-align: left">Tarih:</td>

            <td style="text-align: left">{{ \Carbon\Carbon::parse($start_date)->format('d.m.Y') }} / {{ \Carbon\Carbon::parse($end_date)->format('d.m.Y') }}</td>

        </tr>


    </tbody>
</table>


<table class="styled-table">
    <thead>
        <tr>
            <th>Tarih</th>
            <th>F.No</th>
            <th>Açıklama</th>
            <th style="text-align: right;">Borç</th>
            <th style="text-align: right;">Alacak</th>
            <th style="text-align: right;">Bakiye</th>
            <th>B/A</th>

        </tr>
    </thead>
    <tbody style="text-align: center">
        <tr>
            <td></td>

            <td></td>
            <td><b>Nakli Yekün:</b></td>
            <td></td>

            <td></td>
            <td style="text-align: right;">{{ number_format(abs($balance), 2, ',', '.') }}</td>


            <th>{{ $balance > 0 ? 'A' : 'B' }}</th>
        </tr>

        @php

            $credit = 0;
            $debt = 0;
            
        @endphp
        @foreach ($total as $item)
            @php

                // if ($item->invoice_id == 2) {
                //     $balance = $balance - $item->total;
                //     $debt = $debt + $item->total;
                // } else {
                //     $balance = $balance + $item->total;
                //     $credit = $credit + $item->total;
                // }
                if ($item->type == 1) {
                    $balance -= $item->total;
                    $debt += $item->total;
                } elseif ($item->type == 2) {
                    $balance += $item->total;
                    $credit += $item->total;
                } elseif ($item->invoice_id == 2) {
                    $balance += $item->total;
                    $credit += $item->total;
                } elseif ($item->invoice_id == 3) {
                    $balance -= $item->total;
                    $debt += $item->total;
                }

            @endphp


            <tr>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d.m.Y') }}</td>
                <td>{{ $item->no }}</td>
                @if ($company_type == 'partner' && $item->invoice_id == 2)
                    <td>Acenta Faturası</td>
                @elseif($company_type == 'partner' && $item->invoice_id == 3)
                    <td>Komisyon Faturası</td>
                @elseif($item->type == 1)
                    <td>Gelen Ödeme</td>
                @elseif($item->type == 2)
                    <td>Giden Ödeme</td>
                @else
                    <td></td>
                @endif


                @if ($item->type == 1)
                    <td style="text-align: right;">{{ number_format($item->total, 2, ',', '.') }}</td>
                    <td style="text-align: right;"></td>
                @elseif($item->type == 2)
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">{{ number_format($item->total, 2, ',', '.') }}</td>
                @elseif($item->invoice_id == 2)
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">{{ number_format($item->total, 2, ',', '.') }}</td>
                @elseif($item->invoice_id == 3)
                    <td style="text-align: right;">{{ number_format($item->total, 2, ',', '.') }}</td>
                    <td style="text-align: right;"></td>
                @endif
                {{-- <td style="text-align: right;">{{ $item->patient_id == null ? number_format($item->total, 2, ',', '.') : '' }}</td>
                    <td style="text-align: right;">{{ $item->patient_id != null ? number_format($item->total, 2, ',', '.') : '' }}</td> --}}


                <td style="text-align: right;">{{ number_format(abs($balance), 2, ',', '.') }}</td>
                <th>{{ $balance > 0 ? 'A' : 'B' }}</th>
            </tr>
        @endforeach


        <tr>
            <td></td>

            <td></td>
            <td><b>Hesap Toplamı:</b></td>

            <td style="text-align: right;">{{ number_format(abs($debt), 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format(abs($credit), 2, ',', '.') }}</td>


            <td style="text-align: right;">{{ number_format(abs($balance), 2, ',', '.') }}</td>
            <th>{{ $balance > 0 ? 'A' : 'B' }}</th>
        </tr>

    </tbody>
</table>
