<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Feature Access Restricted</h2>
    <p>Hello {{ $school->schname }},</p>

    <p>
        One or more features of your school portal are currently not available to your school.
    </p>

    <p>
        <strong>Feature:</strong> {{ $feature->name }} ({{ $feature->key }})<br>
        <strong>Plan:</strong> {{ $school->plan?->name ?? 'No plan assigned' }}<br>
        <strong>Reason:</strong> {{ $reason }}
    </p>

    <p>
        If you believe this is an error, or you would like this feature enabled for your school,
        please contact the school administrator or our support team.
    </p>

    <p>
        Best regards,<br>
        SchoolMates Team
    </p>
</body>
</html>
