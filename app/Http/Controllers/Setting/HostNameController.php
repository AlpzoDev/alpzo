<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\HostNameRequest;
use App\Services\SettingService;
use Native\Laravel\Facades\Notification;

class HostNameController extends Controller
{
    public function __invoke(HostNameRequest $request)
    {
        $data = $request->validated();
        if (SettingService::getHostName() !== $data['hostName']) {
            SettingService::setHostName($data['hostName']);
            Notification::new()->title('Success')->message('The host name was updated successfully.')->show();
        }
    }
}
