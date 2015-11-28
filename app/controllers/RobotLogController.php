<?php

class RobotLogController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /robotlog
     *
     * @return Response
     */
    public function index($robotSn)
    {
        $where = Input::only('begin', 'end');

        App::make('Robot\Validators\QueryDateValidator')->validate($where);

        $logs = Robot::findBySn($robotSn)->errorLogs();
        if ($where['begin']) {
            $logs = $logs->where('created_at', '>=', $where['begin']);
        }
        if ($where['end']) {
            $logs = $logs->where('created_at', '<=', $where['end']);
        }

        return JsonView::make('success', ['reports'=>$logs->get()]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /robotlog/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /robotlog
     *
     * @return Response
     */
    public function store($robotSn)
    {
        $robot = Robot::findBySn($robotSn);

        $data = Input::all();
        App::make('Robot\Validators\ErrorLogValidator')->validate($data);

        $log = new ErrorLog($data);
        $log->created_at = time();
        $log = $robot->errorLogs()->save($log);

        return JsonView::make('success');
    }

    /**
     * Display the specified resource.
     * GET /robotlog/{id}
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
     * GET /robotlog/{id}/edit
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
     * PUT /robotlog/{id}
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
     * DELETE /robotlog/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
