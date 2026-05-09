<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) {
            $data_search->where('kode', $kode);
        }

        if (!empty($nama)) {
            $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        if ($hargamin !== null && $hargamin !== '') {
            $data_search->where('harga_beli', '>=', $hargamin);
        }

        if ($hargamax !== null && $hargamax !== '') {
            $data_search->where('harga_beli', '<=', $hargamax);
        }

        $data_search = $data_search
            ->select('kode', 'nama', 'foto', 'jenis', 'harga_beli', 'laba', 'supplier')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;

        $data['categories'] = Category::orderBy('name')->get();

        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {

            $data_item = new MasterItem;

            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            sleep(3);
        } else {

            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $path = $data_item->foto ?? null;

        if ($request->hasFile('foto')) {

            $path = $request->file('foto')->store('items', 'public');
        }

        $data_item->nama = $request->nama;
        $data_item->foto = $path;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->category_id = $request->category_id;



        $data_item->save();

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }

    public function exportExcel()
    {
        $items = MasterItem::with('category')->get();

        return (new FastExcel($items))->download('master-items.xlsx', function ($item) {

            return [
                // 'Kode'         => $item->kode,
                'Kategori'     => $item->category->name ?? '-',
                'Nama'         => $item->nama,
                'Supplier'     => $item->supplier,
                'Harga Beli'   => $item->harga_beli,
                'Laba'         => $item->laba,
                'Harga Jual'   => $item->harga_beli + ($item->harga_beli * $item->laba / 100),
            ];
        });
    }
}
