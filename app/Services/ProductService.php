<?php

namespace App\Services;

use App\Models\product;
use App\Dao\ProductDao;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ProductService
{
    /**
     * @var $productDao
     */
    protected $productDao;

    /**
     * productService constructor.
     *
     * @param ProductDao $productDao
     */
    public function __construct(ProductDao $productDao)
    {
        $this->ProductDao= $productDao;
    }

    /**
     * Delete product by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $product = $this->ProductDao->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete product data');
        }

        DB::commit();

        return $product;

    }

    /**
     * Get all product.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->ProductDao->getAll();
    }

    /**
     * Get product by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->ProductDao->getById($id);
    }

    /**
     * Update product data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateProduct($data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'bail|min:2',
            'detail' => 'bail|max:255'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $product = $this->ProductDao->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();
    }

    /**
     * Validate product data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function saveProductData($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->ProductDao->save($data);

        return $result;
    }

}