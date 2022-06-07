<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * ProductRepository constructor.
     * 
     * @param Product $product
     */
    public function __construct(Product $product) {
        $this->product = $product;
    }

    /**
     * Get All Products
     * 
     * @return Product $product
     */
    public function getAll() 
    {
        return $this->product
            ->get();
    }
    /**
     * Get product by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->product
            ->where('id', $id)
            ->get();
    }

    /**
     * Save Product
     *
     * @param $data
     * @return Product
     */
    public function save($data)
    {
        $product = new $this->product;

        $product->name = $data['name'];
        $product->detail = $data['detail'];

        $product->save();

        // return $product->fresh();

        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Update Product
     *
     * @param $data
     * @return Product
     */
    public function update($data, $id)
    {
        
        $product = $this->product->find($id);

        $product->name = $data['name'];
        $product->detail = $data['detail'];

        $product->update();

        return $product;
    }

    /**
     * Update Product
     *
     * @param $data
     * @return Product
     */
    public function delete($id)
    {
        
        $product = $this->product->find($id);
        $product->delete();

        return $product;
    }
}