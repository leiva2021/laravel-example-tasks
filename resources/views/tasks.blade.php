@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Nueva Tarea
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- New Task Form -->
                    <form action="{{ isset($taskEdit) ? route('updateTask', ['task' => $taskEdit]) : '/task' }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        @if(isset($taskEdit))
                            @method('PUT)
                        @endif
                        <!-- Task Name -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-3 control-label">Tarea</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" id="task-name" class="form-control" value="{{ old('name', isset($taskEdit) ? $taskEdit->name : '') }}">
                            </div>
                        </div>

                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-plus"></i> {{ isset($isEdit) ? 'Editar Tarea' : 'Agregar Tarea' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Tasks -->
            @if (count($tasks) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Tareas Actuales
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Tarea</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </thead>
                            <tbody>
                                @if(!empty($tasks))
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td class="table-text"><div>{{ $task->name }}</div></td>

                                            <!-- Task Delete Button -->
                                            <td>
                                                <form action="{{'/task/' . $task->id }}" method="POST">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}

                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fa fa-btn fa-trash"></i>Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('editTask', ['id' => $task->id]) }}" class="btn btn-warning" role="button"><i class="fa fa-btn fa-pencil"></i>Editar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <!-- Elapsed time -->
            <div class="panel panel-default">
                <div class="panel-body">
                    Tiempo de respuesta: {{ $elapsed * 1000 }} milliseconds.
                </div>
            </div>
        </div>
    </div>
@endsection
