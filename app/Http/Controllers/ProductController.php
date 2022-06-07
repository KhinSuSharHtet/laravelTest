<?php
  
namespace App\Http\Controllers;
   
use App\Models\Product;
use Illuminate\Http\Request;

use App\Services\ProductService;

use App\Repositories\ProductRepository ;

class ProductController extends Controller
{
    protected $productService;
     /**
     * ProductController Constructor
     *
     * @param ProductService $productService
     *
     */
    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        // $products = Product::latest()->paginate(5);
    
        // return view('products.index',compact('products'))
        //     ->with('i', (request()->input('page', 1) - 1) * 5);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->productService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        $products = $result['data'];
        return view('products.index', compact('products'))->with('i');
    }
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'detail',
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->productService->saveProductData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return redirect()->route('products.index')
        ->with('success','Product created successfully.');

    }
     
    /**
     * Display the specified resource.
     *
     * @param  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  
        $result = ['status' => 200];

        try {
            $result['data'] = $this->productService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        $product = $result['data']->first();
        return view('products.show',compact('product'));
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  id 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->productService->getById($id)->first();
        return view('products.edit',compact('product'));
    }
    
     /**
     * Update product.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->only([
            'name',
            'detail'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->productService->updateProduct($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return redirect()->route('products.index')
                    ->with('success','Product updated successfully');

    }
    
     /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->productService->deleteById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return redirect()->route('products.index')
                         ->with('success','Product deleted successfully');
    }
}