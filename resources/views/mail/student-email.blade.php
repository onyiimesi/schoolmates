<DOCTYPE html>
 <html lang=”en-US”>
 <head>
 <meta charset=”utf-8">
 </head>
 <body>
    <h2>Your Account Has Been Successfully Created With Admission Number</h2>
    <h2>{{$student['admission_number']}}</h2><hr>

    <h4>Here is your login details, keep them safely.</h4>
    <p>
        <strong>Username:</strong>
        {{$student['username']}}
    </p>
    <p>
        <strong>Password:</strong>
        {{$student['pass_word']}}
    </p>
</body>
</html>