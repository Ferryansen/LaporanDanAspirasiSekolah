<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Category;
use App\Models\Report;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class PDFController extends Controller
{   
    public function pdfGenerationAllReports(){
        // Convert
        $reports = Report::all();
        $aspirations = null;
        $pdf_view = PDF::loadView('export', compact('reports', 'aspirations'));

        return $pdf_view -> download('dataLaporan.pdf');
        
    }
    
    public function pdfGenerationReportsByCategory($category_id){
        // Convert
        $category = Category::findOrFail($category_id);
        $reports = Report::where("category_id", "like", $category_id)->orderBy('isUrgent', 'desc')->orderBy('created_at', 'desc')->get();
        $aspirations = null;
        $pdf_view = PDF::loadView('export', compact('reports', 'aspirations'));

        return $pdf_view -> download('dataLaporan.pdf');
        
    }
}
