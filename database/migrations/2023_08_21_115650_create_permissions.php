<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use App\Models\Permission\Permission;

class CreatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [];
        $count =0;
        foreach (Route::getRoutes()->getIterator() as $route) {            //$routes[] = $route->uri;
            if (str_contains($route->uri, 'api') && $route->getName() != '') {
                $countPermissions = Permission::where('slug',$route->getName())->count();
                if(0==  $countPermissions){
                    $name = ucwords(implode(' ',preg_split('/(?=[A-Z])/',$route->getName())));
                $data[$count]['id'] = Str::uuid()->toString();
                $data[$count]['name'] = $name;
                $data[$count]['slug'] = $route->getName();
                
                $count++;
                }

            }
        }
        if(0<count($data)){
            Permission::insert($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
