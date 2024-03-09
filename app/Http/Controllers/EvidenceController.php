<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function index($reportId){
        $report = Report::findOrFail($reportId);
        $reportEvidences = Evidence::where('reportId', $reportId)->get();

        return view('report.studentHeadmasterStaff.reportDetail', compact('report', 'reportEvidences'));
    }

    public function store (Request $request, $reportId){
        $request->validate([
            'reportEvidences.*' => 'required|image|mimes:png,jpg,jpeg,webp'
        ]);

        $report = Report::findOrFail($reportId);

        $evidenceData = [];
        if($files = $request->file('reportEvidences')){

            foreach($files as $key => $file){
                // $extension = $file->getClientOriginalExtension();
                // $fileName = $key.'-'.time(). '.' .$extension;

                // $path = "listEvidence/";
                // $file->move($path, $fileName);

                $file = $request->file('reportEvidences');
                $name = $file->getClientOriginalName();
                $filename = $key.'-'.now()->timestamp.'_'.$name;

                $imageUrl = Storage::disk('public')->putFileAs('ListImage', $file, $filename);

                $evidenceData[] = [
                    'reportId' => $report->id,
                    'image' => $imageUrl,
                    'name' => $name
                ];
            }
        }

        Evidence::insert($evidenceData);

        return redirect()->route('report.student.myReport');
    }
}
