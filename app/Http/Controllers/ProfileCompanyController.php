<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileCompaneRequest;
use App\Models\Company;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\ProfileCompany;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use Request;

class ProfileCompanyController extends Controller
{
    public function update(ProfileCompaneRequest $request, int $id)
    {
        $request->validated();
        if ($request->hasFile('image')) {

            $pathImage = $resulte['pathINewImage'];
        }
        $profile = ProfileCompany::findOrFail($id);
        $profile->update($request->safe()->except(['image']));
        $profile->refresh();
        return response()->json([
            'message' => 'update is done',
            'profile' => $profile,
            'image' => $pathImage

        ], 200);
    }
    //////تغيير الlogo

}
