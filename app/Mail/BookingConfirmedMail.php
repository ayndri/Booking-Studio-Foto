<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PaymentTransaction $transaction)
    {
    }

    public function build(): self
    {
        $booking = $this->transaction->booking;

        $pdf = Pdf::loadView('pdf.invoices.invoice', [
            'transaction' => $this->transaction,
            'booking' => $booking,
        ])->setPaper('a4', 'portrait');

        return $this
            ->subject('Booking Dikonfirmasi - ' . $this->transaction->invoice_number)
            ->view('emails.booking-confirmed', [
                'transaction' => $this->transaction,
                'booking' => $booking,
            ])
            ->attachData(
                $pdf->output(),
                'invoice-' . $this->transaction->invoice_number . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
