<DOCTYPE html>
 <html lang=”en-US”>
 <head>
 <meta charset=”utf-8">
 </head>
 <body>
    <h2>Dear {{$student['surname']}} {{$student['surname']}},</h2>
    <h2>{{$student['admission_number']}}</h2><hr>

    <p>
        Congratulations and welcome to [School Name]'s portal! We are thrilled to have you join our online community and embark on your academic journey with us. Your registration as a student on our portal marks the beginning of an exciting chapter filled with opportunities for learning, growth, and engagement.
    </p>
    <p>
        Find Below your Login Details:
    </p>
    <p>
        <a href="https://portal.schoolmateglobal.com/auth">Login Link</a>
    </p>
    <p>
        <strong>Username:</strong>
        {{$student['username']}}
    </p>
    <p>
        <strong>Password:</strong>
        {{$student['pass_word']}}
    </p>
    <p>
        Here's what you can expect as a registered student:
    </p>
    <p>
        1.	Personalized Dashboard: Access your personalized dashboard, where you can view your course schedule, assignments, grades, and important announcements tailored to your academic program.
    </p>
    <p>
        2.	Course Materials and Resources: Explore a wealth of course materials, resources, and interactive content uploaded by your instructors to support your learning objectives and academic success.
    </p>
    <p>
        3.	Collaborative Learning Environment: Engage with your classmates, participate in discussions, and collaborate on group projects through our portal's interactive features, fostering a dynamic and collaborative learning environment.
    </p>
    <p>
        4.	Communication Channels: Connect with your instructors, academic advisors, and fellow students through our communication channels, including messaging, discussion forums, and virtual classrooms, enabling seamless communication and collaboration.
    </p>
    <p>
        5.	Support Services: Access support services and resources, including academic advising, tutoring, and counseling, to ensure you have the necessary support and guidance to thrive in your academic journey.
    </p>
    <p>
        We encourage you to explore the various features and functionalities of our portal to make the most out of your experience. Should you have any questions, encounter any difficulties, or require assistance, please don't hesitate to reach out to our dedicated support team at [Support Email or Contact Information].
    </p>
    <p>
        Once again, welcome to {{$student['sch_id']}}'s portal! We are committed to supporting you every step of the way and providing you with a rewarding and enriching educational experience.
    </p>
    <p>
        Best regards,
    </p>
    <p>
        {{ $student['sch_id'] }} Student {{ $student['sch_id'] }}
    </p>
</body>
</html>
