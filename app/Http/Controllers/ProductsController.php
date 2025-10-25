<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
private $products = [
    [
        'id' => 1,
        'name' => 'Bluetooth Speaker',
        'price' => 350,
        'description' => 'Portable Bluetooth speaker with high-quality sound and deep bass.',
        'image' => 'BluetoothSpeaker.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Mechanical Keyboard',
        'price' => 800,
        'description' => 'RGB mechanical keyboard with blue switches for fast and precise typing.',
        'image' => 'MechanicalKeyboard.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Monitor',
        'price' => 1200,
        'description' => '24-inch Full HD monitor with ultra-slim design and vivid colors.',
        'image' => 'Monitor.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Smart Watch',
        'price' => 950,
        'description' => 'Stylish smartwatch with heart rate monitor and fitness tracking.',
        'image' => 'SmartWatch.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Wireless Headphones',
        'price' => 600,
        'description' => 'Noise-cancelling wireless headphones with long battery life.',
        'image' => 'WirelessHeadphones.jpg'
    ],
];

    // صفحة عرض كل المنتجات
    public function index()
    {
        $products = $this->products;
        return view('products.index', compact('products'));
    }

    // صفحة إنشاء منتج جديد
    public function create()
    {
        return view('products.create');
    }

    // تخزين المنتج (مؤقت في مصفوفة)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // حفظ الصورة
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // هنا في لاب 1 مش هنسجل في قاعدة بيانات، بس نعرض القيم كـ result
        return view('products.show', [
            'product' => [
                'id' => rand(100, 999),
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'image' => $imageName
            ]
        ]);
    }

    // صفحة عرض منتج واحد (من المصفوفة)
    public function show($id)
    {
        $product = collect($this->products)->firstWhere('id', $id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        return view('products.show', compact('product'));
    }
}
