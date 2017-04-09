<?php namespace DummyNamespace\Http\Controllers\Ecommerce;

use WebEd\Plugins\Ecommerce\Models\Contracts\ProductCategoryModelContract;
use WebEd\Plugins\Ecommerce\Models\ProductCategory;
use DummyNamespace\Http\Controllers\AbstractController;

class ProductCategoryController extends AbstractController
{
    /**
     * @var ProductCategory
     */
    protected $category;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param ProductCategory $item
     * @param array $data
     */
    public function handle(ProductCategoryModelContract $item, array $data)
    {
        $this->dis = $data;

        $this->category = $item;

        $this->getMenu('product-category', $item->id);

        $happyMethod = '_template_' . studly_case($item->page_template);

        if(method_exists($this, $happyMethod)) {
            return $this->$happyMethod();
        }

        return $this->defaultTemplate();
    }

    protected function defaultTemplate()
    {
        return $this->view('front.product-category-templates.default');
    }
}
