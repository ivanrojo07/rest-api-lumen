<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
	protected $limit = 10;

	public function index(Request $request) {
		$products = Product::paginate($request->limit ? $request->limit : $this->limit);
		return response()->json([
			'status' => 'success',
			'data' => $products->items(),
			'pagination' => [
				'total' => $products->total(),
				'per_page' => $products->perPage(),
				'current_page' => $products->currentPage(),
				'last_page' => $products->lastPage(),
				'from' => $products->firstItem(),
				'to' => $products->lastItem(),
			]
		], 200);
	}

	public function store(Request $request) {

		$this->validate($request, [
			'name' => 'required|string|min:3|max:155',
			'price' => 'required|numeric|min:0.01',
			'description' => 'required|string|min:5|max:255'
		]);

		$product = new Product();

		$product->name = $request->input('name');
		
		$product->price = $request->input('price');

		$product->description = $request->input('description');


        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
        
	}	

    public function show($id) {
        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product found',
            'data' => $product
        ], 200);
    }

    public function update(Request $request, $id){
        $this->validate($request, [
			'name' => 'required|string|min:3|max:155',
			'price' => 'required|numeric|min:0.01',
			'description' => 'required|string|min:5|max:255'
		]);

        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ],200);
    }


    public function destroy($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
                'data' => null
            ], 404); 
        }
        $product->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
            'data' => $product
        ],200);
    }

}
