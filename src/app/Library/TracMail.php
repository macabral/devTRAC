<?php
  
namespace App\Library;
  
use ProtoneMedia\Splade\Facades\Toast;
use App\Models\Emails;
  
class TracMail
{

    /**
     * Save email
     */
    public function save($mailData)
    {

        try {
            
            Emails::create($mailData);

        } catch (\Exception $e) {

            Toast::title(__('Release error!' . $e->getMessage()))->danger()->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Email sent!'))->autoDismiss(5);

        return;
    }

}