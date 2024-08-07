<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use ZipArchive;
use App\Models\Documents;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Support\Facades\Session;

class FilesdocController extends Controller
{
    /**
     * Upload a document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload($id, Request $request)
    {

        $this->validate($request, [
            'arquivos' => 'required'
        ]);

        $iddoc = base64_decode($id);

        $project = Session::get('ret')[0]['id'];

        $doc = Documents::findOrFail($iddoc);

        $arqs = $request->file('arquivos');
        
        if (!is_null($arqs)) {

            $created = substr($doc['created_at'],0,4);
            $destinationPath = public_path('uploads/' . $project . '/' . $created) . '/' . $doc['file'];

            if (!file_exists($destinationPath) || empty($doc['file']) ) {

                $destinationPath = public_path('uploads/' . $project . '/'. $created);
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0757, true);
                }
                
                $zip_file = Uuid::uuid4() . '.zip';
                while (file_exists($destinationPath . '/' . $zip_file)) {
                    $zip_file = Uuid::uuid4() . '.zip';
                }
                $doc['file'] = $zip_file;
                $destinationPath =  $destinationPath . '/' . $zip_file;
                $tipo = ZipArchive::CREATE | ZipArchive::OVERWRITE;

                $doc->save();
               
            } else {

                $zip_file = $doc['file'];
                $tipo = 0;

            }

            $zip = new ZipArchive;

            if ($zip->open($destinationPath, $tipo)) {

                foreach($arqs as $file) {
                            
                    $zip->addFile($file, basename($file->getClientOriginalName()));

                }

                $zip->close();

            }

            Toast::title(__('File saved!'))->autoDismiss(5);
    
            return redirect()->back();

        }

    }

    /**
     * View files from a especific document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($retId)
    {
        
        $id = base64_decode($retId);

        $project = Session::get('ret')[0]['id'];

        $docs = Documents::findOrFail($id);

        // exclui os arquivo da pasta

        $pasta = public_path('/uploads/downloads/' . auth('sanctum')->user()->id);
        if (!is_dir($pasta)) {
            mkdir($pasta, 0757, true);
        }

        $files = glob($pasta . '/*'); 
        foreach($files as $file){ 
            if(is_file($file)) {
                unlink($file); 
            }
        }

        $file = ''; $list = []; 
        
        $nomearq = $docs->file;

        if ($nomearq != '') {
            $created = date('Y', strtotime($docs['created_at']));
            $file = public_path('/uploads/' . $project . '/' . $created . '/' . $docs['file']);

            if (file_exists($file)) {

                $zip = new ZipArchive();
                
                if ($zip->open($file, \ZipArchive::RDONLY)) {
                    $numfiles = $zip->count();
                    for($idx=0; $idx < $numfiles; $idx++) {

                        $parts = explode(DIRECTORY_SEPARATOR, $zip->getNameIndex($idx));
                        array_push($list, $parts);
            
                    }

                    $zip->extractTo($pasta);

                    $zip->close();
                }

            } else {
                $nomearq = '';
            }
        }

        $ret = array(
            "id" => $retId,
            "iddoc" => $id,
            "file" => $nomearq,
            "files" => $list,
        );

        return view('filesdoc.view-files', [
            'ret' => $ret,
        ]);
    }

    /**
     * Delete a file in ZIP
     *
     * @param  int  $id $nomearq
     * @return \Illuminate\Http\Response
     */
    public function deleteFile($id, $nomearq)
    {

        $iddoc = base64_decode($id);

        $project = Session::get('ret')[0]['id'];

        $doc = Documents::findOrFail($iddoc);

        // Path to the file
        $created = date('Y', strtotime($doc['created_at']));
        $path = public_path('uploads/' . $project . '/'. $created . '/' . $doc['file']);

        if (file_exists($path)) {

            $zip = new ZipArchive();

            if ($zip->open($path) === TRUE) {
                $zip->deleteName($nomearq);

                $zip->close();

                Toast::title(__('File deleted!'))->autoDismiss(5);
                
            }
        }

        return redirect()->back();

    }

    /**
     * View download a especific document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {

        $id = base64_decode($id);

        $project = Session::get('ret')[0]['id'];

        $doc = Documents::findOrFail($id);

        // Path to the file
        $created = date('Y', strtotime($doc['created_at']));
        $path = public_path('/uploads/' . $project . '/' . $created . '/' . $doc['file']);

        // This is based on file type of $path, but not always needed    
        $mm_type = "application/octet-stream";

        //Set headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: " . $mm_type);
        header("Content-Length: " .(string)(filesize($path)) );
        header('Content-Disposition: attachment; filename="'.basename($path).'"');
        header("Content-Transfer-Encoding: binary\n");

        // Outputs the content of the file
        readfile($path);

    }

    /**
     * View download a especific document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function openfile($filename)
    {

        // Path to the file
        $path = public_path( '/uploads/downloads/' . auth('sanctum')->user()->id . '/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            Toast::title(__('File not found.'))->danger()->autoDismiss(5);
            return redirect()->back();
        }

    }
}
