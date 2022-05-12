<h1 style="text-align: center;">בקשה לעדכון שעות</h1>
<table style="text-align: center;">
    <thead>
    <tr>
        <th>שם</th>
        <th>תאריך</th>
        <th>כניסה</th>
        <th>יציאה</th>

        <th>כניסה חדש</th>
        <th>יציאה חדש</th>
    </tr>
    </thead>
    <tbody>
    <tr style="border: 1px solid">
        <td style="text-align: center; width: 150px">{{ $name }}</td>
        <td style="text-align: center; width: 150px">{{ $date }}</td>
        <td style="text-align: center; width: 150px">{{ $in }}</td>
        <td style="text-align: center; width: 150px">{{ $out }}</td>
        <td style="text-align: center; width: 150px;"><strong>{{ $in_new }}</strong></td>
        <td style="text-align: center; width: 150px"><strong>{{ $out_new }}</strong></td>
    </tr>
    </tbody>
</table>