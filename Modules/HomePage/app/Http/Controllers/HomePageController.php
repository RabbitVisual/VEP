<?php
/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA.
 * Versão: vs.1.0.0
 */

namespace VertexSolutions\HomePage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class HomePageController extends Controller
{
    public function index(): View
    {
        return view('homepage::homepage');
    }

    public function faq(): View
    {
        return view('homepage::pages.faq');
    }

    public function about(): View
    {
        return view('homepage::pages.about');
    }

    public function pricing(): View
    {
        return view('homepage::pages.pricing');
    }

    public function contact(): View
    {
        return view('homepage::pages.contact');
    }

    public function privacy(): View
    {
        return view('homepage::pages.legal.privacy');
    }

    public function terms(): View
    {
        return view('homepage::pages.legal.terms');
    }

    public function cookies(): View
    {
        return view('homepage::pages.legal.cookies');
    }
}
