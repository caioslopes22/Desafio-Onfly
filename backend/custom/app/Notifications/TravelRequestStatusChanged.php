<?php

namespace App\Notifications;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelRequestStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public TravelRequest $travelRequest) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Atualização do pedido de viagem #'.$this->travelRequest->id)
            ->line('Status: '. $this->travelRequest->status)
            ->line('Destino: '. $this->travelRequest->destination)
            ->line('Ida: '. $this->travelRequest->departure_date->format('d/m/Y'))
            ->line('Volta: '. $this->travelRequest->return_date->format('d/m/Y'));
    }

    public function toArray($notifiable): array
    {
        return [
            'travel_request_id' => $this->travelRequest->id,
            'status' => $this->travelRequest->status,
        ];
    }
}
