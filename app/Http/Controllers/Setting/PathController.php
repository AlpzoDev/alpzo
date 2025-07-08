<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\PathUpdateRequest;
use App\Http\Requests\SttingPathRequest;
use App\Jobs\CheckNewProjectJob;
use App\Models\Path;
use App\Services\SettingService;
use Native\Laravel\Dialog;
use Native\Laravel\Facades\Notification;

class PathController extends Controller
{
public function store( SttingPathRequest $request)
{
$path = Dialog::new()
->title('Update Path')
->folders()
->open();
if (!$path || Path::where('path', $path)->exists()) {
Notification::new()->title('Error')->message('The path already exists or is invalid.')->show();
return;
}


$v= $request->validated();
$v['path'] = $path;
if (Path::create($v)) {
Notification::new()->title('Success')->message('The path was created successfully.')->show();
defer(function (){
    dispatch_sync(new CheckNewProjectJob());
});
}
else {
Notification::new()->title('Error')->message('The path already exists.')->show();
}
}



public function update(Path $path, PathUpdateRequest $request)
{
$v = $request->validated();
$pathStr = Dialog::new()
->title('Update Path')
->defaultPath($path->path)
->folders()
->open();
if (!$pathStr) {
Notification::new()->title('Error')->message('The path already exists or is invalid.')->show();
return;
}
$v['path'] = $pathStr;
if ($path->update($v)) {
Notification::new()->title('Success')->message('The path was updated successfully.')->show();
}
else {
Notification::new()->title('Error')->message('The path not updated.')->show();
}
}

public function destroy(Path $path)
{
if ($path->is_default) {
Notification::new()->title('Error')->message('The default path cannot be deleted.')->show();
return;
}

if ($path->delete()) {
Notification::new()->title('Success')->message('The path was deleted successfully.')->show();
}
else {
Notification::new()->title('Error')->message('The path not deleted.')->show();
}
}

public function default(Path $path){
SettingService::setDefaultPath($path->path);
Path::whereNotIn('id',[$path->id])->update([
'is_default' => false
]);
$path->is_default = true;
$path->save();
Notification::new()->title('Success')->message('The path was set as default successfully.')->show();
}


}
