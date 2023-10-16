<?php

namespace Notabenedev\ProductImport\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\ImportYml;
use App\Jobs\Vendor\ProductImport\ProcessYmlFile;
use App\YmlFile;
use Illuminate\Http\RedirectResponse;
use Notabenedev\ProductImport\Facades\ProductImportParserActions;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;

class ImportYmlController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $ymls = ImportYml::query()
            ->orderBy("created_at", "desc")
            ->paginate(100);
        return view("product-import::admin.ymls.index", ['ymls' => $ymls]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ImportYml  $yml
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(ImportYml $yml)
    {
        return view("product-import::admin.ymls.show",[
            'yml' => $yml,
        ]);
    }



    /**
     * @param ImportYml $yml
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(ImportYml $yml)
    {
        if (ProductImportParserActions::getJobsCount())
            return redirect()->back()->with("danger", "Очередь выгрузок не пустая. Попробуйте позже.");
        $yml->delete();
        //после удаления отправляем на index
        return redirect()->route("admin.ymls.index")->with("success", "Файл выгрузки удален");
    }


    /**
     * Отзывчивая кнопка run & other (запуск импорта через компонент - спиннер)
     *
     * @param YmlFile $file
     * @return \Illuminate\Http\JsonResponse|RedirectResponse
     */
    public function progress(YmlFile $file)
    {
        if (ProductImportParserActions::getJobsCount())
            return redirect()->back()->with("danger", "Очередь выгрузок не пустая. Попробуйте позже.");

        return response()->json(["answer" => ProductImportParserActions::getProgress($file)]);
    }
}
