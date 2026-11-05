<?php

namespace App\Features\Reports\Controllers;

use App\Features\Reports\Actions\GetFacultyReport;
use App\Features\Reports\Actions\GetLateSubmissionsReport;
use App\Features\Reports\Actions\GetLoadsReport;
use App\Features\Reports\Actions\GetNotSubmittedReport;
use App\Features\Reports\Requests\FacultyReportRequest;
use App\Features\Reports\Requests\LoadsReportRequest;
use App\Features\Reports\Requests\SubmissionReportRequest;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LoadReportsController extends Controller
{
    public function lateSubmissions(SubmissionReportRequest $request, GetLateSubmissionsReport $report): View
    {
        return view('prints.late-submissions', $report($request->validated()));
    }

    public function notSubmitted(SubmissionReportRequest $request, GetNotSubmittedReport $report): View
    {
        return view('prints.not-submitted', $report($request->validated()));
    }

    public function facultyReport(FacultyReportRequest $request, GetFacultyReport $report): View
    {
        return view('prints.faculty-report', $report($request->validated()));
    }

    public function loadsReport(LoadsReportRequest $request, GetLoadsReport $report): View
    {
        return view('prints.load-reports', $report($request->validated()));
    }
}
