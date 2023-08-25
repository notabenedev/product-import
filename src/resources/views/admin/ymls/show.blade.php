@extends("admin.layout")

@section("page-title", "Выгрузка Cml - ")

@section('header-title', "Выгрузка Cml")

@section('admin')
    @include("product-import::admin.ymls.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="cml-uuid">
                    <h2>{{ $yml->uuid }}</h2>
                </div>
                <div class="yml-date">
                    {{ $yml->human_created }}
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Имя файла</th>
                        <th>Тип</th>
                        <th>Дата загрузки</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($yml->files as $item)
                        <tr>
                            <td><a href="{{ asset("storage/".$item->path) }}" target="_blank">{{ $item->original_name }}</a></td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <div role="toolbar" class="btn-toolbar">
                                    <div class="btn-group mr-1">
                                        <button type="button" class="btn btn-warning"
{{--                                                {{  $item->started_at ? "disabled" : "" }}--}}
                                                data-confirm="{{ "run-form-{$item->id}" }}"
                                                title="{{ $item->started_at }}">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger"
                                                {{  siteconf()->get("product-import","xml-category-import-type") !== "full" ? "disabled" : "" }}
                                                data-confirm="{{ "other-form-{$item->id}" }}"
                                                title="{{ $item->full_import_at ? $item->full_import_at: "Скрыть непереданные категории и товары" }}">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <confirm-form :id="'{{ "run-form-{$item->id}" }}'" confirm-text="Да, запустить">
                                    <template>
                                        <form action="{{ route('admin.ymls.run', ['file' => $item]) }}"
                                              id="run-form-{{ $item->id }}"
                                              class="btn-group"
                                              method="post">
                                            @csrf
                                            @method("put")
                                        </form>
                                    </template>
                                </confirm-form>
                                <confirm-form :id="'{{ "other-form-{$item->id}" }}'" confirm-text="Да, скрыть отсутствующие сущности">
                                    <template>
                                        <form action="{{ route('admin.ymls.other', ['file' => $item]) }}"
                                              id="other-form-{{ $item->id }}"
                                              class="btn-group"
                                              method="post">
                                            @csrf
                                            @method("put")
                                        </form>
                                    </template>
                                </confirm-form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
