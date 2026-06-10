<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PaymentTransaction $transaction,
        public string $payUrl,
    ) {
    }

    public function build(): self
    {
        $this->transaction->loadMissing([
            'booking.guest',
            'booking.studio',
            'booking.servicePackage',
        ]);

        return $this
            ->subject('Selesaikan Pembayaran Booking - ' . $this->transaction->invoice_number)
            ->view('emails.booking-pending', [
                'transaction' => $this->transaction,
                'booking' => $this->transaction->booking,
                'payUrl' => $this->payUrl,
            ]);
    }
}
