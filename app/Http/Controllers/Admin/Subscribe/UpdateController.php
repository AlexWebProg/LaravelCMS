<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Http\Requests\Admin\Subscribe\UpdateRequest;
use App\Models\Subscribe;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Subscribe $subscribe)
    {
        $data = $request->validated();
        $subscribe->update($data);
        return redirect()
            ->route('admin.subscribes.edit', $subscribe->id)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
