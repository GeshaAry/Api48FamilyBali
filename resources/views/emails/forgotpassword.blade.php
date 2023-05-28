<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div style="width:30%; height:400px; background-color: white; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; padding: 20px;">
        <table style="width: 100%; height: 200px;">
            <tr>
                <td >
                    <h1 style="text-align: center;">{{ $mailData['title'] }}</h1>
                </td>
            </tr>
            <tr >
                <td >
                    <p style="text-align: center;">{{ $mailData['body'] }}</p>
                </td>
            </tr>
            <tr >
                <td style=" text-align: center; padding-top: 20px;">
                    <a href="http://localhost:8080/resetpassword/{{ $mailData['email'] }}/{{ $mailData['token'] }}" style=" background-color: #DA1F1A; padding: 20px; text-decoration: none; color: white; border-radius: 10px;" >Reset Password</a>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>