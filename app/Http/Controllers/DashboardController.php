<?php

namespace App\Http\Controllers;

use App\Models\Funko;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index (){
        
        //contar cada modelos
        $funkosCount = Funko::count();
        $categoriesCount = Category::count();
        $usersCount = User::count();

       

        //pasar los datos a las vistas
        return view('dashboard', compact('funkosCount', 'categoriesCount', 'usersCount'));

    }
}
