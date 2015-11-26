<?php

class RobotReportController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /cleanreport
     *
     * @return Response
     */
    public function index($robotSn)
    {
        $where = Input::only('begin', 'end');

        App::make('Robot\Validators\QueryDateValidator')->validate($where);

        $reports = Robot::findBySn($robotSn)->cleanReports();
        if ($where['begin']) {
            $reports = $reports->where('created_at', '>=', $where['begin']);
        }
        if ($where['end']) {
            $reports = $reports->where('created_at', '<=', $where['end']);
        }

        return JsonView::make('success', ['reports'=>$reports->get()]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /cleanreport/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /cleanreport
     *
     * @return Response
     */
    public function store($robotSn)
    {
        $robot = Robot::findBySn($robotSn);

        $data = Input::all();
        App::make('Robot\Validators\ReportValidator')->validate($data);

        $report = new CleanReport($data);
        $report = $robot->cleanReports()->save($report);

        return JsonView::make('success');
    }

    /**
     * Display the specified resource.
     * GET /cleanreport/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /cleanreport/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /cleanreport/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /cleanreport/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
