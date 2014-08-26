<?php

class StoreController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	public function showIndex()
	{
		//$categorias = Category::all();
		$menu = array();
		$categorias = Category::where('parent_id', 0)->get();
		if (!empty($categorias)) {

			foreach ($categorias as $categoria) {

				//echo "<dl>";
				//echo "<dt>".$categoria->nombre."<dt>";
				$subcategorias = Category::where('parent_id', $categoria->id)->get();
				if (!empty($subcategorias)) {
					$subcats = array();
					foreach ($subcategorias as $subcategoria) {
						$subcats[] = array('id_subcategoria'=>$subcategoria->id,'nombre_subcategoria'=>$subcategoria->nombre);
						//echo "<dd>".$subcategoria->nombre."<dd>";
						/*
						$subsubcategorias = Category::where('parent_id', $subcategoria->id)->get();
						foreach ($subsubcategorias as $subsubcategoria) {
							//$count_products = Product::where('id_categoria', $subsubcategoria->id)->count();
							//echo "<dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$subsubcategoria->nombre."(".$count_products.")<dd>";
							echo "<dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$subsubcategoria->nombre."<dd>";
						}
						*/
					}
					$menu[] = array('id_categoria'=>$categoria->id,'nombre_categoria'=>$categoria->nombre,'subcategorias'=>$subcats);
				}
				//echo "</dl>";
			}
		}

		$data["menu"] = $menu;
		$view = View::make('index');
		$view->nest('menu', 'commons.menu',$data);
		$view->nest('slider', 'commons.slider');
		$view->nest('ofertas', 'commons.ofertas');
		$view->nest('destacados', 'commons.destacados');
		$view->nest('navbar', 'commons.navbar');
		$view->nest('footer', 'commons.footer');
		return $view;
	}
}
