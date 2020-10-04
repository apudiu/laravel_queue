## Requirements
    - 1. Custom mailer
        1. With (mail provider) fallback support
        2. Use at least 2 mail providers
        3. Way to add fallbacks easily in future
        4. Keep log of sent email
        
    - 2. API for sending email
        1. A JSON api  
        2. CLI command
        
    - 3. Queueing
        1. Process email sending with queue
        2. Microservice should be horizontally scaleable
        
    
## Implementations
    - 1. Custom mailer
        => 
        Implemented with "PHP mailer", By default laravel use swift mailer.
        Used PHP mailer to avoid low level implementation details, like setting up headers & many other things.
            
        1. With (mail provider) fallback support
        =>
        Here we used 2 email providers, Sendgrid (default) & Mail2go.
        When requested mailer will try to send mail using default if that failed
        then mailer will try next available provider as fallback(s).
        
        2. Use at least 2 mail providers
        =>
        Used 2 providers
        
        3. Way to add fallbacks easily in future
        =>
        In current implementation we can add as many fallback as we want.
        We can do so in `config > mail.php > mailProviders`. Mailer will try out all available fallbacks automatically
        if needed.
                
        4. Keep log of sent email
        => 
        This is not implemented. But we can keep a simple log (in DB). As we didn't used authentication here, there will be no user information.
        Just 
            1. recepient [email] 
            2. sent using [provider], 
            3. [status] & 
            4. [time] when sent 
        can be there. For this we can use any logger or write our won.
            
    - 2. API for sending email
        1. A JSON api
        =>
        An API route is defined which is `{domain:port}/api/sendmail`.
        We can send post request to it containing `times` body paramenter which expects a number.
        If the request pass minimalistic validation then this will queue n(`{times}`) imails.        
          
        2. CLI command
        =>
        `sendmail` is defined as a simple(st) command.
        This command can be executed like `php artisan sendmail`. This will que ue one email which is pre defined & not customizeable right now.
        Here we didn't taken any argument like API route.
        
    - 3. Queueing
        1. Process email sending with queue
        =>
        Here we used simple queueing mechanism. Of course we can go much further than this.
        We can use `laravel horizon` plugin which is better suited. As a queue driver we used database. We know that at scale
        redis will be much more suitable.
        
        2. Microservice should be horizontally scaleable
        =>
        This feature is not implemented. But we can archive this using laravel horizon package.
        In that case we have to use `Redis` as queue driver, Then if we scale the microservice
        & all services are connected to same resis (/ cluster) this will not be an issue.

## Techniques used
    1. Abstraction
    =>
    Used "CustomMailer" in the "CustomMailable" type. But custom mailer is an interface. 
    Bount CustomMailer with "CustomPHPMailer" class in the (service) container.
    So any time we can change underlying CustomPHPMailer with anything else & we can mock it for easy testing too.

    *Queue driver is abstracted by the framework
