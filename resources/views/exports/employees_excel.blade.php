<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Employees Excel</title>
    <style>
        .table_header {
            background: rgb(65, 65, 151);
        }

        .table_row {
            text-align: center
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th style="font-weight:bold;width:100px;">
                    SI NO
                </th>
                <th style="font-weight:bold;width:230px;">
                    EMPLOYEE NAME
                </th>
                <th style="font-weight:bold;width:230px;">
                    EMPLOYEE CODE
                </th>
                <th style="font-weight:bold;width:230px;">
                    PHONE NUMBER
                </th>
                <th style="font-weight:bold;width:230px;">
                    DESIGNATION
                </th>
                <th style="font-weight:bold;width:230px;">
                    POSTED DISTRICT
                </th>
                <th style="font-weight:bold;width:230px;">
                    POSTED BLOCK
                </th>
                <th style="font-weight:bold;width:230px;">
                    POSTED GP
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @foreach ($employees as $employee)
                <tr>
                    <td style="text-align: left">{{ $count }}</td>
                    <td style="text-align: left">{{ $employee->employe_name }}</td>
                    <td style="text-align: left">{{ $employee->employe_code }}</td>
                    <td style="text-align: left">{{ $employee->employe_phone }}</td>
                    <td style="text-align: left">{{ $employee->designation_name }}</td>
                    <td style="text-align: left">{{ $employee->district_name }}</td>
                    <td style="text-align: left">{{ $employee->block_name }}</td>
                    <td style="text-align: left">{{ $employee->gram_panchyat_name }}</td>
                </tr>
                @php
                    $count++;
                @endphp
            @endforeach
        </tbody>
    </table>
</body>

</html>
