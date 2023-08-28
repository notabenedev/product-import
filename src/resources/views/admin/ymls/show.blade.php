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
                        <th>Детали</th>
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
                                    <div class="btn-group mr-1{{ $item->started_at ? " d-none" : ""  }}">
                                        <progress-spinner get-progress="{{ route("admin.ymls.progress",['file' => $item]) }}"
                                                              title="{{ $item->started_at }}"
                                        >
                                        </progress-spinner>
                                    </div>
                                    @if ($item->started_at && request()->has("action"))
                                        @role('admin')
                                        <div>
                                            <button type="button" class="btn btn-primary"
                                                    data-confirm="{{ "run-form-{$item->id}" }}"
                                                    title="{{ $item->started_at ? $item->started_at: "Запустить импорт вручную" }}">
                                                <i class="fas fa-play"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger"
                                                    {{  siteconf()->get("product-import","xml-category-import-type") !== "full" ? "disabled" : "" }}
                                                    data-confirm="{{ "other-form-{$item->id}" }}"
                                                    title="{{ $item->full_import_at ? $item->full_import_at: "Скрыть непереданные категории и товары" }}">
                                                <i class="fas fa-minus"></i>
                                            </button>

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
                                        </div>
                                        @endrole('admin')
                                    @endif
                                </div>
                            </td>
                            <td>
                                @isset($item->started_at)
                                    Запущена: {{ $item->started_at }}<br>
                                @endisset
                                @isset($item->full_import_at)
                                    Полная выгрузка: {{ $item->full_import_at }}
                                @endisset
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
