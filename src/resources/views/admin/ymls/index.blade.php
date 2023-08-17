@extends("admin.layout")

@section("page-title", "Файлы выгрузки")

@section('header-title', "Файлы выгрузки")

@section('admin')
    @include("product-import::admin.ymls.includes.pills")
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
                                            <a href="{{ route('admin.ymls.show', ['yml' => $item]) }}"
                                               class="btn btn-dark">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger"
                                                    data-confirm="{{ "delete-form-{$item->id}" }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
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

                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
