Hello  {{$user->name}}
thank you for creating an account with us please verify your email using this link:
{{route('verify', $user->verfication_token)}}