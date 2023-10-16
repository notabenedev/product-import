@extends("admin.layout")

@section("page-title", "Импорт Каталога")

@section('header-title', "Импорт Каталога")

@section('admin')
    @include("product-import::admin.ymls.includes.pills")

    @can('upload', \App\ImportYml::class)
        <div class="col-12 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>Загрузить файл</h2>
                        <label for="fileInput">Выберите файл YML|XML:</label>
                        <form name="yml" enctype="multipart/form-data" method="post" action="{{ route("admin.ymls.load") }}">
                            @csrf
                            <div class="form-group d-flex">
                                <select name="type">
                                    <option value="catalog" selected>Весь каталог</option>
                                </select>
                                <input type="hidden" class="form-control" name="filename" placeholder="Имя файла" id="fileName">
                            </div>
                            <div class="form-group d-flex">
                                <input type="file" class="form-control form-control-file custom-file" name="file" id="fileInput">
                                <button  type="submit" class="btn btn-primary" id="fileSubmit" hidden>Загрузить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('viewAny', \App\ImportYml::class)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>Выгрузки</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Uuid выгрузки</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ymls as $item)
                                <tr>
                                    <td>{{ $item->uuid }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div role="toolbar" class="btn-toolbar">
                                            <div class="btn-group mr-1">
                                                @can("view", \App\ImportYml::class)
                                                    <a href="{{ route('admin.ymls.show', ['yml' => $item]) }}"
                                                       class="btn btn-dark">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can("delete", \App\ImportYml::class)
                                                    <button type="button" class="btn btn-danger"
                                                        data-confirm="{{ "delete-form-{$item->id}" }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                        @can("delete", \App\ImportYml::class)
                                            <confirm-form :id="'{{ "delete-form-{$item->id}" }}'">
                                                <template>
                                                    <form action="{{ route('admin.ymls.destroy', ['yml' => $item]) }}"
                                                          id="delete-form-{{ $item->id }}"
                                                          class="btn-group"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                    </form>
                                                </template>
                                            </confirm-form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
