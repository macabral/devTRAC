<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Models\Emails;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendmailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendmail:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email from emails table';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $config = config('mail.mailers.smtp');
        $from = config('mail.from');

        if (empty($from['address'])) {
            return;
        }

        $cursor = Emails::where('sent', '=', 0)->orderby('priority')->take(10)->get();

        if (count($cursor) == 0) {
            return;
        }

        require base_path("vendor/autoload.php");

        $mail = new PHPMailer(true);
      
        // SMTP configurations
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port       = $config['port'];
        $mail->SMTPDebug  = 0;

        // Sender info
        $mail->setFrom($from['address'], $from['name']);

        // Set email format to HTML
        $mail->isHTML(true);

        // Image
        foreach($cursor as $item) {

            $para = "";
            $cc = "";
            $arquivos = "";
            try {
                $para = explode(";", $item->to);
                foreach ($para as $dest1) {
                    if (! empty(trim($dest1))) {
                        $mail->addAddress(trim($dest1));
                    }
                }

                if (! is_null($item->cc)) {
                    $cc = explode(";", $item->cc);
                    foreach ($cc as $dest2) {
                        if (! empty(trim($dest2))) {
                            $mail->addCC(trim($dest2));
                        }
                    }
                }

                $mail->Subject =  $item->subject;

                $mail->Body = $item->body;

                // Anexos
                if (! is_null($item->attachments)) {
                    $arquivos = $item->anexo;
                    $arqs = explode(';', $arquivos);
                    foreach ($arqs as $e) {
                        $file = trim($e);
                        if (file_exists($file)) {
                            $mail->AddAttachment($file, basename($file));
                        }
                    }
                }
               
                if (! $mail->send()) {
                    Log::alert("Mailer Error \n" . sprintf("~~~~\n%s\n~~~~", (string) $mail->ErrorInfo));
                }

                Emails::where('id',$item->id)->delete();

            } catch (Exception $e) {
                Log::error("Mailer Error Exception \n" . sprintf("~~~~\n%s\n~~~~", (string) $e));
            }

            $mail->clearAllRecipients();
            $mail->clearAddresses();
            $mail->clearAttachments();

        }

    }
}
