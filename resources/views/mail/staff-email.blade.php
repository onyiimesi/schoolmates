<DOCTYPE html>
 <html lang=”en-US”>
 <head>
 <meta charset=”utf-8">
 </head>
 <body>
    <h2>Your Account Has Been Successfully Created,</h2>
    <h2>{{$staff['surname']}} {{$staff['firstname']}} {{$staff['middlename']}}</h2><hr>

    <h4>Here is your login details, keep them safely.</h4>
    <p>
        <strong>Username:</strong>
        {{$staff['username']}}
    </p>
    <p>
        <strong>Password:</strong>
        {{$staff['pass_word']}}
    </p>
</body>
</html>