<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="{{ route("admin.ymls.index") }}"
                       class="nav-link{{ $currentRoute === "admin.ymls.index" ? " active" : "" }}">
                        Список
                    </a>
                </li>


                @if (! empty($yml))
                    <li class="nav-item">
                        <a href="{{ route("admin.ymls.show", ["yml" => $yml]) }}"
                           class="nav-link{{ $currentRoute === "admin.ymls.show" ? " active" : "" }}">
                            Просмотр
                        </a>
                    </li>


                    <li class="nav-item">
                        <button type="button" class="btn btn-link nav-link"
                                data-confirm="{{ "delete-form-yml-{$yml->id}" }}">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </button>
                        <confirm-form :id="'{{ "delete-form-yml-{$yml->id}" }}'">
                            <template>
                                <form action="{{ route('admin.ymls.destroy', ['yml' => $yml]) }}"
                                      id="delete-form-yml-{{ $yml->id }}"
                                      class="btn-group"
                                      method="post">
                                    @csrf
                                    @method("delete")
                                </form>
                            </template>
                        </confirm-form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
