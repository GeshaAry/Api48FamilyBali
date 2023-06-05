<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="wrap-title">
        <p class="title">REPORT MERCHANDISE</p>
    </div>
    <table>
        <tr style="background-color:#DA1F1A; text-align:center;">
            <td style="padding:20px; font-weight:700;">NO</td>
            <td style="padding:20px; font-weight:700;">DATE</td>
            <td style="padding:20px; font-weight:700;">MERCHANDISE NAME</td>
            <td style="padding:20px; font-weight:700;">MERCHANDISE SIZE</td>
            <td style="padding:20px; font-weight:700;">MERCHANDISE PRICE</td>
            <td style="padding:20px; font-weight:700;">QUANTITY</td>
            <td style="padding:20px; font-weight:700;">TOTAL PRICE</td>
        </tr>
        @php $i=1 @endphp
            @foreach($reports as $report)
            @php $harga = App\Http\Controllers\Api\TransactionMerchandiseController::RupiahFormat($report->merchandisetns_totalprice) @endphp
            @php $totals = App\Http\Controllers\Api\TransactionMerchandiseController::RupiahFormat($total) @endphp
            <tr style="background-color: #E3E3E3; text-align:center;">
                <td style="padding:20px; color:black;">{{ $i++ }}</td>
                <td style="padding:20px; color:black;">{{date('d/m/Y',strtotime($report->merchandisetns_datebuy))}}</td>
                <td style="padding:20px; color:black;">{{$report->merchandisevariant->merchandise->merchandise_name}}</td>
                <td style="padding:20px; color:black;">{{$report->merchandisevariant->merchandisevar_size}}</td>
                <td style="padding:20px; color:black;">{{$report->merchandisevariant->merchandisevar_price}}</td>
                <td style="padding:20px; color:black;">{{$report->merchandisetns_quantity}}</td>
                <td style="padding:20px; color:black;">Rp{{ $harga }} </td>
            </tr>
        @endforeach
        <tr style="background-color: #E3E3E3; text-align:center;">
            <td style="padding:20px; color:black;" colspan="6">ALL TOTAL</td>
            <td style="padding:20px; color:black;">Rp{{ $totals }} </td>
        </tr>
    </table>
</body>
<style>
    .wrap-title{
        width: 100%;
        height: 100px;
        background-color: #DA1F1A;
        font-family: 'Inter', sans-serif;
    }
    .title{
        text-align: center;
        font-weight: 700;
        color: white;
        font-size: 24px;
        line-height: 70px;
    }
    
    table{
        font-family: 'Inter', sans-serif;
        color: white;
        width: 100%;
    }

    .text-name{
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 24px;
        color: #DA1F1A;
    }
    .text-thanks{
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 24px;
        color: #DA1F1A;
        text-align: center;
    }
</style>
</html>