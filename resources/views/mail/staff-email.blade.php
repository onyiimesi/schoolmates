<DOCTYPE html>
 <html lang=”en-US”>
 <head>
 <meta charset=”utf-8">
 </head>
 <body>
    <h2>Welcome to {{ $staff['sch_id'] }}'s' Portal,</h2>
    <h3>{{$staff['surname']}} {{$staff['firstname']}} {{$staff['middlename']}},</h3>

    <p>
        We are delighted to inform you that your registration as a staff member on {{ $staff['sch_id'] }}'s portal has been successfully completed. Welcome aboard!
    </p>
    <p>
        Your presence on our portal is integral to our school community, and we are excited to have you join us in our mission to provide excellence in education and support to our students.
    </p>
    <p>
        Find Below your Login Details Login Link.
    </p>
    <p>
        <strong>Username:</strong>
        {{$staff['username']}}
    </p>
    <p>
        <strong>Password:</strong>
        {{$staff['pass_word']}}
    </p>
    <p>
        Here's what you can expect as a staff member on our portal:
    </p>
    <p>
        1.	Access to Administrative Tools: Utilize our portal's administrative tools to manage your courses, schedule, and other administrative tasks efficiently.
    </p>
    <p>
        2.	Collaborative Workspace: Engage with your colleagues and fellow staff members through our collaborative workspace, fostering teamwork and innovation in our educational initiatives.
    </p>
    <p>
        3.	Resource Repository: Explore our extensive resource repository, where you can find and share educational materials, best practices, and innovative teaching strategies to enhance the learning experience for our students.
    </p>
    <p>
        4.	Communication Channels: Stay connected with your peers and the broader school community through our communication channels, facilitating open dialogue and collaboration across departments.
    </p>
    <p>
        5.	Professional Development Opportunities: Take advantage of professional development opportunities offered through our portal, including workshops, training sessions, and online courses to further your skills and expertise in your field.
    </p>
    <p>
        We encourage you to familiarize yourself with the various features and functionalities of our portal to maximize your experience and contribute to our shared success.
    </p>
    <p>
        Should you have any questions or require assistance navigating the portal, please do not hesitate to reach out to the School Administrator.
    </p>
    <p>
        Once again, welcome to {{ $staff['sch_id'] }}'s portal! We are thrilled to have you join our team and look forward to working together to create an enriching and supportive learning environment for our students.
    </p>
    <p>
        Best regards,
    </p>
    <p>
        {{ $staff['sch_id'] }} {{ $staff['designation_id'] }} {{ $staff['sch_id'] }}
    </p>
</body>
</html>
