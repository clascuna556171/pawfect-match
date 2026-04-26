<!DOCTYPE html>
<html>
<head>
    <title>Post-Adoption Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hi {{ $application->user->name }},</h2>
    
    <p>It's been exactly a week since you adopted {{ $application->pet->name }}! We would love to hear how things are going and see how your new companion is settling in.</p>
    
    <p>Please click the link below to share a quick update and a photo with us:</p>
    
    <p style="margin: 20px 0;">
        <a href="{{ route('updates.create', $application) }}" style="background-color: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Post an Update</a>
    </p>
    
    <br>
    <p>Thanks,<br>The PawfectMatch Team</p>
</body>
</html>
