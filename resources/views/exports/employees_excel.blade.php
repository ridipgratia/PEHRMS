<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Employees Excel</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>
                    Employee Name
                </th>
                <th>
                    Email ID
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->employe_name }}</td>
                    <td>{{ $employee->employe_email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
