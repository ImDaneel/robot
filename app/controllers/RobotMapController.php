<?php

class RobotMapController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /map
     *
     * @return Response
     */
    public function index($robotSn)
    {
        $where = Input::only('begin', 'end');

        App::make('Robot\Validators\MapIndexValidator')->validate($where);

        //$maps = Robot::findBySn($robotSn)->maps()->get();
        $maps = Robot::findBySn($robotSn)->maps();
        if ($where['begin']) {
            $maps = $maps->where('created_at', '>=', $where['begin']);
        }
        if ($where['end']) {
            $maps = $maps->where('created_at', '<=', $where['end']);
        }

        return JsonView::make('success', ['maps'=>$maps->get()]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /map/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /map
     *
     * @return Response
     */
    public function store($robotSn)
    {
        $robot = Robot::findBySn($robotSn);

        $data = Input::only('resolution', 'origin', 'occupied_thresh', 'free_thresh', 'negate');
        if (! is_array($data['origin'])) {
            $data['origin'] = json_decode($data['origin'], true);
        }

        App::make('Robot\Validators\MapValidator')->validate($data);

        $file = Input::file('map_file');
        $result = $this->uploadMapFile($robotSn, $file);
        if (isset($result['errors'])) {
            return JsonView::make('error', $result);
        }

        $data['file_path'] = $result['filename'];
        $data['created_at'] = time();
        $map = new Map($data);
        $map = $robot->maps()->save($map);

        return JsonView::make('success');
    }

    /**
     * Display the specified resource.
     * GET /map/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($robotSn, $mapId)
    {
        $map = Robot::findBySn($robotSn)->maps()->findOrFail($mapId);

        return Response::download($map->file_path);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /map/{id}/edit
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
     * PUT /map/{id}
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
     * DELETE /map/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function uploadMapFile($robotSn, $file)
    {
        if (! $file || ! $file->isValid()) {
            return ['errors' => 'Error while uploading file.'];
        }

        $ext = $file->getClientOriginalExtension();
        $allowed = ['pgm'];

        if ($ext && ! in_array($ext, $allowed)) {
            return ['errors' => 'You may only upload ' . implode(', ', $allowed) . '.'];
        }

        $fileName = $robotSn . '_' . date('YmdHis', time()) . '.' . $ext;
        $destPath = Config::get('app.map_path') . $robotSn . '/';
        $file->move($destPath, $fileName);

        return ['filename' => $destPath . $fileName];
    }

}
