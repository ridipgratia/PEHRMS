<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Employees PDF</title>
    <style>
        .head_para {
            font-size: 15px;
            font-family: 'Courier New', Courier, monospace;
        }

        .head_para span:first-child {
            font-weight: bold;
        }
    </style>
</head>

<body>
    @php
        $count = 1;
    @endphp
    @foreach ($employees as $employee)
        <p class="head_para"><span>SI NO</span> : <span>{{ $count }}</span></p>
        <p class="head_para"><span>Employee Name</span> : <span>{{ $employee->employe_name }}</span></p>
        <p class="head_para"><span>Employee Email ID</span> : <span>{{ $employee->employe_code }}</span></p>
        <p class="head_para"><span>Employee Phone </span> : <span>{{ $employee->employe_phone }}</span></p>
        <p class="head_para"><span>Employee Designation</span> : <span>{{ $employee->designation_name }}</span></p>
        <p class="head_para"><span>Posted District</span> : <span>{{ $employee->district_name }}</span></p>
        <p class="head_para"><span>Posted Block</span> : <span>{{ $employee->block_name }}</span></p>
        <p class="head_para"><span>Posted Gp </span> : <span>{{ $employee->gram_panchyat_name }}</span></p>
        @php
            $count++;
        @endphp
        <hr>
    @endforeach
</body>

</html>
