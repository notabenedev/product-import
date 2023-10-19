@extends("admin.layout")

@section("page-title", "Импорт Каталога -  Выгрузка")

@section('header-title', "Импорт Каталога -  Выгрузка")

@section('admin')
    @include("product-import::admin.ymls.includes.pills")
    @can("view", \App\ImportYml::class)
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
                                    @can('import',\App\ImportYml::class)
                                    <div role="toolbar" class="btn-toolbar">
                                        <div class="btn-group mr-1">
                                            <progress-spinner get-progress="{{ route("admin.ymls.progress",['file' => $item]) }}"
                                                                  strated="{{ $item->started_at }}"
                                                              full-import="{{ empty($item->full_import_at) ? 0 : 1 }}"
                                            >
                                            </progress-spinner>
                                        </div>
                                    </div>
                                    @endcan
                                        @isset($item->started_at)
                                            Запущена: {{ $item->started_at }}<br>
                                        @endisset
                                        @if(isset($item->full_import_at))
                                            Полная выгрузка: {{ $item->full_import_at }}
                                            @elseif (isset($item->updated_at) && isset($item->started_at))
                                            Выполнена: {{ $item->updated_at }}
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    @endcan
@endsection
