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
        <p class="title">M E R C H A N D I S E</p>
    </div>
    <p class="text-name">Date Buy: {{ $transaction->merchandisetns_datebuy }}</p>
    <p class="text-name">Name: {{ $transaction->user->user_name }}</p>
    <table style="margin: 0px auto;">
        <tr style="background-color:#DA1F1A; text-align:center;">
            <td style="padding:20px; font-weight:700;">MERCHANDISE NAME</td>
            <td style="padding:20px; font-weight:700;">MERCHANDISE SIZE</td>
            <td style="padding:20px; font-weight:700;">MERCHANDISE PRICE</td>
            <td style="padding:20px; font-weight:700;">QUANTITY</td>
            <td style="padding:20px; font-weight:700;">TOTAL PRICE</td>
        </tr>
        <tr style="background-color: #E3E3E3; text-align:center;">
            <td style="padding:20px; color:black;">{{ $transaction->merchandisevariant->merchandise->merchandise_name }}</td>
            <td style="padding:20px; color:black;">{{ $transaction->merchandisevariant->merchandisevar_size }}</td>
            <td style="padding:20px; color:black;">{{ $transaction->merchandisevariant->merchandisevar_price  }}</td>
            <td style="padding:20px; color:black;">{{ $transaction->merchandisetns_quantity }}</td>
            <td style="padding:20px; color:black;">{{ $transaction->merchandisetns_totalprice }}</td>
        </tr>
    </table>
    <p class="text-thanks">Thank You For Purchasing</p>
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