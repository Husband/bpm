<?php

namespace ProcessMaker\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use ProcessMaker\Model\Application as Instance;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;

class ProcessCompletedNotification extends Notification
{
    use Queueable;

    private $processUid;
    private $processName;
    private $instanceUid;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ExecutionInstanceInterface $instance)
    {
        $this->processUid = $instance->process->uid;
        $this->processName = $instance->process->name;
        $this->instanceUid = $instance->uid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toBroadcast($notifiable)
    {
        $instance = Instance::where('uid', $this->instanceUid)->first();
        return new BroadcastMessage([
            'name' => sprintf('Completed: %s', $this->processName),
            'dateTime' => $instance->APP_FINISH_DATE->toIso8601String(),
            'uid' => $this->processName,
            'url' => '/process',
        ]);

    }

}
