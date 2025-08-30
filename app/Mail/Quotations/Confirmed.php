<?php

namespace App\Mail\Quotations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Confirmed extends Mailable
{
    use Queueable, SerializesModels;

    protected $type;

    protected $info;

    protected $model;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info, $type, Model $model)
    {
        $this->info = $info;
        $this->model = $model;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $settings = settings()->getMany([
            'sent_from_name', 'sent_from_email',
            'global_bcc_email'
        ]);


        if(!is_null($settings['global_bcc_email'])) {
            $bcc[] = $settings['global_bcc_email'];
        }

        $pdf = pdfRaw('docs.'.$this->type, $this->model);

        
        $requester = User::where('id','=',$this->model->user_id)->value('email');
        $manager_id= User::where('id','=',$this->model->user_id)->value('manager_id');
        $manager = User::where('id','=',$manager_id)->value('email');
        $fileName = time().'-'.$this->model->number.'.pdf';


        $mail =  $this
        ->from($settings['sent_from_email'], $settings['sent_from_name'])
        ->subject('Quotation  #'.$this->model->number.' - Approved')
        ->view('email.quotation.confirmed', [
            'data' => $this->model
        ])
        ->attachData($pdf->Output('', 'S'), $fileName,[
            'mime' => 'application/pdf',
        ]);
        
        $mail->to($requester);
        $mail->cc($manager);
        return $mail;
        
    }
}
