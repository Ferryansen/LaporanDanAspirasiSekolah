<?php

namespace App\Http\Controllers;

use App\Models\DownloadContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadContentController extends Controller
{
    function seeAllDownloadContent() {
        $downloadContents = DownloadContent::paginate(10)->withQueryString();

        return view('support.downloadContentView', compact('downloadContents'));
    }

    function updateDownloadContent(Request $request, $content_id) {
        $currDownloadContent = DownloadContent::findOrFail($content_id);

        $rules = [
            'title' => 'required|max:250',
            'description' => 'required|max:1000',
        ];

        $messages = [
            'title.required' => 'Jangan lupa masukin judulnya yaa',
            'title.max' => 'Judul yang kamu masukin cuman bisa maksimal 250 karakter nih',
            
            'description.required' => 'Jangan lupa masukin deskripsinya yaa',
            'description.max' => 'Deskripsi yang kamu masukin cuman bisa maksimal 1000 karakter nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'title' => $request->title,
            'description' => $request->description,
            'updatedBy' => Auth::user()->name,
        ];

        if($request->hasFile('file')) {
            $downloadContent_path = public_path().'\storage/'.$currDownloadContent->file;
            unlink($downloadContent_path);
            
            $inputFile = $request->file('file');
            $name = $inputFile->getClientOriginalName();
            $inputFileName = now()->timestamp.'_'.$name;

            $inputtedFileUrl = Storage::disk('public')->putFileAs('ListFile', $inputFile, $inputFileName);
            $credentials['file'] = $inputtedFileUrl;
        }

        $currDownloadContent->update($credentials);
        return redirect()->route('downloadcontent.seeall')->with('successMessage', 'File berhasil diperbarui');
    }

    function updateDownloadContentForm($content_id) {
        $currDownloadContent = DownloadContent::findOrFail($content_id);

        return view('support.adminHeadmasterStaff.updateDownloadContentView', compact('currDownloadContent'));
    }

    function createDownloadContent(Request $request) {
        $rules = [
            'title' => 'required|max:250',
            'description' => 'required|max:1000',
            'file' => 'required',
        ];

        $messages = [
            'title.required' => 'Jangan lupa masukin judulnya yaa',
            'title.max' => 'Judul yang kamu masukin cuman bisa maksimal 250 karakter nih',
            
            'description.required' => 'Jangan lupa masukin deskripsinya yaa',
            'description.max' => 'Deskripsi yang kamu masukin cuman bisa maksimal 1000 karakter nih',

            'file.required' => 'Jangan lupa upload file-nya yaa',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'title' => $request->title,
            'description' => $request->description,
            'createdBy' => Auth::user()->name,
        ];

        $inputFile = $request->file('file');
        $name = $inputFile->getClientOriginalName();
        $inputFileName = now()->timestamp.'_'.$name;

        $inputtedFileUrl = Storage::disk('public')->putFileAs('ListFile', $inputFile, $inputFileName);
        $credentials['file'] = $inputtedFileUrl;

        DownloadContent::create($credentials);
        return redirect()->route('downloadcontent.seeall')->with('successMessage', 'File berhasil ditambahkan');
    }

    function createDownloadContentForm() {
        return view('support.adminHeadmasterStaff.createDownloadContentView');
    }

    function deleteDownloadContent($content_id) {
        $currDownloadContent = DownloadContent::findOrFail($content_id);
        $downloadContent_path = public_path().'\storage/'.$currDownloadContent->file;
        unlink($downloadContent_path);
        $currDownloadContent->delete();

        return redirect()->route('downloadcontent.seeall')->with('successMessage', 'File berhasil dihapus');
    }
}
