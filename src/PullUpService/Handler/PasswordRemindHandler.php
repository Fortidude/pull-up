<?php

namespace PullUpService\Handler;

use PullUpDomain\Service\AuthenticationManagerInterface;
use PullUpDomain\Service\ProfileManagerInterface;
use PullUpService\Command\PasswordRemindCommand;

class PasswordRemindHandler
{
    protected $router;
    protected $mailer;
    protected $templating;

    /** @var ProfileManagerInterface */
    protected $userManager;

    public function __construct(ProfileManagerInterface $userManager,
        \Swift_Mailer $mailer,
        $router,
        $templating) {
        $this->templating = $templating;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->userManager = $userManager;
    }

    public function handle(PasswordRemindCommand $command)
    {
        $user = $this->userManager->findUserBy(['email' => $command->email]);
        if (!$user) {
            throw new \Exception("USER_NOT_FOUND");
        }

        $token = bin2hex(random_bytes(24));
        $user->setConfirmationToken($token);

        $this->userManager->update($user);

        $message = (new \Swift_Message('Password reset'))
            ->setFrom('pullup.codevee@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    // app/Resources/views/Emails/registration.html.twig
                    'Emails/password_reminder.html.twig',
                    [
                        'token' => $token,
                        'email' => $user->getEmail(),
                    ]
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);

    }
}
